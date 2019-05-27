<?php

namespace App\Providers;

use App\Notifications\NewSale;
use App\Plan;
use App\Tasks\CustomUUID;
use App\Tasks\UserCountry;
use App\User;
use Carbon\Carbon;
use Laravel\Spark\Providers\AppServiceProvider as ServiceProvider;
use Laravel\Spark\Spark;
use Notification;

class SparkServiceProvider extends ServiceProvider
{
    /**
     * Your application and company details.
     *
     * @var array
     */
    protected $details = [
        'vendor' => 'Global Real Estate Licence',
        'product' => 'Your Product',
        // 'street' => 'PO Box 111',
        'location' => '228 Hamilton Ave, 3rd Flr, Palo Alto, California, 94301.USA',
        'phone' => '+1-650-535-1200',
    ];

    /**
     * The address where customer support e-mails should be sent.
     *
     * @var string
     */
    protected $sendSupportEmailsTo = null;

    /**
     * All of the application developer e-mail addresses.
     *
     * @var array
     */
    protected $developers = [
        'shahlajalalicoo@gmail.com',
        'tariqkhursheedceo@gmail.com',
        'rameezisrarcode@gmail.com',
    ];

    /**
     * Indicates if the application will expose an API.
     *
     * @var bool
     */
    protected $usesApi = false;

    /**
     * Finish configuring Spark for the application.
     *
     * @return void
     */
    public function booted()
    {
        //Spark::promotion('zone1');

        // adding a custom uuid field in the user table
        //assing it to the UUID library
        Spark::createUsersWith(function ($request) {

            $uuid = CustomUUID::get();

            $user = Spark::user();

            $data = $request->all();

            // get the customer country
            $country_name = UserCountry::name();
            // get the customer country

            $user->forceFill([
                'name' => $data['name'],
                'email' => $data['email'],
                'country' => $country_name,
                'uuid' => $uuid,
                'password' => bcrypt($data['password']),
                'last_read_announcements_at' => Carbon::now(),
                'trial_ends_at' => Carbon::now()->addDays(Spark::trialDays()),
            ])->save();

            // Notification::route('mail', ['mubbuco@gmail.com', 'sarah@globalstaging.org', 'tariq@designationhub.com', 'shahla@designationhub.com', 'qasim@designationhub.com', 'developernewton02@gmail.com', 'rama@grel.org', 'danny@grel.org', 'database.hitman@gmail.com'])
            //     ->notify(new NewSale($user));

            return $user;
        });
        // adding a custom uuid field in the user table ended

        // Spark::useStripe()->noCardUpFront()->trialDays(0);
        Spark::useStripe();
        // Spark::collectBillingAddress();

        $plans = Plan::where('publish', 1)->get();

        foreach ($plans as $plan) {

            if ($plan->type == "monthly") {

                Spark::plan($plan->name, $plan->plan_id)
                    ->trialDays($plan->trial_days)
                    ->price($plan->price);

            }

        }

        foreach ($plans as $plan) {

            if ($plan->type == "yearly") {

                Spark::plan($plan->name, $plan->plan_id)
                    ->trialDays($plan->trial_days)
                    ->price($plan->price)
                    ->yearly();

            }

        }

    }
}
