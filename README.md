
# Blok Repository

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/blok/laravel-repository/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/blok/laravel-repository/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/blok/laravel-repository/badges/build.png?b=master)](https://scrutinizer-ci.com/g/blok/laravel-repository/build-status/master)
[![Packagist](https://poser.pugx.org/blok/laravel-repository/d/total.svg)](https://packagist.org/packages/blok/laravel-repository)
[![Packagist](https://img.shields.io/packagist/l/blok/laravel-repository.svg)](https://packagist.org/packages/blok/laravel-repository)

An opiniated way to handle business logic with the Repository pattern.

This package tends to give you an opiniated structure to handle your business logic inside one repository folder instead of duplicating your code in Controllers, Seeders etc.

It comes with handy helpers to let you use this repository inside your controller, api controller or graphql mutation without the need to redefine the wheel everytimes.

## Installation

Install via composer

```
composer require blok/laravel-repository
```

## Usage

Blok repository is a Laravel package that will give extra functionnalities to your model and control.

### Create a repository class
```
php artisan make:repository UserRepository
```

It will create a Repository class inside /app/Repositories/UserRepository :

```
<?php

namespace App\Repositories;

use App\User;
use Blok\Repository\AbstractEloquentRepository;

class UsersRepository extends AbstractEloquentRepository
{
    function model()
    {
        return User::class;
    }
}
```

Without any configuration, it will handle the basic CRUD operations :

- all
- find
- findBy
- create
- update
- delete
- getForm
- validation

Off course, you can override any of these methods and create your own inside this Repository Class

###  How to use it inside your controller ?

Blok/Repository comes with a very handy and common ApiController structure. To use is you can do :

````php artisan make:apicontroller UserController````

It will create an ApiController inside App/Http/Controllers/Api :

````
<?php

namespace App\Http\Controllers\Api;

use App\Repositories\UsersRepository;
use Blok\Repository\Http\Controllers\AbstractApiController;

class UserController extends AbstractApiController
{
    function model()
    {
        return UsersRepository::class;
    }
}
````

This controller will handle directly the CRUD logic of your repository for more infos see AbstractApiController


###  How to use it in Graphql ?

Blok/Repository comes with a very handy and common Graphql Mutation structure. It assumes that you use [https://lighthouse-php.com/](https://lighthouse-php.com/) to handle GraphQL inside Laravel.

To use is you can do :

````php artisan make:mutation UserUpdateMutation````

It will create a graphql mutation inside App/Graphql/Mutations :

````
<?php

namespace App\GraphQL\Mutations;

class UserUpdateMutation extends AbstractUpdateMutation
{
    function repository()
    {
        return \App\Repositories\UserRepository::class;
    }
}
````

This controller will handle directly the CRUD logic mutation for Create, Update and Delete.

### Adding a business logic with a Criteria class

Off course, any methods of the AbstractClass can be overriden but sometimes you just need to add somewhere else your own query logic to reuse it. For that we implemented a usefull patern that will help to keep your query logic in a separated class reusable anywhere.

Let's give a simple exemple for the get all methods => you want to get only public users.

### Create a Criteria

``` php artisan make:criteria OnlyPublicCriteria ```

This will create a class inside /app/Repositories/Criterias/OnlyPublicCriteria

```
<?php

namespace App\Repositories\Criterias;

use Blok\Repository\AbstractCriteria;

class OnlyPublicCriteria extends AbstractCriteria
{
    public $type = "public";

    public function __construct($type = 'public'){
      $this->type = $type;
    }

    public function apply($model, $repository = null)
    {
        return $model->where('visibility', $this->type);
    }
}
```

### Use it inside your Repository

In your UserRepository, you can add and handle your criteria like that :

```
public function all($columns = array('*'))
{
    if (!auth()->check()) {
        $this->pushCriteria(new OnlyPublicCriteria());
    }

    return parent::all($columns);
}
```

It will apply the condition of where visibility=public automatically to the $userRepository->all() method.

### Use it inside your Controller

In your ControllerClass, you can inject this param like that :

```
<?php

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!auth()->check()){
          $this->userRepository->pushCriteria(new OnlyPublicCriteria('public'));
        }

        $users = $this->userRepository->paginate(12, $request->all());

        return view('users', compact('users'));
    }
}
```

It will have the same behavior but at the Controller level. Off course, you are free to add your variables logic when you initiate the criteria (for exemple here I push the type public for the demo).

Putting this logic inside a Criteria, will help you to queue your query condition and reuse it in different Repository.

### Criterias available by default:

You have criterias helpers available in the namespace Blok\Repository\Criterias.

They each contain an argument containing your filters, here are more explanations for some of them :

#### WhereCriteria:
This criteria is used to filter the data coming from the source model.
For the syntax, separate the elements with a space, you can use all the classical operators except BETWEEN which is not implemented yet. You can also use AND and OR with && and ||.

Example:
```
$filters = "first_name = sarah || first_name = mario || first_name LIKE %deb% && email LIKE %gmail% || email LIKE %outlook% && phone != null";
```

#### WhereHasCriteria:
This criteria works like WhereCriteria with the difference that it is there to filter elements of the models related to the source model.
For the syntax it's the same as WhereCriteria but you have to put the relation for which the filters are applied followed by a "->" (without space) at the beginning of the query and after each &&.
You can use the || but it will be applied to the filtered table (selected with "relationshipName->".

Example:
```
$filters = "bookings->price_ht > 1000 || price_ht < 100 && socialUsers->provider = google";
```

## Security

If you discover any security related issues, please [email me](daniel@cherrypulp.com) instead of using the issue tracker.

## Credits

- [Daniel Sum](https://github.com/cherrylabs/blok-repository)
- [All contributors](https://github.com/cherrylabs/blok-repository/graphs/contributors)

This package is bootstrapped with the help of
[blok/laravel-package-generator](https://github.com/cherrylabs/blok-laravel-package-generator).
