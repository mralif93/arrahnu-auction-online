<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ValidationService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

abstract class BaseAdminController extends Controller
{
    /**
     * The model class for this controller.
     */
    protected string $modelClass;

    /**
     * The route prefix for redirects.
     */
    protected string $routePrefix;

    /**
     * The entity name for messages.
     */
    protected string $entityName;

    /**
     * Check if user can perform maker actions.
     */
    protected function canMake(): bool
    {
        return Auth::user()->canMake();
    }

    /**
     * Check if user can perform checker actions.
     */
    protected function canApprove(Model $model = null): bool
    {
        return Auth::user()->canApprove($model);
    }

    /**
     * Check if user owns the record.
     */
    protected function ownsRecord(Model $model): bool
    {
        return $model->created_by_user_id === Auth::id();
    }

    /**
     * Check if user can edit the record.
     */
    protected function canEdit(Model $model): bool
    {
        if (!in_array($model->status, ['draft', 'rejected'])) {
            return false;
        }

        return $this->canMake() || $this->ownsRecord($model);
    }

    /**
     * Check if user can delete the record.
     */
    protected function canDelete(Model $model): bool
    {
        if ($model->status === 'active') {
            return false;
        }

        return $this->canMake() || $this->ownsRecord($model);
    }

    /**
     * Handle entity creation with workflow.
     */
    protected function createEntity(Request $request, array $validationRules, array $additionalData = []): JsonResponse|RedirectResponse
    {
        return $this->handleAction(function () use ($request, $validationRules, $additionalData) {
            $request->validate($validationRules, ValidationService::getCustomMessages());

            $status = $request->submit_action === 'submit_for_approval' ? 'pending_approval' : 'draft';

            $data = array_merge(
                $request->except(['submit_action', '_token']),
                $additionalData,
                [
                    'status' => $status,
                    'created_by_user_id' => Auth::id(),
                ]
            );

            $entity = $this->modelClass::create($data);

            $message = $status === 'pending_approval'
                ? "{$this->entityName} created and submitted for approval successfully."
                : "{$this->entityName} created as draft successfully.";

            if ($request->expectsJson()) {
                return $entity->load($this->getDefaultRelationships());
            }

            return $this->redirectToIndex("{$this->routePrefix}.index", $message);
        }, $request);
    }

    /**
     * Handle entity update with workflow.
     */
    protected function updateEntity(Request $request, Model $entity, array $validationRules, array $additionalData = []): JsonResponse|RedirectResponse
    {
        return $this->handleAction(function () use ($request, $entity, $validationRules, $additionalData) {
            if (!$this->canEdit($entity)) {
                throw new \Exception('You do not have permission to edit this record.');
            }

            $request->validate($validationRules, ValidationService::getCustomMessages());

            $updateData = array_merge(
                $request->except(['submit_action', '_token']),
                $additionalData
            );

            // Handle status change if submit_action is provided
            if ($request->has('submit_action')) {
                if ($request->submit_action === 'submit_for_approval' && $entity->status === 'draft') {
                    $updateData['status'] = 'pending_approval';
                }
            }

            $entity->update($updateData);

            $message = isset($updateData['status']) && $updateData['status'] === 'pending_approval'
                ? "{$this->entityName} updated and submitted for approval successfully."
                : "{$this->entityName} updated successfully.";

            if ($request->expectsJson()) {
                return $entity->fresh($this->getDefaultRelationships());
            }

            return $this->redirectToIndex("{$this->routePrefix}.index", $message);
        }, $request);
    }

    /**
     * Handle entity deletion.
     */
    protected function deleteEntity(Request $request, Model $entity): JsonResponse|RedirectResponse
    {
        return $this->handleAction(function () use ($request, $entity) {
            if (!$this->canDelete($entity)) {
                throw new \Exception('You do not have permission to delete this record.');
            }

            $entityName = $this->getEntityDisplayName($entity);
            $entity->delete();

            $message = "{$this->entityName} '{$entityName}' has been deleted successfully.";

            if ($request->expectsJson()) {
                return null;
            }

            return $this->redirectToIndex("{$this->routePrefix}.index", $message);
        }, $request);
    }

    /**
     * Handle entity approval.
     */
    protected function approveEntity(Request $request, Model $entity): JsonResponse|RedirectResponse
    {
        return $this->handleAction(function () use ($request, $entity) {
            if (!$this->canApprove($entity)) {
                throw new \Exception('You do not have permission to approve this record.');
            }

            if ($entity->status !== 'pending_approval') {
                throw new \Exception("{$this->entityName} is not pending approval.");
            }

            // Special handling for User model to ensure proper approval
            if ($entity instanceof User) {
                $entity->approveAccount(Auth::user(), $request->notes ?? null);
            } else {
                $entity->update([
                    'status' => 'active',
                    'approved_by_user_id' => Auth::id(),
                ]);
            }

            $entityName = $this->getEntityDisplayName($entity);
            $message = "{$this->entityName} '{$entityName}' has been approved and is now active.";

            if ($request->expectsJson()) {
                return $entity->fresh($this->getDefaultRelationships());
            }

            return redirect()->back()->with('success', $message);
        }, $request);
    }

    /**
     * Handle entity rejection.
     */
    protected function rejectEntity(Request $request, Model $entity): JsonResponse|RedirectResponse
    {
        return $this->handleAction(function () use ($request, $entity) {
            if (!$this->canApprove($entity)) {
                throw new \Exception('You do not have permission to reject this record.');
            }

            if ($entity->status !== 'pending_approval') {
                throw new \Exception("{$this->entityName} is not pending approval.");
            }

            $entity->update([
                'status' => 'rejected',
                'approved_by_user_id' => Auth::id(),
            ]);

            $entityName = $this->getEntityDisplayName($entity);
            $message = "{$this->entityName} '{$entityName}' has been rejected.";

            if ($request->expectsJson()) {
                return $entity->fresh($this->getDefaultRelationships());
            }

            return redirect()->back()->with('success', $message);
        }, $request);
    }

    /**
     * Handle bulk actions.
     */
    protected function handleBulkAction(Request $request, string $entityType): JsonResponse|RedirectResponse
    {
        return $this->handleAction(function () use ($request, $entityType) {
            $request->validate(ValidationService::getBulkActionRules($entityType));

            $entities = $this->modelClass::whereIn('id', $request->entity_ids)->get();
            $action = $request->action;
            $count = 0;

            DB::transaction(function () use ($entities, $action, &$count) {
                foreach ($entities as $entity) {
                    if ($this->processBulkAction($entity, $action)) {
                        $count++;
                    }
                }
            });

            $actionText = $this->getBulkActionText($action);
            $message = "{$count} {$this->entityName}(s) have been {$actionText} successfully.";

            if ($request->expectsJson()) {
                return ['processed' => $count];
            }

            return redirect()->back()->with('success', $message);
        }, $request);
    }

    /**
     * Process individual bulk action.
     */
    protected function processBulkAction(Model $entity, string $action): bool
    {
        switch ($action) {
            case 'approve':
                if ($entity->status === 'pending_approval' && $this->canApprove($entity)) {
                    if ($entity instanceof \App\Models\User) {
                        $entity->approveAccount(Auth::user());
                    } else {
                        $entity->update([
                            'status' => 'active',
                            'approved_by_user_id' => Auth::id()
                        ]);
                    }
                    return true;
                }
                break;
            case 'reject':
                if ($entity->status === 'pending_approval' && $this->canApprove($entity)) {
                    if ($entity instanceof \App\Models\User) {
                        $entity->rejectAccount(Auth::user());
                    } else {
                        $entity->update([
                            'status' => 'rejected',
                            'approved_by_user_id' => Auth::id()
                        ]);
                    }
                    return true;
                }
                break;
            case 'delete':
                if ($this->canDelete($entity)) {
                    $entity->delete();
                    return true;
                }
                break;
            case 'activate':
                if ($entity->status === 'inactive' && Auth::user()->isAdmin()) {
                    $entity->update(['status' => 'active']);
                    return true;
                }
                break;
            case 'deactivate':
                if ($entity->status === 'active' && Auth::user()->isAdmin()) {
                    $entity->update(['status' => 'inactive']);
                    return true;
                }
                break;
        }
        return false;
    }

    /**
     * Get bulk action text.
     */
    protected function getBulkActionText(string $action): string
    {
        return match ($action) {
            'approve' => 'approved',
            'reject' => 'rejected',
            'delete' => 'deleted',
            'activate' => 'activated',
            'deactivate' => 'deactivated',
            default => $action,
        };
    }

    /**
     * Get default relationships to load.
     */
    protected function getDefaultRelationships(): array
    {
        return ['creator', 'approvedBy'];
    }

    /**
     * Get entity display name for messages.
     */
    protected function getEntityDisplayName(Model $entity): string
    {
        return $entity->name ?? $entity->title ?? $entity->id;
    }
}
