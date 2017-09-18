# Carghaez/Larapi
Larapi is an API-friendly plugin of Laravel based on the Larapi fork of [esbenp/larapi](https://github.com/esbenp/larapi).

Larapi comes included with...
* Laravel Passport for OAuth Authentication, including a proxy for password and refresh-token grants
* A new directory structure optimized for separating controllers and resources code. Groups your controllers, models, etc. by resource-type.
* [Optimus\Heimdal](https://github.com/esbenp/heimdal): A modern exception handler for APIs with Sentry and Bugsnag integration out-of-the-box
* [Optimus\Bruno](https://github.com/esbenp/bruno): A base controller class that gives sorting, filtering, eager loading and pagination for your endpoints
* [Optimus\Genie](https://github.com/esbenp/genie): A base repository class for requesting entities from your database. Includes integration with Bruno.
* [Optimus\Architect](https://github.com/esbenp/architect): A library for creating advanced structures of related entities
* [Optimus\ApiConsumer](https://github.com/esbenp/laravel-api-consumer): A small class for making internal API requests

## Introduction

## Installation
Required Laravel 5.4 and above

```bash
composer require carghaez/larapi ~1.0
```

Migrate the tables.

```bash
php artisan migrate
```

Larapi comes with Passport include as the default authenticatin method. You should now install it using this command.

```bash
php artisan passport:install --force
```

Copy-paste the generated secrets and IDs into your `.env` file like so.

```
PERSONAL_CLIENT_ID=1
PERSONAL_CLIENT_SECRET=mR7k7ITv4f7DJqkwtfEOythkUAsy4GJ622hPkxe6
PASSWORD_CLIENT_ID=2
PASSWORD_CLIENT_SECRET=FJWQRS3PQj6atM6fz5f6AtDboo59toGplcuUYrKL
```

## Implementation

## Options

## License

The MIT License (MIT). Please see [License File](https://github.com/carghaez/larapi/blob/master/LICENSE) for more information.
