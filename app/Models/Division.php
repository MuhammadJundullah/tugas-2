<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Division extends Model
{
    //
    use HasFactory;

    protected $keyType = 'string'; // UUID bertipe string
    public $incrementing = false; // Non-incremental karena UUID
    protected $fillable = ['id', 'name']; // Kolom yang dapat diisi

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->id = (string) Str::uuid();
        });
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
