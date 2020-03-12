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

## Usage

Add `Journal` to you Model.

```php
class Post extends Model {
    use Codewiser\Journalism\Journalised;
}
```

Now you can journal any events you want.

```php
$post = Post::first();

// Record an event
$post->journalise('my-event');
```

You may add to the journal record any payload you want.
```php
$post->journalise('my-event', /* jsonable data */);
```

### Observer

This package is very useful to record Eloquent events.

Lets apply an Observer to the Model.

```php
class AppServiceProvider extends ServiceProvider 
{
    public function boot()
    {
        Post::observe(Codewiser\Journalism\Journalist::class);
    }
}
```

> Observer detects `created`, `updated`, `deleted`, `restored` and `forceDeleted` events.
> You may extend the observer or write out yourself, it's simple.

For now it will record some Eloquent events automatically. 
The payload of event will contain the Model changes (`$post->getDirty()`).


So you will have full history of object changes.

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