<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MajorRegister extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'team_id',
        'student_id',
        'aprove_no',
        'ar_wa_tha_no',
        'type',
        'major',
        'get_university',
        'note',
        'created_by',
        'updated_by',
        'deleted_by',
    ];


    protected static function boot()
    {
        parent::boot();

        // Automatically set created_by when creating a new record
        static::creating(function ($model) {
            $model->created_by = auth()->id(); // Set the currently authenticated user's ID
               $model->team_id = Filament::getTenant()->id;
        });

        // Automatically set updated_by when updating an existing record
        static::updating(function ($model) {
            $model->updated_by = auth()->id(); // Set the currently authenticated user's ID
        });

    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
