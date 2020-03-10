<?php

namespace Codewiser\Journalism;

use Carbon\Carbon;
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
 * @property string $memo
 * @method ofObject(Model $model)
 * @method event($event)
 */
class Journal extends Model
{
    protected $table = 'journal';

    protected $fillable = ['event', 'payload', 'memo'];

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

    public function object()
    {
        return $this->morphTo();
    }

    public function scopeOfObject(Builder $query, Model $model)
    {
        $query
            ->where('object_type', get_class($model))
            ->where('object_id', $model->getKey())
            ->orderByDesc('created_at');
        return $query;
    }

    /**
     * @param Builder $query
     * @param null|string|array $event
     * @return Builder
     */
    public function scopeEvent(Builder $query, $event = null)
    {
        if ($event) {
            if (!is_array($event)) {
                $event = explode(' ', $event);
            }
            $query->whereIn('event', $event);
        }
        $query->orderByDesc('created_at');
        return $query;
    }
}