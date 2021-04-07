# EloquentFilter

Add simple sortability to Eloquent models.

## Installation

Install the EloquentSort package via Composer:

```
composer require thecodemill/eloquent-sort
```

## Usage

As the name suggests, this package is intended for use with [Eloquent](https://github.com/illuminate/database) and therefor works seamlessly with Laravel.

EloquentSort allows you to define sort handlers on any of your app's models to make sorting model queries much simpler. A sort handler is very similar to a local scope, but by using the `Model::sort()` method, any number of scopes may be applied at once without the need for chaining the individual scopes or query modifiers.

## Author

* [Andrew Robinson](https://twitter.com/ap_robinson)
