<?php

namespace App\Interfaces;

interface BrandingRepositoryInterface
{
    /**
     * Get's a $branding by it's ID
     *
     * @param int
     */
    public function get();

    /**
     * Get's all $brandings.
     *
     * @return mixed
     */
    public function all();

    /**
     * Updates a $branding.
     *
     * @param $request
     */
    public function update($request);



    /**
     * Store a User
     * @param $name
     * @param $email
     * @param $password
     */
    public function store($request);

}
