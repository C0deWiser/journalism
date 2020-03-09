<?php
namespace Codewiser\Journalism;

use Codewiser\Journalism\Journal;
use Illuminate\Database\Eloquent\Model;

class Journalist
{
    public function created(Model $model)
    {
        /* @var Journalised $model */
        return $model->journalise('created', $model->getDirty());
    }

    public function updated(Model $model)
    {
        /* @var Journalised $model */
        return $model->journalise('updated', $model->getDirty());
    }

    public function deleted(Model $model)
    {
        /* @var Journalised $model */
        return $model->journalise('deleted', $model->getDirty());
    }

    public function restored(Model $model)
    {
        /* @var Journalised $model */
        return $model->journalise('restored', $model->getDirty());
    }

    public function forceDeleted(Model $model)
    {
        /* @var Journalised $model */
        return $model->journalise('forceDeleted', $model->getDirty());
    }
}