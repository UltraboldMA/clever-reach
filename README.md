# Laravel-CleverReach

![Laravel CleverReach](img/laravel-clever-reach.jpg)

**Integration for the v3 API**

This package integrates the CleverReach API v3 with Laravel and Livewire.
It contains vendor-namespaced Actions, Models, routes and Livewire components
for handling clients, groups, forms, newsletters and subscribers.

**Laravel 10, 11, 12 and 13 are currently supported.**

Laravel 9 / Livewire 2 support was dropped for the modern Livewire 3/4 stack.

### Docs

-   [Installation](#installation)
-   [Laravel compatibility](#laravel-compatibility)

## Installation

### Install package

Add the package in your composer.json by executing the command.

```bash
composer require ultraboldma/clever-reach
```

Laravel CleverReach features auto discover for Laravel. In case this fails, add the
Service Provider to `config/app.php` on Laravel 10 or `bootstrap/providers.php` on Laravel 11+.

```
UltraboldMA\CleverReach\CleverReachServiceProvider::class,
```

### Publish package files

Routes, views and Livewire components are loaded from the package. Publish only what you
want to customize.

```bash
php artisan clever-reach:publish
```

Individual tags are also available.

```bash
php artisan vendor:publish --tag=clever-reach-config
php artisan vendor:publish --tag=clever-reach-migrations
php artisan vendor:publish --tag=clever-reach-views
```

### Migrations

Publish and run the migrations.

```bash
php artisan migrate
```

### Menu items

There is no menu included with this package. Build your own and link to the package routes.

```php
route('clever-reach')
route('clever-reach-groups')
route('clever-reach-forms')
route('clever-reach-newsletters')
route('clever-reach-subscribers')
```

### Tailwind

All elements in this package are based on Tailwind and come with minimal styling.
If needed you can adjust the published views to fit whatever framework you're using.

## Configuration

### Base URL

Base URL for the CleverReach v3 API. It's already set for you.

```php
'baseUrl' => 'url of api'
```

### Single Client

You have the option to run multiple API clients for interacting
with multiple CleverReach accounts.

```php
'singleClient' => true
```

### Routes

The package registers its routes automatically. Configure prefix and middleware if needed.

```php
'route_prefix' => 'admin',
'route_middleware' => ['web', 'auth'],
```

### Available languages

You can create newsletters with different languages/locales in the DB,
if you wish to load the based on your app's locale. Please list the
available languages here.

```php
'available_languages' => ['de', 'en']
```

### Default group ID

If you always want to use a default group for your newsletter signups,
you can set that here. (not yet available)

```php
'default_group_id' => 39843984
```

### Default form ID

Same as the default group ID. (not yet available)

```php
'default_form_id' => 39839483
```

## Laravel compatibility

| Laravel | Livewire | PHP |
| :------ | :------- | :-- |
| 13.x    | 4.x      | 8.3+ |
| 12.x    | 3.x / 4.x | 8.2+ |
| 11.x    | 3.x / 4.x | 8.2+ |
| 10.x    | 3.x / 4.x | 8.1+ |

Lower versions of Laravel are not supported.
