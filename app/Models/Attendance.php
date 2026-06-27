<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'employee_id',
        'company_id',
        'date',
        'check_in_time',
        'check_out_time',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'check_in_time' => 'time',
        'check_out_time' => 'time',
    ];

    /**
     * Get the employee that owns the attendance.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the company that owns the attendance.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Calculate working hours.
     */
    public function getWorkingHoursAttribute()
    {
        if (!$this->check_in_time || !$this->check_out_time) {
            return null;
        }
        return $this->check_out_time->diffInHours($this->check_in_time);
    }
}
