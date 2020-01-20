<?php

use Illuminate\Database\Seeder;

class PlansTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Starter Monthly
        $this->saveRecord(
            'starter',
            49,
            'monthly'
        );

        //Starter yearly
        $this->saveRecord(
            'starter',
            490,
            'yearly'
        );

        //Premium Monthly
        $this->saveRecord(
            'premium',
            59,
            'monthly'

        );
        //Premium Yearly
        $this->saveRecord(
            'premium',
            590,
            'yearly'

        );

        //Enterprise Monthly
        $this->saveRecord(
            'enterprise',
            69,
            'monthly'

        );
        //Enterprise Yearly
        $this->saveRecord(
            'enterprise',
            690,
            'yearly'

        );


    }

    public function saveRecord($name, $price, $type)
    {
        $plan = App\Plan::where('name', $name)
                            ->where('type', $type)->first();

        if (empty($plan)) // this plan not created yet then create it
        {
            $plan = new \App\Plan;

            $plan->name = $name;

            $plan->price = $price;

            $plan->type = $type;

            $plan->save();
        } else {
            //update the plan
            $plan->name = $name;

            $plan->price = $price;

            $plan->type = $type;

            $plan->save();
        }


    }
}
