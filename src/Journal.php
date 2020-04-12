<?php

namespace Codewiser\Journalism;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;

/**
 * Class Journal
 * @package Codewiser\Journalism
 *
 * @property integer $id
 * @property Carbon $created_at
 * @property string $event
 * @property string $email (helps identify user)
 * @property array $user
 * @property Model $object
 * @property mixed $payload
 * @method static onlyObject(Model $model)
 * @method static onlyEvent(string|array $event)
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
     * @return static
     */
    public static function record($event, Model $model, $payload)
    {
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