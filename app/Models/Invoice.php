<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_number',
        'customer_id',
        'company_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'tax_amount',
        'tax_rate',
        'discount_amount',
        'total',
        'paid_amount',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_amount' => 'decimal:2',
    ];

    /**
     * Get the customer that owns the invoice.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the company that owns the invoice.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the invoice items.
     */
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the remaining balance.
     */
    public function getRemainingBalanceAttribute()
    {
        return $this->total - $this->paid_amount;
    }

    /**
     * Check if invoice is overdue.
     */
    public function getIsOverdueAttribute()
    {
        return $this->status != 'paid' && now()->isAfter($this->due_date);
    }
}
