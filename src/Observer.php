<?php


namespace Codewiser\Journalism;


use Illuminate\Database\Eloquent\Model;

class Observer
{
    public function created(Model $model)
    {
        return Journal::log('created', $model, json_encode($model->getDirty()));
    }

    public function updated(Model $model)
    {
        return Journal::log('updated', $model, json_encode($model->getDirty()));
    }

    public function deleted(Model $model)
    {
        return Journal::log('deleted', $model);
    }

    public function restored(Model $model)
    {
        return Journal::log('restored', $model);
    }
}