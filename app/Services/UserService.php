<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService extends BaseService
{
    protected function setModel()
    {
        $this->model = new User();
    }

    public function updateRole(User $user, string $role)
    {
        $user->syncRoles([$role]);
        return $user;
    }

    public function updateStatus(User $user, bool $isActive)
    {
        $user->is_active = $isActive;
        $user->save();
        return $user;
    }

    public function updatePassword(User $user, string $password)
    {
        $user->password = Hash::make($password);
        $user->save();
        return $user;
    }
}
