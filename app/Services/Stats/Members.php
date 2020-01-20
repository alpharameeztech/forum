<?php

namespace App\Services\Stats;


use App\User;
use Illuminate\Support\Facades\Cache;

class Members
{

    static public  function count()
    {
        return User::where('type','member')
                    ->where('shop_id', Cache::get('shop_id'))
                    ->count();
    }

    static public function top()
    {

        $members = User::with('forumInfo')
                ->where('type', 'member')
                ->where('shop_id', Cache::get('shop_id'))
                ->get();

        $index = 0;
        foreach ($members as $member)
        {

            if( !empty($member->forumInfo) ){ // if the user experience exist

                $member['experience'] = $member->forumInfo->experience ;

                unset($member->forumInfo);
                unset($member->id);
                unset($member->email);

            }else{

                $member['experience'] = 0;
                unset($members[$index]);

            }
            $index++;

        }

        $members = collect($members);

        $sorted = $members->sortByDesc('experience');


        return $sorted->values()->slice(0, 5)->all();


    }

    static public function recent()
    {

        $member = User::where('type', 'member')
            ->where('shop_id', Cache::get('shop_id'))
                ->latest()->first();

        unset($member->id);
        unset($member->email);

        return $member;
    }

}
