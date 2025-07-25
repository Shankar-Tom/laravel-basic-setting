# Laravel Basic Setting

A collection of useful traits, middleware, and rules to speed up Laravel development. This package provides helpers for API responses, searching Eloquent models (including relationships), safe Livewire calls, transaction-handling middleware, and a custom phone validation rule.

## Features

- **ApiResponse Trait**: Standardized JSON responses for success, error, validation errors, and internal server errors.
- **CanSearch Trait**: Easily add search and relation-search scopes to your Eloquent models.
- **LivewireSafeCallTrait**: Wrap Livewire actions in database transactions with error reporting.
- **HandleWithTransaction Middleware**: Automatically wraps HTTP requests in a DB transaction and handles exceptions gracefully.
- **ValidPhone Rule**: Validate phone numbers with a customizable regex.

## Installation

1. Install via Composer:
    ```bash
    composer require shankar/laravel-basic-setting
    ```
2. (Optional) Publish the middleware to your app:
    ```bash
    php artisan vendor:publish --tag=middleware
    ```

## Usage

### ApiResponse Trait
Use in your controllers to standardize API responses:
```php
use Shankar\LaravelBasicSetting\Traits\ApiResponse;

class ExampleController extends Controller {
    use ApiResponse;
    // ...
}
```

### CanSearch Trait
Add to your Eloquent models for search functionality:
```php
use Shankar\LaravelBasicSetting\Traits\CanSearch;

class Post extends Model {
    use CanSearch;
}

// Usage:
Post::search('keyword')->get();
Post::searchRelation('keyword', ['user' => ['name', 'email']])->get();
```

### LivewireSafeCallTrait
Use in Livewire components to safely wrap actions in transactions:
```php
use Shankar\LaravelBasicSetting\Traits\LivewireSafeCallTrait;

class ExampleComponent extends Component {
    use LivewireSafeCallTrait;
    // ...

    public function exampleAction()
    {
        $this->safeCall(function () {
            // Your code here
        });
    }
}
```

### HandleWithTransaction Middleware
Publish and register the middleware to wrap requests in DB transactions (see Installation step 2).

### ValidPhone Rule
Use in your form requests for phone validation:
```php
use Shankar\LaravelBasicSetting\Rules\ValidPhone;

$request->validate([
    'phone' => ['required', new ValidPhone],
]);
```

## License

MIT
