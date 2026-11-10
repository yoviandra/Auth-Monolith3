<?php

namespace App\Modules\Membership\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Modules\Auth\Models\Role;

class Member extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'member_number',
        'name',
        'email',
        'phone',
        'address',
        'status',
    ];

    public function memberships(): HasMany
    {
        return $this->hasMany(Membership::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'member_role',
            'member_id',
            'role_id'
        )->withTimestamps();
    }
}
