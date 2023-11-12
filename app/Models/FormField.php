<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormField extends Model
{
    use HasFactory;
    protected $fillable=[
        'form_id',
        'name',
        'type',
        'required',
        'active',
        'options',
        'default',
        'placeholder',
        'help_text',
        'validation',
        'validation_message',
    ];
    protected $casts=[
        'options' => 'json',
        'required' => 'boolean',
    ];

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
