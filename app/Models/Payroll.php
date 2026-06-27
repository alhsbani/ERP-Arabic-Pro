<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
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
        'payroll_date',
        'salary',
        'total_allowances',
        'total_deductions',
        'net_salary',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payroll_date' => 'date',
        'salary' => 'decimal:2',
        'total_allowances' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    /**
     * Get the employee that owns the payroll.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the company that owns the payroll.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the allowances for the payroll.
     */
    public function allowances()
    {
        return $this->hasMany(PayrollAllowance::class);
    }

    /**
     * Get the deductions for the payroll.
     */
    public function deductions()
    {
        return $this->hasMany(PayrollDeduction::class);
    }
}
