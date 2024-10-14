<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['first_name', 'last_name', 'email', 'avatar'];

    //relationship between client and transactionss
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Accessor to generate the full URL for the avatar to be used in the frontend
    public function getAvatarUrlAttribute()
    {
        return asset('storage/' . $this->avatar);
    }
}
