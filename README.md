# FilterIt Laravel
This is the Laravel backend implementation for the [filterit-ts ↗](https://github.com/FilterItTool/filterit-ts) library,
which provides a query builder for TypeScript and JavaScript.

## Installation

`composer require filterit/filterit-laravel`

## Usage

To use the FilterIt, you first need to add the FilterIt trait to your Laravel model:

```php
use Filter
class User extends Authenticatable
{
    use FilterIt;
    ...
}

```

The FilterIt trait provides a FilterIt method that can be used to apply filters and sorts to your model. You can then
use this method in your controller to retrieve filtered results:

```php
class UserController extends BaseController
{
    public function index(Request $request)
    {
        return User::FilterIt($request->query())->get();
    }
}
```

## What is FilterIt?
Please refer to [About](https://github.com/FlterItTool/FlterItTool) for more information.

## License

The filterit-laravel is open-sourced software licensed under the MIT license.
