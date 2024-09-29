<?php

namespace App\Models;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
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

        static::creating(function ($model) {
            $model->created_by = $model->created_by ?? auth()->id(); // Set the currently authenticated user's ID
            $model->team_id = $model->team_id ?? Filament::getTenant()->id;
        });


        static::created(function ($model) {
            $model->student_code = strtoupper('MKT' . str_pad($model->id, 6, '0', STR_PAD_LEFT));
            $model->save();
        });


        // Automatically set updated_by when updating an existing record
        static::updating(function ($model) {
            $model->updated_by = auth()->id(); // Set the currently authenticated user's ID
        });

    }
    public function getFullNameAttribute()
    {
        return "{$this->name} ({$this->father_name})"; // Concatenate the names
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }


    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function level()
    {
        // Get the latest university record for this student
        $university = University::where('student_id', $this->id)->latest()->first();

        // Return the year_of_attendance if the university record exists
        return $university ? $university->year_of_attendance : 'r';
    }

    public function university()
    {
        return $this->hasOne(University::class, 'student_id');
    }

}
