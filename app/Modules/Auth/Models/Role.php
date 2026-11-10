<?php
namespace App\Modules\Auth\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['name', 'slug', 'description'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}