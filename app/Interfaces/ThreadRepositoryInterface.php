<?php

namespace App\Interfaces;

interface ThreadRepositoryInterface
{
    /**
     * Get's a $Thread by it's ID
     *
     * @param int
     */
    public function get($thread_id);

    /**
     * Get's all $Threads.
     *
     * @return mixed
     */
    public function all();

    /**
     * Updates a $Thread.
     *
     * @param int
     * @param array
     */
    public function update($thread_data);

    /**
     * Ban a Thread
     * $param $thread_id
     */
    public function ban($thread_id, $boolean);

    /**
     * When called with any
     * query parameter,
     * the filter will applied
     * @return mixed
     */
    public function filter($query);

}
