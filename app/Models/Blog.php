<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\Product;

class Blog extends BaseModel
{
	protected $connection = 'mongodb';
    protected $collection = 'blog';

    protected $guarded = [];
}
