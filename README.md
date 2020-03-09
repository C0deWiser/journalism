# Journalism
Record any (model or custom) events to store and review history

### Service Provider

Add the package to your application service providers in `config/app.php` file.

```php
'providers' => [
    
    /*
     * Laravel Framework Service Providers...
     */
    Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
    Illuminate\Auth\AuthServiceProvider::class,
    ...
    
    /**
     * Third Party Service Providers...
     */
    Codewiser\Journalism\JournalServiceProvider::class,

],
```

### Config File And Migrations

Publish the package migrations to your application. Run these commands inside your terminal.

    php artisan vendor:publish --provider="Codewiser\Journalism\JournalServiceProvider"

And also run migrations.

    php artisan migrate

> This uses the default users table which is in Laravel. You should already have the migration file for the users table available and migrated.
