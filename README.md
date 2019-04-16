[![Build Status](https://travis-ci.org/Kooser6/Session.svg?branch=master)](https://travis-ci.org/Kooser6/Session)
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

Our session API is easy to use static methods that replace the native session function. You ultimately control the session using the `SessionManager` class. Here is a small example of starting a secure session. Also, the session config is the session runtime config which can be found here (https://www.php.net/manual/en/session.configuration.php). You can use them in the session config array just remove the `session.` prefix.

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
    "cookie_samesite"  => "Lax",
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

This session library comes with its own custom session handlers which can be implemented and used with complete ease. We have a lot of session handlers to choose from here is an example of using the filesystem handler. Also when using the MySQL session handler it will create the table for you, all you have to do is set the name of the table.

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

If you have another session storage handler in mind here is a list of avaliable session handlers below excluding the ones above.

> Create a Null session handler. <br />
`Kooser\Session\Handler\NullSessionHandler(): self` <br />

> Create a MongoDB session handler. <br />
`Kooser\Session\Handler\MongoDBSessionHandler(\MongoCollection $collection): self`

> Create a Memcached session handler. <br />
`Kooser\Session\Handler\MemcachedSessionHandler(string $memoryStore): self`

> Create a Redis Session Handler. <br />
`Kooser\Session\Handler\RedisSessionHandler(string $redisServer): self`

### Session Variables

Session variables are the key reason why we use sessions. We provide an easy to use session API to make variable management as easy as possible. The has method check to see if the variable exists, the set method is self-explanatory, the get method is self-explanatory, the flash method is the same as the get method except it deletes the variable after use, and the delete method is self-explanatory.

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

All contributions are welcome! If you wish to contribute, create an issue first so that your feature, problem or question can be discussed.

## License

This project is licensed under the terms of the [MIT License](https://opensource.org/licenses/MIT).
