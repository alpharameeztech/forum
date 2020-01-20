<?php

namespace App\Interfaces;

interface PublisherRepositoryInterface
{
    /**
     * Get's a $Publisher by it's ID
     *
     * @param int
     */
    public function get($publisher_id);

    /**
     * Get's all $Publishers.
     *
     * @return mixed
     */
    public function all();

    /**
     * Updates a $Publisher.
     *
     * @param $id
     * @param $name
     * @param $password
     * @param $email
     * @param $ban
     */
    public function update($id, $ban);

    /**
     * Ban a Publisher
     * $param $Publisher_id
     */
    public function ban($publisher_id, $boolean);

    /**
     * Store a User
     * @param $name
     * @param $email
     * @param $password
     */
    public function store($name, $email, $password);

    /**
     * When called with any
     * query parameter,
     * the filter will applied
     * @return mixed
     */
    public function filter($query);
}
