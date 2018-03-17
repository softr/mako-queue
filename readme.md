# Mako Queue Manager

This is a simple queue manager package for Mako Framework 4.1.

## Install

Use composer to install. Simply add package to your project.

```php
composer require softr/mako-queue:0.9.*
```

So now you can update your project with a single command.

```php
composer update
```


### Register Service

After installing you'll have to register the package in your ``app/config/application.php`` file.

```
'services' =>
[
    ...
    'softr\MakoQueue\MakoQueueService',
],
```
