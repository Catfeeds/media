<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrowseRecord extends Model
{
    protected $table = 'browse_records';

    protected $guarded = [];

    protected $connection = 'clw';
}
