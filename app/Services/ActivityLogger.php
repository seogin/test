<?php

namespace App\Services;

use App\Models\ActionLog;
use App\Models\AuthLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    protected function getActor()
    {
        $actor = Auth::user();
        return [
            'id' => $actor?->id,
            'type' => $actor ? get_class($actor) : 'Unknown',
        ];
    }

    public function logCreate($model, array $data = [])
    {
        $actor = $this->getActor();

        return ActionLog::create([
            'actor_id' => $actor['id'],
            'actor_type' => $actor['type'],
            'action' => 'create',
            'target_id' => $model->id,
            'target_type' => get_class($model),
            'after' => $model->toArray(),
            'data' => $data,
        ]);
    }

    public function logUpdate($model, array $data = [])
    {
        $actor = $this->getActor();
        $before = $model->getOriginal();
        $after = $model->getDirty();

        if (empty($after)) return null;

        return ActionLog::create([
            'actor_id' => $actor['id'],
            'actor_type' => $actor['type'],
            'action' => 'update',
            'target_id' => $model->id,
            'target_type' => get_class($model),
            'before' => array_intersect_key($before, $after),
            'after' => $after,
            'data' => $data,
        ]);
    }

    public function logDelete($model, array $data = [])
    {
        $actor = $this->getActor();

        return ActionLog::create([
            'actor_id' => $actor['id'],
            'actor_type' => $actor['type'],
            'action' => 'delete',
            'target_id' => $model->id,
            'target_type' => get_class($model),
            'before' => $model->toArray(),
            'data' => $data,
        ]);
    }

    public function logPermissionChange($model, array $before, array $after, array $data = [])
    {
        $actor = $this->getActor();

        return ActionLog::create([
            'actor_id' => $actor['id'],
            'actor_type' => $actor['type'],
            'action' => 'permission_update',
            'target_id' => $model->id,
            'target_type' => get_class($model),
            'before' => $before,
            'after' => $after,
            'data' => $data,
        ]);
    }

    public function logUnauthorizedAttempt($model, string $action, array $attempted, array $data = [])
    {
        $actor = $this->getActor();

        return ActionLog::create([
            'actor_id' => $actor['id'],
            'actor_type' => $actor['type'],
            'action' => "failed_{$action}",
            'target_id' => $model->id,
            'target_type' => get_class($model),
            'after' => $attempted,
            'data' => $data,
        ]);
    }

    public function logAuthEvent(string $action, $actor = null, array $data= [])
{
    if ($actor) {
        $actorData = ['id' => $actor->id, 'type' => get_class($actor)];
    } elseif (Auth::check()) {
        $actorData = ['id' => Auth::id(), 'type' => get_class(Auth::user())];
    } else {
        $actorData = ['id' => null, 'type' => 'Unknown'];
    }

    return AuthLog::create([
        'actor_id'   => $actorData['id'],
        'actor_type' => $actorData['type'],
        'action'     => $action,
        'ip'         => request()->ip(),
        'data'      => $data,
    ]);
}

// Log admin reactivation
public function logReactivation($admin, array $data = [])
{
    $actor = $this->getActor();

    return ActionLog::create([
        'actor_id' => $actor['id'],
        'actor_type' => $actor['type'],
        'action' => 'reactivate',
        'target_id' => $admin->id,
        'target_type' => get_class($admin),
        'after' => $admin->toArray(),
        'data' => $data,
    ]);
}
}
