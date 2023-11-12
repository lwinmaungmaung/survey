<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class FormResponse extends Model
{
    use HasFactory;
    protected $fillable = [
        'form_id',
        'response',
    ];
    protected $casts = [
        'response' => 'array',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Form::class,'id','id','form_id','user_id');
    }
}
