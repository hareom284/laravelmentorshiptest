## How to write crud with DDD patterns 

I want to explain to you a explain with User Crud Example

### Step 1

Go to that file `src/BlendedConcept/Security/Presentation/HTTP/routes.php` and write your own **Route** as below as an example.

```
    Route::resource('users', UserController::class);
```

And then create a `UserController.php` and write your functionality on that.
```php

<?php

namespace Src\BlendedConcept\Security\Presentation\HTTP;

use Inertia\Inertia;
use Src\BlendedConcept\Organization\Application\UseCases\Queries\GetOrganizatonName;
use Src\BlendedConcept\Security\Application\Policies\UserPolicy;
use Src\BlendedConcept\Security\Application\Requests\StoreUserRequest;
use Src\BlendedConcept\Security\Application\Requests\UpdateUserRequest;
use Src\BlendedConcept\Security\Application\UseCases\Queries\Roles\GetRoleName;
use Src\BlendedConcept\Security\Application\Requests\updateUserPasswordRequest;
use Src\BlendedConcept\Security\Application\UseCases\Queries\Users\GetUserName;
use Src\BlendedConcept\Security\Application\UseCases\Queries\Users\GetUsersWithPagination;
use Src\BlendedConcept\Security\Application\DTO\UserData;
use Src\BlendedConcept\Security\Application\Mappers\UserMapper;
use Src\BlendedConcept\Security\Application\UseCases\Commands\User\StoreUserCommand;
use Src\BlendedConcept\Security\Application\UseCases\Commands\User\UpdateUserCommand;
use Src\BlendedConcept\Security\Application\UseCases\Commands\User\DelectUserCommand;
use Src\BlendedConcept\Security\Domain\Services\UserService;
use Src\BlendedConcept\Security\Infrastructure\EloquentModels\UserEloquentModel;
use Src\Common\Infrastructure\Laravel\Controller;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    protected $userService;

    public function __construct()
    {
        $this->userService = app()->make(UserService::class);
    }

    /***
     *  @params null
     *
     *  @return \Illuminate\Http\RedirectResponse|\Inertia\Response
     *
     */
    public function index()
    {


        abort_if(authorize('view', UserPolicy::class), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {

            // Get the filters from the request, or initialize an empty array if they are not present
            $filters = request()->only(['name', 'email', 'role', 'search', 'perPage', 'roles']) ?? [];

            // Retrieve users with pagination using the provided filters
            $users = (new GetUsersWithPagination($filters))->handle();
            // Retrieve user names
            $users_name = (new GetUserName())->handle();

            // Retrieve role names
            $roles_name = (new GetRoleName())->handle();

            $oragnization_name = (new GetOrganizatonName())->handle();

            // Render the Inertia view with the obtained data
            return Inertia::render(config('route.users.index'), [
                'users' => $users,
                'users_name' => $users_name,
                'roles_name' => $roles_name,
                'organizations' => $oragnization_name,
            ]);
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('sytemErrorMessage', $e->getMessage());
        }
    }

    public function create()
    {
        abort(404);
    }

    /**
     * Store a new user.
     *
     * @param  StoreUserRequest  $request The incoming request.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        abort_if(authorize('create', UserPolicy::class), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {

            $request->validated();
            $newUser = UserMapper::fromRequest($request);

            $createNewUser = new StoreUserCommand($newUser);
            $createNewUser->execute();

            return redirect()->route('users.index')->with('successMessage', 'User created successfully!');
        } catch (\Exception $e) {
            // Handle the exception, log the error, or display a user-friendly error message.
            return redirect()->route('users.index')->with('sytemErrorMessage', $e->getMessage());
        }
    }

    //update user
    public function update(UpdateUserRequest $request, UserEloquentModel $user)
    {
        abort_if(authorize('edit', UserPolicy::class), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $updateUser = UserData::fromRequest($request, $user->id);
        $updatedUserCommand = (new UpdateUserCommand($updateUser));

        $updatedUserCommand->execute();

        return redirect()->route('users.index')->with('successMessage', 'User Updated Successfully!');
    }

    public function destroy(UserEloquentModel $user)
    {
        abort_if(authorize('destroy', UserPolicy::class), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user = new DelectUserCommand($user->id);
        $user->execute();

        return redirect()->route('users.index')->with('successMessage', 'User Deleted Successfully!');
    }

    public function show()
    {
        return Inertia::render(config('route.users.show'));
    }

    public function changePassword(updateUserPasswordRequest $request)
    {

        try {
            $isPasswordMatch = $this->userService->changePassword($request);
            if ($isPasswordMatch) {
                return redirect()->route('userprofile')->with('successMessage', 'Password Updated Successfully!');
            }

            return redirect()->route('userprofile')->with('errorMessage', 'Password does not match!');
        } catch (\Exception $error) {

            dd('something was wrong', $error->getMessage());
        }
    }
}

```

