<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

/**
 * Class ParseList.
 *
 * @package namespace App\Entities;
 */
class ParseList extends Model implements Transformable
{
    use TransformableTrait;

    protected $table = 'parse_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'adv_id',
        'title',
        'price',
        'href',
        'description',
        'create_date'
    ];

}
