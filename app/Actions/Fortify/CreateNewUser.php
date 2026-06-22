<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use App\Models\Internal\Role;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Support\Facades\Log;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
public function create(array $input): User
{


    Validator::make($input, [
        ...$this->profileRules(),
        'password' => $this->passwordRules(),
    ])->validate();

    $user = User::create([
        'name' => $input['name'],
        'email' => $input['email'],
        'password' => $input['password'],
    ]);

    Log::info('User created', [
        'user_id' => $user->id,
        'email' => $user->email,
    ]);

    $memberRole = Role::where('name', 'member')->first();

    Log::info('Member role lookup', [
        'role' => $memberRole?->toArray(),
    ]);

    $user->assignRole($memberRole);

    Log::info('Roles after assignment', [
        'roles' => $user->fresh()->getRoleNames()->toArray(),
    ]);

    return $user;
}
}