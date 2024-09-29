<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'current_attendance_year',
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
        'is_win',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    protected $casts = [
        'desk_id_history' => 'array',  // This will automatically convert to/from JSON when saved or retrieved
        'is_win' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $currentYear = AppSettings::where('team_id', Filament::getTenant()->id)->first()->year_of_attendance_university;
            $model->current_attendance_year = $currentYear;
            $model->created_by = auth()->id();

            // Fetch the last related record based on a relationship (for example: universities)
            $lastRecord = $model->latest()->first(); // Replace `universities` with the correct relationship

            if ($lastRecord && $model->year_of_attendance == $lastRecord->year_of_attendance) {
                // If the year_of_attendance matches, mark the last record as not winning
                $lastRecord->is_win = false;
                $lastRecord->save(); // Save the updated `is_win` status
            }else if ($lastRecord && $model->year_of_attendance != $currentYear) {
                $lastRecord->is_win = true;
                $lastRecord->save(); // Save the updated `is_win` status
            }
        });


        static::updating(function ($model) {
            $model->updated_by = auth()->id();
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
