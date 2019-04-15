[![Build Status](https://travis-ci.org/Kooser6/Session.svg?branch=master)](https://travis-ci.org/Kooser6/Session)
[![Latest Stable Version](https://poser.pugx.org/kooser/session/v/stable)](https://packagist.org/packages/kooser/session)
[![Latest Unstable Version](https://poser.pugx.org/kooser/session/v/unstable)](https://packagist.org/packages/kooser/session)
[![License](https://poser.pugx.org/kooser/session/license)](https://packagist.org/packages/kooser/session)
[![Downloads](https://img.shields.io/packagist/dt/kooser/session.svg)](https://packagist.org/packages/kooser/session)
[![Coverage Status](https://coveralls.io/repos/github/Kooser6/Session/badge.svg?branch=master)](https://coveralls.io/github/Kooser6/Session?branch=master)

# Session

Securely manage and preserve session data.

## Requirements

The only thing we require is PHP 7.3 and up.

## Installation

via Composer:

The best way to install this session library is through composer. If you do not have composer installed you can install it directly from thier website (https://getcomposer.org/). After composer is successfully install run the command line code below.

```sh
composer require kooser/session
```

## Usage

### Basic usage

Our session api is easy to use static methods that replace the native session function. You ultimately control the session using the `SessionManager` class. Here is a small example of starting a secure session with same-site cookies. Also the session config is the session runtime config which can be found here (https://www.php.net/manual/en/session.configuration.php). You can use them in the session config array just remove the `session.` prefix.

```php
<?php

use Kooser\Session\SessionManager;

// Require the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Define our session config.
$sessionConfig = [
    "use_cookies"      => \true,
    "use_only_cookies" => \true,
    "cookie_samesite"  => "Lax",
];

// Start the session.
SessionManager::start($sessionConfig);

// Check to see if we are active.
var_dump(SessionManager::exists());

```

### Regeneration

Regenerating the session id is important when dealing with different access levels.

* Regenerate the session id. <br />
`Kooser\Session\SessionManager::regenerate(bool deleteOldSession): bool`

### Garbage Collection

You can preform the session garbage collection manually using the simple api.

* Preform the session garbage collection. <br />
`Kooser\Session\SessionManager::gc(): void`

### Session Handlers

This session library comes with its own custom session handlers which can be implemented and used with complete ease. We have a lot of session handlers to choose from here is an example of using the filesystem handler. Also when using the mysql session handler it will create the table for you, all you have to do is set the name of the table.

```php
<?php

use Kooser\Session\SessionManager;

// Require the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Define our session config.
$sessionConfig = [
    "use_cookies"      => \true,
    "use_only_cookies" => \true,
    "cookie_samesite"  => "Lax",
];

// Set the path where the session files will be stored.
SessionManager::setSavePath(__DIR__ . '/sessions');

// Construct the handler.
$handler = new Kooser\Session\Handler\FileSessionHandler();

// Set the filesystem session handler.
SessionManager::setSaveHandler($handler);

// Start the session.
SessionManager::start($sessionConfig);

// Check to see if we are active.
var_dump(SessionManager::exists());

```

If you rather store your sessions in a mysql database that is easy too. Here is an example of that below.


```php
<?php

use Kooser\Session\SessionManager;

// Require the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Define our session config.
$sessionConfig = [
    "use_cookies"      => \true,
    "use_only_cookies" => \true,
    "cookie_samesite"  => "Lax",
];

// Construct the handler.
$handler = new Kooser\Session\Handler\MySqlSessionHandler(
    'mysql:host=localhost;dbname=test',
    'db_username',
    'db_password',
    'tablename'
); 

// Set the mysql session handler.
SessionManager::setSaveHandler($handler);

// Start the session.
SessionManager::start($sessionConfig);

// Check to see if we are active.
var_dump(SessionManager::exists());

```

### Session Variables

Session variables are the key reason why we use sessions. We provide an easy to use session api to make variable management as easy as possible. The has method check to see if the variable exists, the set method is self-explanatory, the get method is self-explanatory, the flash method is the same as the get method except it deletes the variable after use, and the delete method is self-explanatory.

* Checking if a session variable exists. <br />
`Kooser\Session\SessionManager::has(string variableName): bool`

* Setting a session variable. <br />
`Kooser\Session\SessionManager::set(string variableName, mixed variableValue): void`

* Getting a session variable. <br />
`Kooser\Session\SessionManager::get(string variableName, mixed defaultReturnValue): mixed`

* Flashing a session variable. <br />
`Kooser\Session\SessionManager::flash(string variableName, mixed defaultReturnValue): mixed`

* Deleting a session variable. <br />
`Kooser\Session\SessionManager::delete(string variableName): void`

We recommend you use the api over the regular `$_SESSION` array.

## Contributing

All contributions are welcome! If you wish to contribute, create an issue first so that your feature, problem or question can be discussed.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
