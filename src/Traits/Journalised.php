<?php


namespace Codewiser\Journalism\Traits;

use Codewiser\Journalism\Journal;
use Codewiser\Journalism\Observers\Journalist;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Access Model Journal
 * @package Codewiser\Journalism
 *
 * @property Journal[]|Collection $journal
 * @mixin Model
 * @mixin SoftDeletes
 */
trait Journalised
{
    public static function bootJournalised()
    {
        static::created(function (Model $model) {
            (new Journalist())->created($model);
        });
        static::updated(function (Model $model) {
            (new Journalist())->updated($model);
        });
        static::deleted(function (Model $model) {
            (new Journalist())->deleted($model);
        });
        if (method_exists(static::class, 'restored')) {
            static::restored(function (Model $model) {
                (new Journalist())->restored($model);
            });
        }
    }

    /**
     * @return MorphMany
     */
    public function journal()
    {
        return $this->morphMany(Journal::class, 'object');
    }

    /**
     * Insert journal record
     * @param string $event
     * @param mixed $payload
     * @return Journal|null
     */
    public function journalise($event, $payload = null)
    {
        return Journal::record($event, $this, $payload);
    }
}