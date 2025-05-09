<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use app\Models\FarmerWarehouse;
use app\Role;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'role'
    ];

    // protected $casts = [
    //     'role' => Role::class,
    // ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get all of the farmerwarehouse for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function farmerwarehouse()
    {
        return $this->hasMany(FarmerWarehouse::class, 'user_id', 'id');
    }

    public function authorizeRoles($roles)
    {
        if (is_array($roles)) {
            if (!in_array($this->role, $roles)) {
                abort(403, 'This action is unauthorized.');
            }
        } else {
            if ($this->role !== $roles) {
                abort(403, 'This action is unauthorized.');
            }
        }
        return true;
    }
}
