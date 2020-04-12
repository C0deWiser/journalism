<?php


namespace Codewiser\Journalism\Subscribers;


use Codewiser\Journalism\Journal;
use Illuminate\Auth\Events;

/**
 * Watch Auth events
 * @package Codewiser\Journalism\Subscribers
 */
class Concierge
{
    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     */
    public function subscribe($events)
    {
        $events->listen(
            Events\Registered::class,
            static::class.'@onUserRegister'
        );

        $events->listen(
            Events\Attempting::class,
            static::class.'@onUserAttempts'
        );

        $events->listen(
            Events\Authenticated::class,
            static::class.'@onUserAuthenticates'
        );

        $events->listen(
            Events\Login::class,
            static::class.'@onUserLogin'
        );

        $events->listen(
            Events\Failed::class,
            static::class.'@onUserFails'
        );

        $events->listen(
            Events\Logout::class,
            static::class.'@onUserLogout'
        );

        $events->listen(
            Events\Lockout::class,
            static::class.'@onUserLockedOut'
        );

        $events->listen(
            Events\PasswordReset::class,
            static::class.'@onUserResetsPassword'
        );
    }

    /**
     * @param Events\PasswordReset $event
     */
    public function onUserResetsPassword($event)
    {
        $this->recordAuthEvent('auth/reset-password', $event->user, null);
    }

    /**
     * @param Events\Lockout $event
     */
    public function onUserLockedOut($event)
    {
//        $this->recordAuthEvent('auth/lockout', $event->user, null);
    }

    /**
     * @param Events\Failed $event
     */
    public function onUserFails($event)
    {
        $this->recordAuthEvent('auth/fail', $event->user, $event->credentials);
    }

    /**
     * @param Events\Authenticated $event
     */
    public function onUserAuthenticates($event)
    {
//        $this->recordAuthEvent('auth/authentication', $event->user, null);
    }

    /**
     * @param Events\Attempting $event
     */
    public function onUserAttempts($event)
    {
        //$this->recordAuthEvent('auth/attempt', $event->user, $event->credentials);
    }

    /**
     * @param Events\Registered $event
     */
    public function onUserRegister($event)
    {
        $this->recordAuthEvent('auth/register', $event->user, null);
    }

    /**
     * @param Events\Logout $event
     */
    public function onUserLogout($event)
    {
        $this->recordAuthEvent('auth/logout', $event->user, null);
    }

    /**
     * @param Events\Login $event
     */
    public function onUserLogin($event) {
        $this->recordAuthEvent('auth/login', $event->user, ['remember' => $event->remember]);
    }

    private function recordAuthEvent($event, $user, $payload)
    {
        Journal::record($event, $user, $payload);
        return true;
    }
}
