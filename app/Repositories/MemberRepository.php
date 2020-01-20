<?php

namespace App\Repositories;

use App\Interfaces\MemberRepositoryInterface;
use App\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class MemberRepository implements MemberRepositoryInterface
{
    protected $shop_id = '';

    public function __construct()
    {
        $this->shop_id = Cache::get('shop_id');
    }

    /**
     * Get's a Member by it's ID
     *
     * @param int
     * @return collection
     */
    public function get($member_id)
    {

    }

    /**
     * Get's all Members.
     *
     * @return mixed
     */
    public function all()
    {
        $query =  User::where('type', 'member')
                        ->where('shop_id', $this->shop_id)
                        ->with('forumInfo');

        return $this->filter($query);
    }


    /**
     * Updates a Member.
     *
     * @param $id
     * @param $name
     * @param $pasword
     * @param $email
     * @param $ban
     */
    public function update($id, $name, $password, $email, $ban)
    {

    }

    /**
     * Ban a $member
     * @param $member_id
     * @param $boolean
     */
    public function ban($memberId, $boolean)
    {

        $member = User::find($memberId);

        $member->is_ban = $boolean;

        $member->save();

    }

    /**
     * Store a user
     * @param $user_data
     */
    public function store($name, $email, $password){


    }

    public function filter($query)
    {

        if( request()->ban === '1' || request()->ban === '0' )  {

            $members = $query->where('is_ban', request('ban'))->get();

            return $members;
        }
        else
        {
            return $query->get();
        }
    }

}
