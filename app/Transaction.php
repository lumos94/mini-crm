<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    // Allow mass assignment for these fields
    protected $fillable = [
        'client_id',
        'transaction_date',
        'amount',
    ];

    // Define the relationship with the Client model
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
