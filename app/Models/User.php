<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function technician()
    {
        return $this->hasOne(Technician::class);
    }

    public function permissions()
    {
        return $this->roles->loadMissing('permissions')->pluck('permissions')->flatten()->unique('id');
    }

    public function hasPermission($permission)
    {
        return Cache::remember("user.{$this->id}.permission.{$permission}", 3600, function () use ($permission) {
            if ($this->isSuperAdmin()) return true;
            return $this->roles->loadMissing('permissions')
                ->pluck('permissions')
                ->flatten()
                ->pluck('name')
                ->contains($permission);
        });
    }

    public function hasAnyPermission($permissions)
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) return true;
        }
        return false;
    }

    public function isSuperAdmin()
    {
        return $this->roles->pluck('name')->contains('super_admin');
    }

    public function hasRole($roleName)
    {
        return $this->roles->pluck('name')->contains($roleName);
    }

    public function flushPermissionsCache()
    {
        $permissions = Permission::all();
        foreach ($permissions as $p) {
            Cache::forget("user.{$this->id}.permission.{$p->name}");
        }
    }
}
