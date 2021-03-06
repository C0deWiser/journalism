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

### Migrations

Publish the package migrations to your application. Run these commands inside your terminal.

    php artisan vendor:publish --provider="Codewiser\Journalism\JournalServiceProvider"

And also run migrations.

    php artisan migrate

## Using as Trait

Add `Journal` to you Model.

```php
class Post extends Model {
    use Codewiser\Journalism\Traits\Journalised;
}
```

For now every Eloquent event will be journalized.

And you can journal any events you want.

```php
$post = Post::first();

// Record an event
$post->journalise('my-event');
```

You may add to the journal record any payload you want.
```php
$post->journalise('my-event', /* jsonable data */);
```

### Or using as Observer

```php
class AppServiceProvider extends ServiceProvider 
{
    public function boot()
    {
        Post::observe(Codewiser\Journalism\Observers\Journalist::class);
    }
}
```

### Auth Subscriber

Package provides mechanism to records auth events.

```php
class EventServiceProvider extends ServiceProvider
{
    protected $subscribe = [
        Codewiser\Journalism\Subscribers\Concierge::class,
    ];
}
```

> Subscriber detects `auth/register`, `auth/login`, `auth/logout`, `auth/reset-password` and `auth/fail` events.

### Custom journal records

Lets imagine, every time user wants to update the Post, he must explain, why changes were made.

```php
class Controller
{
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        $post->journalise('comment', $request->get('comment'));
        $post->update($request->all());
    }
}
```

### Accessing history

You have access to full history with user explanations:

```php
foreach ($post->journal as $record) {
    echo "At {$record->created_at} 
          user {$record->user['name']} 
          makes {$record->event}\n";
    
    echo "Payload was: " . json_encode($record->payload);
}
```