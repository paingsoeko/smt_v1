<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function universities(): HasMany
    {
        return $this->hasMany(University::class);
    }

    public function majorRegisters(): HasMany
    {
        return $this->hasMany(MajorRegister::class);
    }

    public function appSettings(): HasMany
    {
        return $this->hasMany(AppSettings::class);
    }
}
