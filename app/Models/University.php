<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'student_id',
        'type',
        'major',
        'year_of_attendance',
        'last_desk_id',
        'current_desk_id',
        'desk_id_history',
        'assignment_a',
        'assignment_b',
        'note',
        'custom_column_1',
        'custom_column_2',
        'custom_column_3',
        'custom_column_4',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    protected $casts = [
        'desk_id_history' => 'array',  // This will automatically convert to/from JSON when saved or retrieved
    ];

protected static function boot()
    {
        parent::boot();

        // Automatically set created_by when creating a new record
        static::creating(function ($model) {
            $model->created_by = auth()->id(); // Set the currently authenticated user's ID
        });

        // Automatically set updated_by when updating an existing record
        static::updating(function ($model) {
            $model->updated_by = auth()->id(); // Set the currently authenticated user's ID
        });

    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function deleter()
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }


public function team(): BelongsTo
{
    return $this->belongsTo(Team::class);
}
}
