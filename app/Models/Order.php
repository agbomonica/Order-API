<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'order_id';

    protected $guarded = ['order_id'];

    public $timestamps = false;



}
