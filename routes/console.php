<?php



/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

//TODO: move everything to controller

/**
 * Clearing the log files
 *
 */

use App\System\ApplicationPermissions;
use App\System\ApplicationRoles;
use App\User;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;

Artisan::command('logs:clear', function() {
    foreach(scandir(storage_path('logs')) as $key => $value){
        if($value == "." || $value == "..") continue;
        if($value == ".gitignore") continue;
//        unlink(storage_path('logs') . DIRECTORY_SEPARATOR. $value);
        file_put_contents(storage_path('logs') . DIRECTORY_SEPARATOR. $value, "");
    }

    $this->comment('Logs have been cleared!');
})->describe('Clear log files');

Artisan::command('system:setup', function() {
    // TODO: system:setup
})->describe('Install the application');


Artisan::command('permission:defaults', function() {

    // TODO: extend
    $defaultPermissions = [
        ApplicationPermissions::SHOW_ADMIN_MENU
    ];

    $this->comment('Defining permissions...');
    $bar = $this->output->createProgressBar(count($defaultPermissions));
    $bar->start();
    foreach($defaultPermissions as $permission){
        // TODO: exitCode checkup
        $exitCode = Artisan::call('permission:create-permission', [
            'name' => $permission
        ]);
        $bar->advance();
    }
    $bar->finish();

    // TODO: extend
    $defaultRoles = [
        ApplicationRoles::SUPERADMIN => [], // Allowed everything
        ApplicationRoles::ADMIN => [
            ApplicationPermissions::SHOW_ADMIN_MENU
        ],
        ApplicationRoles::MODERATOR => [],
        ApplicationRoles::USER => [],
    ];

    $this->comment(' Done.');
    $this->comment('Defining roles...');
    $bar = $this->output->createProgressBar(count($defaultRoles));
    $bar->start();
    foreach($defaultRoles as $role => $permissions){
        // TODO: exitCode checkup
        $exitCode = Artisan::call('permission:create-role', [
            'name' => $role,
            'permissions' => implode("|", $permissions)
        ]);
        $bar->advance();
    }
    $bar->finish();

    $this->comment(' Done.');
})->describe('Define the default permissions and roles');

Artisan::command('permission:grant {user_id} {role}', function() {
    $userId = $this->argument("user_id");
    $roleName = $this->argument("role");

    /** @var User $user */
    $user = User::all()->where("id", $userId)->first();

    if($user == null){
        $this->error('Error: user not found.');
        return;
    }

    $role = null;
    try {
        $role = Role::findByName($roleName);
    } catch (RoleDoesNotExist $e){
        $role = null;
    }
    if($role == null){
        $this->error("Error: role not found.");
        return;
    }

    $user->assignRole($roleName);

    $this->comment('Done.');
})->describe('Grant role access to the specified user');