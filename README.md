[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/thecodingmachine/lazy-array/badges/quality-score.png?b=1.0)](https://scrutinizer-ci.com/g/thecodingmachine/lazy-array/?branch=1.0)
[![Build Status](https://travis-ci.org/thecodingmachine/lazy-array.svg?branch=1.0)](https://travis-ci.org/thecodingmachine/lazy-array)
[![Coverage Status](https://coveralls.io/repos/thecodingmachine/lazy-array/badge.svg?branch=1.0&service=github)](https://coveralls.io/github/thecodingmachine/lazy-array?branch=1.0)


What is it?
===========

This project contains a single class that behaves like an array.
However, objects in this array can be instantiated only when the key in the array is fetched, so you don't have to create the instance right away. This is useful for performance considerations.

How does it work?
=================

Easy, you create a new `LazyArray` object, and then, you push objects in it.

```php
$lazyArray = new LazyArray();

$key = $lazyArray->push(MyServiceProvider::class);

// This will trigger the creation of the MyServiceProvider object and return it.
$serviceProvider = $lazyArray[$key];
```

You can also pass parameters to the constructor of the object:

```php
$lazyArray = new LazyArray();

$key = $lazyArray->push(MyServiceProvider::class, "param1", "param2");
```

And because we are kind, you can also push into the lazy array an already instantiated object:

```php
$lazyArray = new LazyArray();

// This is possible, even if we loose the interest of the LazyArray.
$key = $lazyArray->push(new MyServiceProvider());
```


Finally, if you are performance oriented (and I'm sure you are), you can create the whole lazy array in one call:

```php
$lazyArray = new LazyArray([
    MyServiceProvider::class, // Is you simply want to create an instance without passing parameters
    [ MyServiceProvider2::class, [ "param1", "param2 ] ],  // Is you simply want to create an instance and pass parameters to the constructor
    new MyServiceProvider4('foo') // If you directly want to push the constructed instance.
]);
```

Why?
====

Is the examples suggest, this was build for improving the performance of service providers.
Do not use this as a replacement for a dependency injection container, this is a bad idea.