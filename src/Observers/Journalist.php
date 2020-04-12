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
        return $this->recordEloquentEvent('created', $model);
    }

    public function updated(Model $model)
    {
        return $this->recordEloquentEvent('updated', $model);
    }

    public function deleted(Model $model)
    {
        return $this->recordEloquentEvent('deleted', $model);
    }

    public function restored(Model $model)
    {
        return $this->recordEloquentEvent('restored', $model);
    }

    public function forceDeleted(Model $model)
    {
        return $this->recordEloquentEvent('forceDeleted', $model);
    }

    private function recordEloquentEvent($event, Model $model)
    {
        Journal::record($event, $model, $model->getDirty());
        return true;
    }
}