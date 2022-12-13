<?php

namespace App\Repository;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class BaseRepository
 * @package App\Repository
 */
class BaseRepository implements EloquentRepositoryInterface
{
    /**
     * @param array $attributes
     * @return Model|void
     */
    public function create(array $attributes)
    {
    }

    /**
     * @param $id
     * @return mixed|void
     */
    public function find($id)
    {
    }

    /**
     * @return mixed|void
     */
    public function all()
    {
        return 'all';
    }


    /**
     * @return mixed|void
     */
    public function getAuthId()
    {
        return Auth::user()['user_id'];
    }

    /**
     * @param bool $all
     * @return int|mixed
     */
    public function getStore($all = true)
    {
        if ($all) return DB::table(TableStore)->where('city', '<>', 'None')->orderBy('name')->get();
        return DB::table(TableUser)->where('id', $this->getAuthId())->first()->store_id;
    }

    /**
     * @param array $attributes
     * @return Collection
     */
    public function getCategory(array $attributes)
    {
        $data = DB::table(TableCategory);
        if ($attributes['type'] == 'option') $data->select('id', 'name');
        return $data->where('is_active', 1)->orderBy('name')->get();
    }
}
