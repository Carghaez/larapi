# Carghaez/Larapi
## Introduction
Larapi is an API-friendly plugin of Laravel 5.4 and above based on the Larapi fork of [esbenp/larapi](https://github.com/esbenp/larapi). Authentication, error handling, resource filtering, sorting, pagination and much more included

Larapi comes included with...
* Laravel Passport for OAuth Authentication, including a proxy for password and refresh-token grants
* A new directory structure optimized for separating controllers and resources code. Groups your controllers, models, etc. by resource-type.
* [Optimus\Heimdal](https://github.com/esbenp/heimdal): A modern exception handler for APIs with Sentry and Bugsnag integration out-of-the-box
* [Optimus\Bruno](https://github.com/esbenp/bruno): A base controller class that gives sorting, filtering, eager loading and pagination for your endpoints
* [Optimus\Genie](https://github.com/esbenp/genie): A base repository class for requesting entities from your database. Includes integration with Bruno.
* [Optimus\Architect](https://github.com/esbenp/architect): A library for creating advanced structures of related entities
* [Optimus\ApiConsumer](https://github.com/esbenp/laravel-api-consumer): A small class for making internal API requests

## Functionality
* Parse GET parameters for dynamic eager loading of related resources, sorting and pagination
* Advanced filtering of resources using filter groups
* Use [Optimus\Architect](https://github.com/esbenp/architect) for sideloading, id loading or embedded loading of related resources

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

### How to create a Resource step-by-step
1. Create a resource folder into App/Http/Resources folder, i.e. Books
2. Into Books folder, create Controllers and Models folders
3. Create Book.php into App/Http/Resources/Books/Models folder, i.e.
```
<?php

namespace App\Http\Resources\Books\Models;

use Carghaez\Larapi\Resource\ResourceModel as Model;

class Book extends Model
{
}
```
4. Create BookController.php into App/Http/Resources/Books/Controllers, i.e.
```
<php

namespace App\Http\Resources\Books\Controllers;

use Carghaez\Larapi\Resource\ResourceController as Controller;
use App\Http\Resources\Books\Models\Book;

class BookController extends Controller
{
    protected $model = Book::class;
}
```
5. Finally, create routes_protected.php and routes_public.php into App/Http/Resources/Books folder, following standard access endpoint of your resource

### Standard endpoints available for all resources (i.e. Books) :

Function | Method | Route | Description
-------- | ------ | ----- | -----------
getAll() | GET | /books |  Get all elements
getById()| GET | /books/{id} | Get a single element by id
create() | POST or PUT | /books | Create a new element
update() | PUT or POST | /books/{id} | Update an exist element
delete() | DELETE | /books/{id} | Delete a element by id

## Usage
The examples will be of a hypothetical resource endpoint `/books` which will return a collection of `Book`,
each belonging to a `Author`.

```
Book n ----- 1 Author
```

### Available query parameters

Key | Type | Description
--- | ---- | -----------
Includes | array | Array of related resources to load, e.g. ['author', 'publisher', 'publisher.books']
Sort | array | Property to sort by, e.g. 'title'
Limit | integer | Limit of resources to return
Page | integer | For use with limit
Filter_groups | array | Array of filter groups. See below for syntax.

## Options

## Syntax documentation

### Eager loading

**Simple eager load**

`/books?includes[]=author`

Will return a collection of 5 `Book`s eager loaded with `Author`.

**IDs mode**

`/books?includes[]=author:ids`

Will return a collection of `Book`s eager loaded with the ID of their `Author`

**Sideload mode**

`/books?includes[]=author:sideload`

Will return a collection of `Book`s and a eager loaded collection of their
`Author`s in the root scope.

[See mere about eager loading types in Optimus\Architect's README](https://github.com/esbenp/architect)

### Pagination

Two parameters are available: `limit` and `page`. `limit` will determine the number of
records per page and `page` will determine the current page.

`/books?limit=10&page=3`

Will return books number 30-40.

### Sorting

Should be defined as an array of sorting rules. They will be applied in the
order of which they are defined.

**Sorting rules**

Property | Value type | Description
-------- | ---------- | -----------
key | string | The property of the model to sort by
direction | ASC or DESC | Which direction to sort the property by

**Example**

```json
[
    {
        "key": "title",
        "direction": "ASC"
    }, {
        "key": "year",
        "direction": "DESC"
    }
]
```

Will result in the books being sorted by title in ascending order and then year
in descending order.

### Filtering

Should be defined as an array of filter groups.

**Filter groups**

Property | Value type | Description
-------- | ---------- | -----------
or | boolean | Should the filters in this group be grouped by logical OR or AND operator
filters | array | Array of filters (see syntax below)

**Filters**

Property | Value type | Description
-------- | ---------- | -----------
key | string | The property of the model to filter by (can also be custom filter)
value | mixed | The value to search for
operator | string | The filter operator to use (see different types below)
not | boolean | Negate the filter

**Operators**

Type | Description | Example
---- | ----------- | -------
ct | String contains | `ior` matches `Giordano Bruno` and `Giovanni`
sw | Starts with | `Gior` matches `Giordano Bruno` but not `Giovanni`
ew | Ends with | `uno` matches `Giordano Bruno` but not `Giovanni`
eq | Equals | `Giordano Bruno` matches `Giordano Bruno` but not `Bruno`
gt | Greater than | `1548` matches `1600` but not `1400`
gte| Greater than or equalTo | `1548` matches `1548` and above (ony for Laravel 5.4 and above)
lte | Lesser than or equalTo | `1600` matches `1600` and below (ony for Laravel 5.4 and above)
lt | Lesser than | `1600` matches `1548` but not `1700`
in | In array | `['Giordano', 'Bruno']` matches `Giordano` and `Bruno` but not `Giovanni`
bt | Between | `[1, 10]` matches `5` and `7` but not `11`

**Special values**

Value | Description
----- | -----------
null (string) | The property will be checked for NULL value
(empty string) | The property will be checked for NULL value

#### Custom filters

Remember our relationship `Books n ----- 1 Author`. Imagine your want to
filter books by `Author` name.

```json
[
    {
        "filters": [
            {
                "key": "author",
                "value": "Optimus",
                "operator": "sw"
            }
        ]
    }
]
```

Now that is all good, however there is no `author` property on our
model since it is a relationship. This would cause an error since
Eloquent would try to use a where clause on the non-existant `author`
property. We can fix this by implementing a custom filter. Where
ever you are using the `EloquentBuilderTrait` implement a function named
`filterAuthor`

```php
public function filterAuthor(Builder $query, $method, $clauseOperator, $value)
{
    // if clauseOperator is idential to false,
    //     we are using a specific SQL method in its place (e.g. `in`, `between`)
    if ($clauseOperator === false) {
        call_user_func([$query, $method], 'authors.name', $value);
    } else {
        call_user_func([$query, $method], 'authors.name', $clauseOperator, $value);
    }
}
```

*Note:* It is important to note that a custom filter will look for a relationship with
the same name of the property. E.g. if trying to use a custom filter for a property
named `author` then Bruno will try to eagerload the `author` relationship from the
`Book` model.

**Custom filter function**

Argument | Description
-------- | -----------
$query | The Eloquent query builder
$method | The where method to use (`where`, `orWhere`, `whereIn`, `orWhereIn` etc.)
$clauseOperator | Can operator to use for non-in wheres (`!=`, `=`, `>` etc.)
$value | The filter value
$in | Boolean indicating whether or not this is an in-array filter

#### Examples

```json
[
    {
        "or": true,
        "filters": [
            {
                "key": "author",
                "value": "Optimus",
                "operator": "sw"
            },
            {
                "key": "author",
                "value": "Prime",
                "operator": "ew"
            }
        ]
    }
]
```

Books with authors whoose name start with `Optimus` or ends with `Prime`.

```json
[
    {
        "filters": [
            {
                "key": "author",
                "value": "Brian",
                "operator": "sw"
            }
        ]
    },
    {
        "filters": [
            {
                "key": "year",
                "value": 1990,
                "operator": "gt"
            },
            {
                "key": "year",
                "value": 2000,
                "operator": "lt"
            }
        ]
    }
]
```

Books with authors whoose name start with Brian and which were published between years 1990 and 2000.

### Optional Shorthand Filtering Syntax (Shorthand)
Filters may be optionally expressed in Shorthand, which takes the a given filter
array[key, operator, value, not(optional)] and builds a verbose filter array upon evaluation.

For example, this group of filters (Verbose)
```json
[
    {
        "or": false,
        "filters": [
            {
                "key": "author",
                "value": "Optimus",
                "operator": "sw"
            },
            {
                "key": "author",
                "value": "Prime",
                "operator": "ew"
            }
            {
                "key": "deleted_at",
                "value": null,
                "operator": "eq",
                "not": true
            }
        ]
    }
]
```
May be expressed in this manner (Shorthand)
```json
[
    {
        "or": false,
        "filters": [
            ["author", "sw", "Optimus"],
            ["author", "ew", "Prime"],
            ["deleted_at", "eq", null, true]
        ]
    }
]
```

## Standards

This package is compliant with [PSR-1], [PSR-2] and [PSR-4]. If you notice compliance oversights,
please send a patch via pull request.

[PSR-1]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-1-basic-coding-standard.md
[PSR-2]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md
[PSR-4]: https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md

## License

The MIT License (MIT). Please see [License File](https://github.com/carghaez/larapi/blob/master/LICENSE) for more information.
