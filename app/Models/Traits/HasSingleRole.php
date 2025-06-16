<?php

namespace App\Models\Traits;

use Spatie\Permission\Traits\HasRoles as SpatieHasRoles;


trait HasSingleRole
{
    use SpatieHasRoles {
        assignRole as protected spatieAssignRole;
        syncRoles as protected spatieSyncRoles;
    }

    public function assignRole($role)
    {
        $this->roles()->detach();
        return $this->spatieAssignRole($role);
    }

    public function syncRoles($roles)
    {
        if (count($roles) > 1) {
            throw new \InvalidArgumentException('Users can only have one role');
        }
        return $this->spatieSyncRoles($roles);
    }

    public function hasRole($roles, $guard = null): bool
    {
        if (is_array($roles)) {
            return $this->roles()->whereIn('name', $roles)->exists();
        }
        return $this->roles()->where('name', $roles)->exists();
    }
}