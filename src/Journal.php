<?php

namespace Codewiser\Journalism;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/**
 * Class Journal
 * @package Codewiser\Journalism
 *
 * @property integer $id
 * @property Carbon $created_at
 * @property string $event
 * @property array $user
 * @property Model $object
 * @property null|string|array $payload
 * @method static static|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder onlyObject(Model $model)
 * @method static static|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder onlyEvent(string|array $event)
 */
class Journal extends Model
{
    protected $table = 'journal';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('latest', function (Builder $builder) {
            $builder->latest();
        });
    }

    public function getPayloadAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setPayloadAttribute($value)
    {
        $this->attributes['payload'] = json_encode($value);
    }

    public function getUserAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setUserAttribute($value)
    {
        $this->attributes['user'] = json_encode($value);
    }

    /**
     * Add journal record
     * @param string $event
     * @param Model $model
     * @param mixed $payload
     * @return static|null
     */
    public static function record($event, Model $model, $payload)
    {
        // Pivots may has ho primary key

        if ($model->getKey()) {
            $journal = new static();
            $journal->object()->associate($model);
            /** @var Model $user */
            if ($user = Auth::user()) {
                $journal->user = $user->toArray();
            }
            $journal->event = $event;
            $journal->payload = $payload;
            $journal->save();

            return $journal;
        } else {
            return null;
        }
    }

    public function object()
    {
        return $this->morphTo();
    }

    public function scopeOnlyObject(Builder $query, Model $model)
    {
        $query
            ->where('object_type', get_class($model))
            ->where('object_id', $model->getKey());
    }

    /**
     * @param Builder $query
     * @param string|array $event
     */
    public function scopeOnlyEvent(Builder $query, $event)
    {
        if (!is_array($event)) {
            $event = explode(' ', $event);
        }
        $query->whereIn('event', $event);
    }
}