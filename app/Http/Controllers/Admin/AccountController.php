<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branch;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Middleware is applied at route level
    }

    /**
     * Display a listing of accounts.
     */
    public function index()
    {
        $accounts = Account::with(['branch', 'creator', 'approvedBy', 'collaterals'])->orderBy('created_at', 'desc')->get();
        $totalAccounts = $accounts->count();
        $activeAccounts = $accounts->where('status', 'active')->count();
        $totalValue = $accounts->sum(function ($account) {
            return $account->collaterals->sum('estimated_value_rm');
        });

        return view('admin.accounts.index', compact('accounts', 'totalAccounts', 'activeAccounts', 'totalValue'));
    }

    /**
     * Show the form for creating a new account.
     */
    public function create()
    {
        // Check if user can create accounts
        if (!auth()->user()->canMake()) {
            return redirect()->back()->with('error', 'You do not have permission to create accounts.');
        }

        $branches = Branch::where('status', 'active')->orderBy('name')->get();

        return view('admin.accounts.create', compact('branches'));
    }

    /**
     * Store a newly created account in storage.
     */
    public function store(Request $request)
    {
        // Check if user can create accounts
        if (!auth()->user()->canMake()) {
            return redirect()->back()->with('error', 'You do not have permission to create accounts.');
        }

        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'account_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'submit_action' => 'sometimes|in:draft,submit_for_approval'
        ]);

        $accountData = [
            'branch_id' => $request->branch_id,
            'account_title' => $request->account_title,
            'description' => $request->description,
            'created_by_user_id' => auth()->id(),
        ];

        // Determine status based on submit action
        if ($request->submit_action === 'submit_for_approval') {
            $accountData['status'] = Account::STATUS_PENDING_APPROVAL;
        } else {
            $accountData['status'] = Account::STATUS_DRAFT;
        }

        $account = Account::create($accountData);

        $message = $accountData['status'] === Account::STATUS_PENDING_APPROVAL
            ? 'Account created and submitted for approval successfully.'
            : 'Account saved as draft successfully.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'account' => $account->fresh(['branch', 'creator', 'approvedBy'])
            ]);
        }

        return redirect()->route('admin.accounts.index')->with('success', $message);
    }

    /**
     * Display the specified account.
     */
    public function show(Account $account)
    {
        $account->load(['branch', 'creator', 'approvedBy', 'collaterals.auction']);

        return view('admin.accounts.show', compact('account'));
    }

    /**
     * Show the form for editing the specified account.
     */
    public function edit(Account $account)
    {
        // Check if user can edit this account
        if (!auth()->user()->canMake() && $account->created_by_user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You do not have permission to edit this account.');
        }

        // Cannot edit approved accounts unless admin
        if ($account->status === 'active' && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot edit an approved account.');
        }

        $branches = Branch::where('status', 'active')->orderBy('name')->get();

        return view('admin.accounts.edit', compact('account', 'branches'));
    }

    /**
     * Update the specified account in storage.
     */
    public function update(Request $request, Account $account)
    {
        // Check if user can edit this account
        if (!auth()->user()->canMake() && $account->created_by_user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You do not have permission to edit this account.');
        }

        // Cannot edit approved accounts unless admin
        if ($account->status === 'active' && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot edit an approved account.');
        }

        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'account_title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'submit_action' => 'sometimes|in:draft,submit_for_approval'
        ]);

        $updateData = [
            'branch_id' => $request->branch_id,
            'account_title' => $request->account_title,
            'description' => $request->description,
        ];

        // Handle status change if submit_action is provided
        if ($request->has('submit_action')) {
            if ($request->submit_action === 'submit_for_approval' && $account->status === 'draft') {
                $updateData['status'] = Account::STATUS_PENDING_APPROVAL;
            } elseif ($request->submit_action === 'draft' && $account->status === 'draft') {
                // Keep as draft - no status change needed
                $updateData['status'] = Account::STATUS_DRAFT;
            }
        }

        $account->update($updateData);

        $message = isset($updateData['status']) && $updateData['status'] === Account::STATUS_PENDING_APPROVAL
            ? 'Account updated and submitted for approval successfully.'
            : 'Account updated successfully.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'account' => $account->fresh(['branch', 'creator', 'approvedBy'])
            ]);
        }

        return redirect()->route('admin.accounts.index')->with('success', $message);
    }

    /**
     * Toggle the status of the specified account.
     */
    public function toggleStatus(Account $account)
    {
        $newStatus = $account->status === 'active' ? 'inactive' : 'active';
        $account->update(['status' => $newStatus]);

        $status = $account->status === 'active' ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Account {$account->account_title} has been {$status}.");
    }

    /**
     * Remove the specified account.
     */
    public function destroy(Account $account)
    {
        // Check if user can delete this account
        if (!auth()->user()->canMake() && $account->created_by_user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'You do not have permission to delete this account.');
        }

        // Cannot delete active accounts with collaterals
        if ($account->status === 'active' && $account->collaterals()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete an active account that has collaterals. Please deactivate first.');
        }

        $accountTitle = $account->account_title;

        // Soft delete if the model supports it, otherwise hard delete
        $account->delete();

        return redirect()->route('admin.accounts.index')->with('success', "Account '{$accountTitle}' has been deleted successfully.");
    }

    /**
     * Submit account for approval.
     */
    public function submitForApproval(Account $account)
    {
        if ($account->status !== 'draft') {
            return redirect()->back()->with('error', 'Only draft accounts can be submitted for approval.');
        }

        if ($account->created_by_user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'You can only submit your own accounts for approval.');
        }

        $account->update(['status' => 'pending_approval']);

        return redirect()->back()->with('success', "Account '{$account->account_title}' has been submitted for approval.");
    }

    /**
     * Approve an account.
     */
    public function approve(Account $account)
    {
        if (!auth()->user()->canApprove($account)) {
            return redirect()->back()->with('error', 'You do not have permission to approve this account.');
        }

        if ($account->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Account is not pending approval.');
        }

        $account->update([
            'status' => 'active',
            'approved_by_user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "Account '{$account->account_title}' has been approved and is now active.");
    }

    /**
     * Reject an account.
     */
    public function reject(Account $account)
    {
        if (!auth()->user()->canApprove($account)) {
            return redirect()->back()->with('error', 'You do not have permission to reject this account.');
        }

        if ($account->status !== 'pending_approval') {
            return redirect()->back()->with('error', 'Account is not pending approval.');
        }

        $account->update([
            'status' => 'rejected',
            'approved_by_user_id' => auth()->id(),
        ]);

        return redirect()->back()->with('success', "Account '{$account->account_title}' has been rejected.");
    }

    /**
     * Show collaterals for the specified account.
     */
    public function collaterals(Account $account)
    {
        $collaterals = $account->collaterals()->with(['auction'])->orderBy('created_at', 'desc')->get();
        return view('admin.account-collaterals', compact('account', 'collaterals'));
    }
}
