## How to write crud with DDD patterns 

I want to explain to you a explain with User Crud Example

### Step 1

Go to that file `src/BlendedConcept/Security/Presentation/HTTP/routes.php` and write your own **Route** as below as an example.

```
    Route::resource('users', UserController::class);
```

And then create a `UserController.php` and write your functionality on that.

![screenshot](https://github.com/hareom284/laravelmentorshiptest/assets/110709031/37df30a5-5bff-4fcc-bfd2-ed929ccb6870)

## Step 2

<strong> You need to create `SecurityRepository.php` and `SecurityRepostoryInterFace.php`
that can be found inside that folder path below 
`src/BlendedConcept/Security/Application/Repositories/Eloquent/SecurityRepository.php` and `src/BlendedConcept/Security/Domain/Repositories/SecurityRepositoryInterface.php` as below 

### SecurtiyRepostoryInterface.php
```SecurtiyRepostoryInterface.php

<?php

namespace Src\BlendedConcept\Security\Domain\Repositories;

use Src\BlendedConcept\Security\Application\DTO\UserData;
use Src\BlendedConcept\Security\Domain\Model\User;

interface SecurityRepositoryInterface
{
    
    //Get user
    public function getUsers($filters = []);

    //Get only user name
    public function getUsersName();

    // store user
    public function createUser(User $user);

    //  update user
    public function updateUser(UserData $user);


    //delete user


    public function deleteUser(int $user_id);
    
}


```


### SecurityRepositroy.php

<strong> This below will be used for related database  inserting and updating with Eloquent </strong>

```.php

<?php

namespace Src\BlendedConcept\Security\Application\Repositories\Eloquent;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Src\BlendedConcept\Security\Application\DTO\UserData;
use Src\BlendedConcept\Security\Application\Mappers\UserMapper;
use Src\BlendedConcept\Security\Domain\Model\User;
use Src\BlendedConcept\Security\Domain\Repositories\SecurityRepositoryInterface;
use Src\BlendedConcept\Security\Domain\Resources\UserResource;
use Src\BlendedConcept\Security\Infrastructure\EloquentModels\UserEloquentModel;

class SecurityRepository implements SecurityRepositoryInterface
{
    //get only the user name and i
    public function getUsersNameId()
    {
        $user_names = UserEloquentModel::get();

        return $user_names;
    }

    // get the user
    public function getUsers($filters = [])
    {
        //set roles
        $users = UserResource::collection(UserEloquentModel::filter($filters)
            ->with('roles')
            ->orderBy('id', 'desc')
            ->paginate($filters['perPage'] ?? 10));

        return $users;
    }

    //get only the user name
    public function getUsersName()
    {
        $user_names = UserEloquentModel::pluck('name');

        return $user_names;
    }

    // store user
    public function createUser(User $user)
    {

        $userEloquent = UserMapper::toEloquent($user);
        //verify email now()
        $userEloquent->email_verified_at = now();
        $userEloquent->save();
        if (request()->hasFile('image') && request()->file('image')->isValid()) {
            $userEloquent->addMediaFromRequest('image')->toMediaCollection('image', 'media_user');
        }

        $userEloquent->roles()->sync(request('role'));
    }

    //  update user
    public function updateUser(UserData $user)
    {

        $userArray = $user->toArray();
        $updateUserEloquent = UserEloquentModel::query()->findOrFail($user->id);
        $updateUserEloquent->fill($userArray);
        $updateUserEloquent->save();

        //  delete the image if reupload or insert if does not exist
        if (request()->hasFile('image') && request()->file('image')->isValid()) {

            $old_image = $updateUserEloquent->getFirstMedia('image');
            if ($old_image != null) {
                $old_image->delete();

                $updateUserEloquent->addMediaFromRequest('image')->toMediaCollection('image', 'media_user');
            } else {

                $updateUserEloquent->addMediaFromRequest('image')->toMediaCollection('image', 'media_user');
            }
        }

        $updateUserEloquent->roles()->sync(request('role'));
    }

    public function deleteUser(int $user_id)
    {
        $user = UserEloquentModel::query()->findOrFail($user_id);
        $user->delete();
    }
}


```

## Step 3

<strong>You need to write UserRepository and UserInerface that might need to bind inside this folder path like below `src/BlendedConcept/Security/Application/Providers/SecurtiyServiceProvider.php` as below then you called to use it from UseCase </strong>

```php

  <?php

namespace Src\BlendedConcept\Security\Application\Providers;

use Illuminate\Support\ServiceProvider;
use Src\BlendedConcept\Security\Application\Repositories\Eloquent\NotificationRepository;
use Src\BlendedConcept\Security\Application\Repositories\Eloquent\SecurityRepository;
use Src\BlendedConcept\Security\Domain\Repositories\NotificationRepositoryInterface;
use Src\BlendedConcept\Security\Domain\Repositories\SecurityRepositoryInterface;

class SecurityServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            SecurityRepositoryInterface::class,
            SecurityRepository::class
        );

        $this->app->bind(
            NotificationRepositoryInterface::class,
            NotificationRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}

```

### Step 4 

<strong>
    
`src/BlendedConcept/Security/Application/UseCases` This file has to folder Command is used for Inserting and updating data and Queries is used for queries from
database 

</strong>

Inside `src/BlendedConcept/Security/Application/UseCases/Commands/User/StoreUserCommand.php` you need to call the repository interface that you implement inside the repository as below

StoreUserCommand is used for inserting data inside the database.

```php

<?php

namespace Src\BlendedConcept\Security\Application\UseCases\Commands\User;

use Src\BlendedConcept\Security\Domain\Model\User;
use Src\BlendedConcept\Security\Domain\Repositories\SecurityRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class StoreUserCommand implements CommandInterface
{
    private SecurityRepositoryInterface $repository;

    public function __construct(
        private readonly User $user
    ) {
        $this->repository = app()->make(SecurityRepositoryInterface::class);
    }

    public function execute()
    {
        return $this->repository->createUser($this->user);
    }
}

```

UpdateUserCommand is used to update data inside the data 

```php

<?php

namespace Src\BlendedConcept\Security\Application\UseCases\Commands\User;

use Src\BlendedConcept\Security\Application\DTO\UserData;
use Src\BlendedConcept\Security\Domain\Repositories\SecurityRepositoryInterface;
use Src\Common\Domain\CommandInterface;

class UpdateUserCommand implements CommandInterface
{
    private SecurityRepositoryInterface $repository;

    public function __construct(
        private readonly UserData $userData
    ) {
        $this->repository = app()->make(SecurityRepositoryInterface::class);
    }

    public function execute()
    {
        return $this->repository->updateUser($this->userData);
    }
}


```

Get UserListWithPagination.php is used for queries data from database 

```php

<?php

namespace Src\BlendedConcept\Security\Application\UseCases\Queries\Users;

use Src\BlendedConcept\Security\Domain\Repositories\SecurityRepositoryInterface;
use Src\Common\Domain\QueryInterface;

class GetUsersWithPagination implements QueryInterface
{
    private SecurityRepositoryInterface $repository;

    public function __construct(
        private readonly array $filters
    ) {
        $this->repository = app()->make(SecurityRepositoryInterface::class);
    }

    public function handle()
    {
        return $this->repository->getUsers($this->filters);
    }
}

```


