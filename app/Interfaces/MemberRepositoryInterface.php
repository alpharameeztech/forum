<?php

namespace App\Interfaces;

interface MemberRepositoryInterface
{
    /**
     * Get's a $member by it's ID
     *
     * @param int
     */
    public function get($member_id);

    /**
     * Get's all $members.
     *
     * @return mixed
     */
    public function all();

    /**
     * Updates a $member.
     *
     * @param $id
     * @param $name
     * @param $password
     * @param $email
     * @param $ban
     */
    public function update($id, $name, $password, $email, $ban);

    /**
     * Ban a member
     * $param $member_id
     */
    public function ban($memberId, $boolean);

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
