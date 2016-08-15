<?php
/**
 * Created by PhpStorm.
 * User: rus
 * Date: 12.08.16
 * Time: 19:18
 * @property Repository repository
 */
class usersCategoryController
{
    private $responce;
    public function __construct()
    {
        $this->repository = new Repository('Category');
        $this->responce   = $this->repository->getList( ['name'] );
    }
    public function getResponce()
    {
        return $this->responce;
    }
}