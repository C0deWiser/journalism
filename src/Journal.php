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
 * @property User $user
 * @property Model $object
 * @property string $payload
 */
class Journal extends Model
{

    protected $table = 'journal';

    /**
     * Log model event
     * @param string $event
     * @param Model $model
     * @param string|null $payload
     * @return Journal
     */
    public static function log(string $event, Model $model, string $payload = null): Journal
    {
        $journal = new static();
        $journal->event = $event;
        if ($user = Auth::user()) {
            $journal->user()->associate($user);
        }
        $journal->object()->associate($model);
        $journal->payload = $payload;

        $journal->save();

        return $journal;
    }

    /**
     * Retrieve model events
     * @param Model $model
     * @return Builder[]|Collection
     */
    public static function get(Model $model)
    {
        return static::query()
            ->where('object_type', get_class($model))
            ->where('object_id', $model->getKey())
            ->get();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function object()
    {
        return $this->morphTo();
    }
}