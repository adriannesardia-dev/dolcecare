<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Patient extends Model
{
    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'gender',
        'contact_number',
        'address',
        'medical_history',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
