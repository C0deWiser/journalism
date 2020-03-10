<?php
namespace Codewiser\Journalism;

use Codewiser\Journalism\Journal;
use Illuminate\Database\Eloquent\Model;

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
        /* @var Journalised|Model $model */
        return $model->journalise($event, $model->getDirty());
    }
}