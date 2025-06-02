<?php

namespace App\Http\Controllers\Admin;

use App\Models\Branch;
use App\Models\BranchAddress;
use App\Models\User;
use App\Services\ValidationService;
use Illuminate\Http\Request;

class BranchController extends BaseAdminController
{
    protected string $modelClass = Branch::class;
    protected string $routePrefix = 'admin.branches';
    protected string $entityName = 'Branch';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Display a listing of branches.
     */
    public function index()
    {
        // Calculate statistics from all branches
        $statistics = [
            'total' => Branch::count(),
            'active' => Branch::where('status', 'active')->count(),
            'pending' => Branch::where('status', 'pending_approval')->count(),
            'locations' => Branch::where('status', 'active')->count(),
        ];

        // Get paginated branches
        $branches = Branch::with(['creator', 'approvedBy', 'branchAddress', 'accounts.collaterals'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(15);

        return view('admin.branches.index', compact('branches', 'statistics'));
    }

    /**
     * Show the form for creating a new branch.
     */
    public function create()
    {
        return view('admin.branches.create');
    }

    /**
     * Display the specified branch.
     */
    public function show(Branch $branch)
    {
        $branch->load(['creator', 'approvedBy', 'accounts.collaterals']);

        $statistics = [
            'total_accounts' => $branch->accounts->count(),
            'active_accounts' => $branch->accounts->where('status', 'active')->count(),
            'total_collaterals' => $branch->accounts->sum(function($account) {
                return $account->collaterals->count();
            }),
            'total_estimated_value' => $branch->accounts->sum(function($account) {
                return $account->collaterals->sum('estimated_value_rm');
            })
        ];

        return view('admin.branches.show', compact('branch', 'statistics'));
    }

    /**
     * Show the form for editing the specified branch.
     */
    public function edit(Branch $branch)
    {
        return view('admin.branches.edit', compact('branch'));
    }

    /**
     * Store a newly created branch.
     */
    public function store(Request $request)
    {
        return $this->handleAction(function () use ($request) {
            $validationRules = ValidationService::getBranchRules();
            $request->validate($validationRules, ValidationService::getCustomMessages());

            // Create branch address first
            $branchAddress = BranchAddress::create([
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->city,
                'state' => $request->state,
                'postcode' => $request->postcode,
                'country' => $request->country,
            ]);

            // Create branch with address reference
            $branch = Branch::create([
                'name' => $request->name,
                'branch_address_id' => $branchAddress->id,
                'phone_number' => $request->phone_number,
                'status' => 'pending_approval',
                'created_by_user_id' => auth()->id(),
            ]);

            // Update branch address with branch reference
            $branchAddress->update(['branch_id' => $branch->id]);

            $message = 'Branch created and submitted for approval successfully.';

            if ($request->expectsJson()) {
                return $branch->load(['creator', 'approvedBy', 'branchAddress']);
            }

            return $this->redirectToIndex('admin.branches.index', $message);
        }, $request);
    }

    /**
     * Update the specified branch.
     */
    public function update(Branch $branch, Request $request)
    {
        // Check if user can edit this branch
        if (!auth()->user()->canMake() && $branch->created_by_user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You do not have permission to edit this branch.');
        }

        // Cannot edit approved branches unless admin
        if ($branch->status === 'active' && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot edit an approved branch.');
        }

        $request->validate([
            'name' => 'required|string|max:100|unique:branches,name,' . $branch->id,
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postcode' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'phone_number' => 'nullable|string|max:20',
        ], [
            'name.unique' => 'A branch with this name already exists.',
        ]);

        // Update branch address
        if ($branch->branchAddress) {
            $branch->branchAddress->update([
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->city,
                'state' => $request->state,
                'postcode' => $request->postcode,
                'country' => $request->country,
            ]);
        } else {
            // Create branch address if it doesn't exist
            $branchAddress = BranchAddress::create([
                'branch_id' => $branch->id,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->city,
                'state' => $request->state,
                'postcode' => $request->postcode,
                'country' => $request->country,
            ]);

            $branch->update(['branch_address_id' => $branchAddress->id]);
        }

        $branch->update([
            'name' => $request->name,
            'phone_number' => $request->phone_number,
            'status' => 'pending_approval',
        ]);

        $message = 'Branch updated and submitted for approval successfully.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'branch' => $branch->fresh(['creator', 'approvedBy'])
            ]);
        }

        return redirect()->route('admin.branches.index')->with('success', $message);
    }

    /**
     * Toggle the status of the specified branch.
     */
    public function toggleStatus(Branch $branch)
    {
        $newStatus = $branch->status === 'active' ? 'inactive' : 'active';
        $branch->update(['status' => $newStatus]);

        $status = $newStatus === 'active' ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Branch {$branch->name} has been {$status}.");
    }

    /**
     * Remove the specified branch.
     */
    public function destroy(Branch $branch)
    {
        // Check if user can delete this branch
        if (!auth()->user()->canMake() && $branch->created_by_user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You do not have permission to delete this branch.');
        }

        // Cannot delete active branches with accounts
        if ($branch->status === 'active' && $branch->accounts()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete an active branch that has accounts. Please deactivate first.');
        }

        $branchName = $branch->name;

        // Soft delete if the model supports it, otherwise hard delete
        $branch->delete();

        return redirect()->route('admin.branches.index')->with('success', "Branch '{$branchName}' has been deleted successfully.");
    }

    /**
     * Submit branch for approval.
     */
    public function submitForApproval(Branch $branch)
    {
        if ($branch->status !== 'draft') {
            return redirect()->back()->with('error', 'Only draft branches can be submitted for approval.');
        }

        if ($branch->created_by_user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'You can only submit your own branches for approval.');
        }

        $branch->update(['status' => 'pending_approval']);

        return redirect()->back()->with('success', "Branch '{$branch->name}' has been submitted for approval.");
    }

    /**
     * Approve a branch.
     */
    public function approve(Branch $branch)
    {
        if (!auth()->user()->canApprove($branch)) {
            return redirect()->back()->with('error', 'You do not have permission to approve this branch.');
        }

        if ($branch->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Branch is not pending approval.');
        }

        $branch->update([
            'status' => 'active',
            'approved_by_user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "Branch '{$branch->name}' has been approved and is now active.");
    }

    /**
     * Reject a branch.
     */
    public function reject(Branch $branch)
    {
        if (!auth()->user()->canApprove($branch)) {
            return redirect()->back()->with('error', 'You do not have permission to reject this branch.');
        }

        if ($branch->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Branch is not pending approval.');
        }

        $branch->update([
            'status' => 'rejected',
            'approved_by_user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "Branch '{$branch->name}' has been rejected.");
    }

    /**
     * Bulk operations for branches.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete,activate,deactivate',
            'branch_ids' => 'required|array|min:1',
            'branch_ids.*' => 'exists:branches,id'
        ]);

        $branches = Branch::whereIn('id', $request->branch_ids)->get();
        $action = $request->action;
        $count = 0;

        foreach ($branches as $branch) {
            switch ($action) {
                case 'approve':
                    if ($branch->status === 'pending_approval' && auth()->user()->canApprove($branch)) {
                        $branch->update([
                            'status' => 'active',
                            'approved_by_user_id' => auth()->id()
                        ]);
                        $count++;
                    }
                    break;
                case 'reject':
                    if ($branch->status === 'pending_approval' && auth()->user()->canApprove($branch)) {
                        $branch->update([
                            'status' => 'rejected',
                            'approved_by_user_id' => auth()->id()
                        ]);
                        $count++;
                    }
                    break;
                case 'delete':
                    if (auth()->user()->canMake() || $branch->created_by_user_id === auth()->id()) {
                        if ($branch->status !== 'active' || $branch->accounts()->count() === 0) {
                            $branch->delete();
                            $count++;
                        }
                    }
                    break;
                case 'activate':
                    if ($branch->status === 'inactive' && auth()->user()->isAdmin()) {
                        $branch->update(['status' => 'active']);
                        $count++;
                    }
                    break;
                case 'deactivate':
                    if ($branch->status === 'active' && auth()->user()->isAdmin()) {
                        $branch->update(['status' => 'inactive']);
                        $count++;
                    }
                    break;
            }
        }

        $actionText = [
            'approve' => 'approved',
            'reject' => 'rejected',
            'delete' => 'deleted',
            'activate' => 'activated',
            'deactivate' => 'deactivated'
        ];

        return redirect()->back()->with('success', "{$count} branches have been {$actionText[$action]} successfully.");
    }
}
