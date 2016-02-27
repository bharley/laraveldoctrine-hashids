# Laravel Doctrine Hashids
This package uses [vinkla]'s [Hashids bridge for Laravel] to provide a Doctrine type that obfuscates integer IDs using
[Hashids].


## Installation
Before installing this package, install and configure the [vinkla/hashids] package.

Require this package using composer:

    composer require "blake/laraveldoctrine-hashids:~1.0"

The next step is enabling the Doctrine type. If you are using the [laravel-doctrine/orm] package, add this to your
`config/doctrine.php` file:

```php
    'custom_types'              => [
        // ...
        'hashid' => Blake\Dbal\Types\HashidType::class,
    ],
```


## Usage
Anywhere you are using an integer in your entities you may safely replace with the `hashid` type:

```php
<?php

namespace App\Entities;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table
 */
class User
{
    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(type="hashid", nullable=false)
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;
    
    // ...
}
```

Interacting with the entity manager is as you'd expect. Simply replace where you'd use lame integer IDs with their
obfuscated version:

```php
$user = EntityManager::find(User::class, 'dlGRfgH73');
```

The ID will be decoded before it hits the database, and it will be encoded on the way out. This allows you to use
performant integer IDs while obfuscating details about your database and making URLs that point to objects slightly
prettier.


## Configuration
[vinkla/hashids] allows you to set up multiple "connections". This package will look for a `doctrine` connection and
use `main` as a fallback if the `doctrine` connection doesn't exist.


[Hashids]: http://hashids.org/
[vinkla]: https://github.com/vinkla
[vinkla/hashids]: https://github.com/vinkla/hashids
[Hashids bridge for Laravel]: https://github.com/vinkla/hashids
[laravel-doctrine/orm]: https://github.com/laravel-doctrine/orm
