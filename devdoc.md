## How to write crud with DDD patterns 

I want to explain to you a explain with User Crud Example

### Step 1

Go to that file `src/BlendedConcept/Security/Presentation/HTTP/routes.php` and write your own **Route** as below as an example.

```
    Route::resource('users', UserController::class);
```

And then create a `UserController.php` and write your functionality on that.

