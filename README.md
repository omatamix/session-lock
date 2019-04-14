![Packagist Lang](https://img.shields.io/badge/PHP-%3E%3D7.2-blue.svg)
[![Build Status](https://travis-ci.org/Kooser6/Session.svg?branch=master)](https://travis-ci.org/Kooser6/Session)
[![Coverage Status](https://coveralls.io/repos/github/Kooser6/Session/badge.svg?branch=master)](https://coveralls.io/github/Kooser6/Session?branch=master)

# Session

Securely manage and preserve session data.

## Requirements

> pdo php extension <br />
> php 7.2 and up

## Installation

via Composer:

The best way to install this session library is through composer. If you do not have composer installed you can install it directly from thier website (https://getcomposer.org/). After composer is successfully install run the command line code below.

```sh
composer require kooser/session
```

## Usage

Our session api is easy to use static methods that replace the native session function. You ultimately control the session using the `SessionManager` class.
