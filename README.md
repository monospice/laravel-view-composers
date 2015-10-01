Laravel View Composers
======================

[![Build Status](https://travis-ci.org/monospice/laravel-view-composers.svg?branch=master)](https://travis-ci.org/monospice/laravel-view-composers)

**An intuitive abstraction for organizing Laravel View Composers and View
Creators.**

View Composers in Laravel improve application structure by consolidating the
controller-independent data-binding logic for a view.

This package provides a readable boilerplate framework to easily define the
View Composer and View Creator bindings in your Laravel application.

Compatible with Laravel 4 and 5+. For more information about View Composers and
View Creators, see the [Laravel Documentation][view-composer-docs].

Simple Example
--------------

In the following example, the application will use `MyViewComposer` to compose
`myview`, `UserComposer` to compose the `user.profile` and `user.image` views,
and both `UserComposer` and `FavoritesComposer` to compose the `user.favorites`
view.

```php
class ViewComposerServiceProvider extends ViewBinderServiceProvider
{
    protected function bindViews()
    {
        $this->compose('myview')->with('MyViewComposer');
    }

    protected function bindUserViews()
    {
        $this->setPrefix('user')
            ->compose('profile', 'image')->with('UserComposer')
            ->compose('favorites')->with('UserComposer', 'FavoritesComposer');
    }

    // ...and so on!
}
```

Installation
-------

Simply install via composer:

```bash
$ composer require monospice/laravel-view-composers
```

### Create the Service Provider

The Service Provider takes care of the view binding work when the application
boots. Simply extend the Service Provider in this package:

```php
use Monospice\LaravelViewComposers\ViewBinderServiceProvider;

class ViewComposerServiceProvider extends ViewBinderServiceProvider
{
    // View Composer and View Creator bindings will go here
}
```

And don't forget to add the new Service Provider to `app.config`:

```php
...
    // Laravel >= 5.1:
    App\Providers\ViewComposerServiceProvider::class,
    // Laravel < 5.1:
    'App\Providers\ViewComposerServiceProvider',
...
```

No need to declare the `register()` or `boot()` methods. The package's
service provider takes care of this.

Binding Views
-------------

Define View Composer and View Creator bindings in the Service Provider you
created during installation.

Definitions must be placed inside a method that begins with "bind" and ends
with "Views", such as `bindViews()` or `bindAnythingGoesHereViews()`. This
convention encourages readable groups of related view bindings:

```php
class ViewComposerServiceProvider extends ViewBinderServiceProvider
{
    protected function bindCommentViews()
    {
        // all comment-related view bindings go here
    }
}
```

### Namespaces

To make these definitions more concise, use the `setNamespace()` method to
declare the namespace to use for the following View Composer or View Creator
classes.

```php
...
    protected function bindCommentViews()
    {
        // The hard way
        $this->compose('view')->with('App\Http\ViewComposers\CommentComposer');

        // or just:
        $this->setNamespace('App\Http\ViewComposers')
            ->compose('view2')->with('CommentComposer')
            ->compose('view3')->with('AnotherComposer');
    }
...
```

In the example above, the Service Provider applies the `App\Http\ViewComposers`
namespace to both the `CommentComposer` and the `AnotherComposer` classes.

One may change the namespace at any time by calling `setNamespace()` again.
Any namespaces are automatically cleared at the end of each `bindViews()`
method.

### View Prefixes

Similar to namespaces above, one may set the namespace-like prefix of the bound
views by calling `setPrefix()` for more concise code:

```php
...
    protected function bindNavbarViews()
    {
        // The hard way
        $this->compose('partials.navbar.info.user')->with('NavbarComposer');

        // or just:
        $this->setPrefix('partials.navbar.info')
            ->compose('user', 'company')->with('NavbarComposer');
    }
...
```

As demonstrated, the application binds the `partials.navbar.info.user` and
`partials.navbar.info.company` views to the `NavbarComposer`.

One may change the prefix at any time by calling `setPrefix()` again. Any
prefixes are automatically cleared at the end of each `bindViews()` method.

### View Composers

Use the `compose()` method to specify the views that the application should
bind to a particular View Composer, and `with()` to specify which View Composer
to use. The View Composer specified in `with()` may be a class name or an
anonymous function, as described in the [Laravel Docs][view-composer-docs]:

```php
...
    protected function bindProductViews()
    {
        $this->setNamespace('App\Http\ViewComposers')->setPrefix('product');

        $this
            ->compose('index', 'search')->with('ProductComposer')
            ->compose('show')->with(function ($view) {
                // view composer logic here
            });
    }
...
```

### View Creators

Similar to View Composers, use the `create()` method to specify the views that
the application should bind to a particular View Creator.

```php
...
    protected function bindStudentViews()
    {
        $this->setNamespace('App\Http\ViewCreators')->setPrefix('dashboard');

        $this
            ->create('student', 'teacher')->with('DashboardCreator')
            ->create('feed')->with(function ($view) {
                // view creater logic here
            });
    }
...
```

Testing
-------

The Laravel View Composers package uses PHPSpec to test object behavior:

``` bash
$ vendor/bin/phpspec run
```

License
-------

The MIT License (MIT). Please see the [LICENSE File](LICENSE) for more
information.

[view-composer-docs]: http://laravel.com/docs/5.1/views#view-composers
