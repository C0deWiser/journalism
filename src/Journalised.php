<?php


namespace Codewiser\Journalism;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

/**
 * Trait Journalised
 * @package Codewiser\Journalism
 * 
 * @property Journal[]|Collection $journal
 */
trait Journalised
{
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
        $journal = new Journal();
        $journal->object()->associate($this);
        /** @var Model $user */
        if ($user = Auth::user()) {
            $journal->user = $user->toArray();
        }
        $journal->event = $event;
        $journal->payload = $payload;
        $journal->save();

        return $journal;
    }
}