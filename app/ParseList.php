<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParseList extends Model
{
    protected $table = 'parse_list';

    protected $fillable = [
        'adv_id',
        'title',
        'price',
        'href'
    ];
}
