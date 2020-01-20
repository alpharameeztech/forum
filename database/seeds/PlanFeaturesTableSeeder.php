<?php

use Illuminate\Database\Seeder;

class PlanFeaturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $plans = App\Plan::get();

        //starter monthly features
        $this->saveRecord(
            $plans[0]->id,
            5,
            0,
            0,
            100,
            0,
            0,
            0
        );
        //starter yearly features
        $this->saveRecord(
            $plans[1]->id,
            5,
            0,
            0,
            100,
            0,
            0,
            0
        );

        //premium monthly features
        $this->saveRecord(
            $plans[2]->id,
            10,
            0,
            101,
            1000,
            0,
            1,
            1
        );
        //premium yearly features
        $this->saveRecord(
            $plans[3]->id,
            10,
            0,
            101,
            1000,
            0,
            1,
            1
        );

        //enterprise monthly features
        $this->saveRecord(
            $plans[4]->id,
            1000,
            1,
            1000, //
            100000,
            1,
            1,
            1
        );
        //enterprise yearly features
        $this->saveRecord(
            $plans[5]->id,
            1000,
            1,
            1000, //
            100000,
            1,
            1,
            1
        );

    }

    public function saveRecord(
        $plan_id,
        $team_users,
        $unlimited_team_users,
        $min_members,
        $max_members,
        $unlimited_members,
        $custom_css,
        $custom_js
    )
    {
        $plan_feature = App\PlanFeature::where('plan_id', $plan_id)->first();

        if (empty($plan_feature)) {

            $plan_feature = new \App\PlanFeature;

            $plan_feature->plan_id = $plan_id;

            $plan_feature->team_users = $team_users;

            $plan_feature->unlimited_team_users = $unlimited_team_users;

            $plan_feature->min_members = $min_members;

            $plan_feature->max_members = $max_members;

            $plan_feature->unlimited_members = $unlimited_members;

            $plan_feature->custom_css = $custom_css;

            $plan_feature->custom_js = $custom_js;

            $plan_feature->save();
        } else {

            $plan_feature->plan_id = $plan_id;

            $plan_feature->team_users = $team_users;

            $plan_feature->unlimited_team_users = $unlimited_team_users;

            $plan_feature->min_members = $min_members;

            $plan_feature->max_members = $max_members;

            $plan_feature->unlimited_members = $unlimited_members;

            $plan_feature->custom_css = $custom_css;

            $plan_feature->custom_js = $custom_js;

            $plan_feature->save();
        }


    }
}
