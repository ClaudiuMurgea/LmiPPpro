<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTest extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'Id';
    protected $table = 'WineDispenser.products_market';
}
