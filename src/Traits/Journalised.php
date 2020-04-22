<?php


namespace Codewiser\Journalism\Traits;

use Codewiser\Journalism\Journal;
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
        static::created(function (Journalised $model) {
            $model->journalise('created', $model->getDirty());
        });
        static::updated(function (Journalised $model) {
            $model->journalise('updated', $model->getDirty());
        });
        static::deleted(function (Journalised $model) {
            $model->journalise('deleted', $model->getDirty());
        });
        static::restored(function (Journalised $model) {
            $model->journalise('restored', $model->getDirty());
        });
        static::forceDeleted(function (Journalised $model) {
            $model->journalise('forceDeleted', $model->getDirty());
        });
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
     * @return Journal
     */
    public function journalise($event, $payload = null)
    {
        return Journal::record($event, $this, $payload);
    }
}