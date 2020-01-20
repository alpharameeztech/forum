<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('shop_id')->unsigned();
            $table->string('shop_name');
            $table->bigInteger('shopify_billing_id')->unsigned()->nullable();
            $table->float('price')->nullable();
            $table->enum('type', ['app', 'feature'])->default('app');
            $table->enum('status', ['pending', 'accepted', 'declined', 'active', 'expired', 'frozen', 'cancelled']);
            $table->dateTime('activated_on')->nullable();
            $table->integer('trial_days')->nullable();
            $table->dateTime('billing_on')->nullable();
            $table->boolean('refunded')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('billings');
    }
}
