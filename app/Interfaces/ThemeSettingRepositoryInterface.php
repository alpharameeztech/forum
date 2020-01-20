<?php

namespace App\Interfaces;

interface ThemeSettingRepositoryInterface
{
    /**
     * Get's a $themeSetting by it's ID
     *
     * @param int
     */
    public function get();

    /**
     * Get's all $themesettings.
     *
     * @return mixed
     */
    public function all();

    /**
     * Updates a $themesetting.
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
