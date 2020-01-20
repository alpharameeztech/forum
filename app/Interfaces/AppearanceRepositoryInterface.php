<?php

namespace App\Interfaces;

interface AppearanceRepositoryInterface
{
    /**
     * Get's shop appearance
     *
     * @param int
     */
    public function get();

    /**
     * Updates a $shop appearance.
     *
     * @param $request
     */
    public function update($request);

    /**
     * Store a shop appearance.
     * @param $request
     */
    public function store($request);
}
