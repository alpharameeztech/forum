<?php

namespace App\Interfaces;

interface ChannelRepositoryInterface
{
    /**
     * Get's a $channel by it's ID
     *
     * @param int
     */
    public function get($channel_id);

    /**
     * Get's all $channels.
     *
     * @return mixed
     */
    public function all();

    /**
     * Updates a $channel.
     *
     * @param int
     * @param array
     */
    public function update($channel_data);

    /**
     * Store a $channel.
     *
     * @param obj
     *
     */
    public function store($channel_data);
}
