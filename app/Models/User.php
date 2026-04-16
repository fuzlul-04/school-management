<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'phone', 'password', 'role_id', 'status', 'bangla_name'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'user_permission')
            ->withPivot('is_granted');
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role) {
            if ($this->role->permissions()->where('slug', $permission)->exists()) {
                return true;
            }
        }

        $directPermission = $this->permissions()
            ->where('slug', $permission)
            ->first();

        if ($directPermission) {
            return $directPermission->pivot->is_granted;
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->role && in_array($this->role->slug, ['super_admin', 'admin']);
    }

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRole($query, $roleId)
    {
        return $query->where('role_id', $roleId);
    }
}
