<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    // Table name
    protected $table = 'students';

    // Mass assignable attributes
    protected $fillable = [
        'student_code',
        'name',
        'team_id',
        'student_nrc_code',
        'student_nrc_no',
        'date_of_birth',
        'grade_10_desk_id',
        'grade_10_total_mark', // Fix the spelling to 'grade_10_total_mark'
        'grade_10_passed_year',
        'father_name',
        'father_nrc_code',
        'father_nrc_no',
        'mother_name',
        'mother_nrc_code',
        'mother_nrc_no',
        'student_phone',
        'parent_phone',
        'address',
        'note',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    // Dates for the model
    protected $dates = ['date_of_birth', 'deleted_at'];

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

    public function universities(): HasMany
    {
        return $this->hasMany(University::class);
    }

    public function majorRegister(): HasOne
    {
        return $this->hasOne(MajorRegister::class);
    }
}
