<?php
namespace Codewiser\Journalism\Observers;

use Codewiser\Journalism\Journal;
use Illuminate\Database\Eloquent\Model;

/**
 * Observe Eloquent events
 * @package Codewiser\Journalism\Observers
 */
class Journalist
{
    public function created(Model $model)
    {
        return $this->recordEloquentEvent('created', $model, $model->toArray());
    }

    public function updated(Model $model)
    {
        return $this->recordEloquentEvent('updated', $model, $model->getDirty());
    }

    public function deleted(Model $model)
    {
        if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
            return $this->recordEloquentEvent('forceDeleted', $model);
        } else {
            return $this->recordEloquentEvent('deleted', $model);
        }
    }

    public function restored(Model $model)
    {
        return $this->recordEloquentEvent('restored', $model);
    }

    private function recordEloquentEvent($event, Model $model, $payload = null)
    {
        Journal::record($event, $model, $payload);
        return true;
    }
}