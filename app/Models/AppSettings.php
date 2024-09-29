<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'year_of_attendance_major',
        'year_of_attendance_university',
        'created_by',
        'updated_by',
    ];


    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

}
