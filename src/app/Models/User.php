<?php

namespace App\Models;

use App\Common\Constants\Role;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail, FilamentUser
{
    use HasFactory,
        Notifiable,
        HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Overide roles relationship.
     * @author vstars
     */
    public function roles()
    {
        return $this->morphToMany(
            config('permission.models.role'),
            'model',
            config('permission.table_names.model_has_roles'),
            config('permission.column_names.model_morph_key'),
            'role_id'
        );
    }

    /**
     * check role is ADMIN | MANAGER.
     * @author vstars
     */
    public function getIsAdminAttribute()
    {
        return $this->hasRole([Role::ADMIN, Role::MANAGER]);
    }

    /**
     * check role is USER | SUPERVISOR.
     * @author vstars
     */
    public function getIsUserAttribute()
    {
        return $this->hasRole([Role::SUPER, Role::USER]);
    }

    /**
     * set role can access panel.
     *
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_admin == 1;
        // return str_ends_with($this->email, '@gmail.com') && $this->hasVerifiedEmail();
    }

    /**
     * get user roles.
     *
     * @author vstars
     */
    public function getRoleAttribute()
    {
        return $this->roles()->first()->name ?? '';
    }
}
