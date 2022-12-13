<?php


namespace App\Repository;


/**
 * Interface ProductRepositoryInterface
 * @package App\Repository
 */
interface ProductRepositoryInterface extends CategoryRepositoryInterface
{
    /**
     * @return mixed
     */
    public function findProductByColumn();


    /**
     * @return mixed
     */
    public function getAuthId();


    /**
     * @param bool $all
     * @return mixed
     */
    public function getStore($all = true);

}
