<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\ParseListRepository;
use App\Entities\ParseList;
use App\Validators\ParseListValidator;

/**
 * Class ParseListRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class ParseListRepositoryEloquent extends BaseRepository implements ParseListRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ParseList::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }
    
}
