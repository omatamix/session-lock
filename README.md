[![Travis (.org) branch](https://img.shields.io/travis/Kooser6/Session/master.svg?style=flat-square)](https://travis-ci.org/Kooser6/Session)
[![Coveralls github branch](https://img.shields.io/coveralls/github/Kooser6/Session/master.svg?style=flat-square)](https://coveralls.io/github/Kooser6/Session?branch=master)

# Session

Securely manage and preserve session data.

## Installation

via Composer:

The best way to install this php component is through composer. If you do not have composer installed you can install it directly from the [composer website](https://getcomposer.org/). After composer is successfully installed run the command line code below.

```sh
composer require kooser/session
```

## Usage

### Basic usage

Our session API is easy to use static methods that replace the native session function. You ultimately control the session using the `SessionManager` class. Here is a small example of starting a secure session. Also, the session config is the session runtime config which can be found [here](https://www.php.net/manual/en/session.configuration.php). You can use them in the session config array just remove the `session.` prefix.

```php
<?php

use Kooser\Session\SessionManager;

// Require the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Define our session config.
$sessionConfig = [
    "use_cookies"      => \true,
    "use_only_cookies" => \true,
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

### Same-Site Session Cookies

Using same-site session cookies can increase session security. The two supported vaules are `Lax` or `Strict`. Here is an example below.

```php
<?php

use Kooser\Session\SessionManager;

// Require the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Define our session config.
$sessionConfig = [
    "cookie_samesite" => "Lax",
];

// Start the session.
SessionManager::start($sessionConfig);

// Check to see if we are active.
var_dump(SessionManager::exists());

```

### Garbage Collection

You can preform the session garbage collection manually using the simple API.

* Preform the session garbage collection. <br />
`Kooser\Session\SessionManager::gc(): void`

### Session Handlers

This session library comes with its own custom session handlers which can be implemented and used with complete ease. We have a lot of session handlers to choose from here is an example of using the filesystem handler.

```php
<?php

use Kooser\Session\SessionManager;

// Require the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Define our session config.
$sessionConfig = [
    "use_cookies"      => \true,
    "use_only_cookies" => \true,
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

We support other session handlers like `database`, `mongodb`, `wincache`, `memcached`, and `redis`.

### Session Variables

Session variables are the key reason why we use sessions. We provide an easy to use session API to make variable management as easy as possible.

* Checking if a session variable exists. <br />
`Kooser\Session\SessionManager::has(string variableName): bool` <br />

* Setting a session variable. <br />
`Kooser\Session\SessionManager::set(string variableName, mixed variableValue): void` <br />

* Getting a session variable. <br />
`Kooser\Session\SessionManager::get(string variableName, mixed defaultReturnValue): mixed` <br />

* Flashing a session variable. <br />
`Kooser\Session\SessionManager::flash(string variableName, mixed defaultReturnValue): mixed` <br />

* Deleting a session variable. <br />
`Kooser\Session\SessionManager::delete(string variableName): void` <br />

We recommend you use the API over the regular `$_SESSION` array.

### Destroying Sessions

Destroying a session is useful for authentication systems. Here is an example of destroying a session.

```php
<?php

use Kooser\Session\SessionManager;

// Require the composer autoloader.
require_once __DIR__ . '/vendor/autoload.php';

// Define our session config.
$sessionConfig = [
    "use_cookies"      => \true,
    "use_only_cookies" => \true,
];

// Start the session.
SessionManager::start($sessionConfig);

// Check to see if we are active.
var_dump(SessionManager::exists());

// Destroy the session.
SessionManager::destroy();

// Check to see if we are active.
var_dump(SessionManager::exists());

```

### Session Helpers

Some avaliable API methods that you can use.

* Re-initialize session array with original values. <br />
`Kooser\Session\SessionManager::reset(): bool` <br />

* Abort the session and discard any changes. <br />
`Kooser\Session\SessionManager::abort(): bool` <br />

### Session Fingerprint

The session fingerprint system is enabled by default using the connecting users IP and user agent. In your session config, you would put this to disable or enable a validator which is the IP or user agent. From the example below, set the array values to either true or false to disable and enable them.

> When using the session fingerprint the session key `session_fingerprint` is reserved.

```php
$sessionConifg = [
    'fingerprint_validators' => [
        'ipValidate' => \true,
        'uaValidate' => \true,
    ],
];
```

## Contributing

All contributions are welcome! If you wish to contribute.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
