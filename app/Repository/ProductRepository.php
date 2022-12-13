<?php


namespace App\Repository;

use Illuminate\Support\Facades\DB;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    public function all()
    {
        return DB::table('tec_products')
            ->select('id', 'name', 'code')
            ->where('is_active', 1)
            ->get();
    }

    public function create(array $attributes)
    {
        // TODO: Implement create() method.

    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    /**
     * @return array|mixed
     */
    public function findProductByColumn()
    {
        foreach (request()->all() as $key => $value)
            if ($value) return DB::table(TableProduct)
                ->join(TableProductStoreQty, TableProduct . '.id', '=', TableProductStoreQty . '.product_id')
                ->where(TableProduct . '.' . $key, $value)
                ->where(TableProduct . '.is_active', 1)
                ->where(TableProductStoreQty . '.store_id', $this->getStore(false))
                ->first();
        return null;
    }
}
