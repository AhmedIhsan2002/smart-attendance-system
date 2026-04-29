<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 'organization_id', 'plan_id', 'amount', 'currency',
        'status', 'billing_type', 'payment_method', 'transaction_id',
        'invoice_date', 'due_date', 'paid_at', 'items', 'notes'
    ];

    protected $casts = [
        'items' => 'array',
        'invoice_date' => 'date',
        'due_date' => 'date',
        'paid_at' => 'datetime'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function markAsPaid($transactionId = null)
    {
        $this->status = 'paid';
        $this->paid_at = now();
        if ($transactionId) {
            $this->transaction_id = $transactionId;
        }
        $this->save();
    }

    public function getAmountFormattedAttribute()
    {
        return $this->currency . ' ' . number_format($this->amount, 2);
    }
}
