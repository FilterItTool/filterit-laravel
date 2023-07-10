<?php

namespace FilterIt\Tests\Unit\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use FilterIt\Tests\Unit\Database\Factories\UserFactory;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'age'
    ];

    protected static function newFactory()
    {
        return UserFactory::new();
    }
}