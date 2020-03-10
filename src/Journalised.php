<?php


namespace Codewiser\Journalism;

use Illuminate\Database\Eloquent\Collection;
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
    private $memo;
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
        if ($user = Auth::user()) {
            $journal->user = $user->toArray();
        }
        $journal->event = $event;
        $journal->payload = $payload;
        $journal->memo = $this->memo;
        $journal->save();

        $this->memo = null;

        return $journal;
    }

    /**
     * Adding textual memo to the next journal record
     * @param string $memo
     */
    public function journalMemo(string $memo)
    {
        $this->memo = $memo;
    }
}