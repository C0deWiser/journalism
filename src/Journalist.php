<?php
namespace Codewiser\Journalism;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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