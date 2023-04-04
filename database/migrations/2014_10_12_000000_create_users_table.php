<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

      
        Schema::create('event_bags', function (Blueprint $table) {
            $table->id();
            $table->string('unitid')->nullable();
            $table->string('eventid')->nullable();
            $table->string('bag_name')->nullable();
            $table->string('seller')->nullable();
            $table->string('location')->nullable();
            $table->string('remaining')->nullable();
            $table->string('status')->nullable();
            $table->string('returned')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();
        });


        Schema::create('event_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('unitid')->nullable();
            $table->string('eventid')->nullable();
            $table->string('ticket_type')->nullable();
            $table->string('ticket_name')->nullable();
            $table->string('price')->nullable();
            $table->string('count')->nullable();
            $table->string('bind')->nullable();
            $table->string('quantity')->nullable();
            $table->string('start')->nullable();
            $table->string('end')->nullable();
            $table->string('remaining')->nullable();
            $table->string('status')->nullable();
            $table->string('date')->nullable();
            $table->timestamps();
        });

        Schema::create('unit_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('unitid')->nullable();
            $table->string('ticket_type')->nullable();
            $table->string('ticket_name')->nullable();
            $table->string('count')->nullable();
            $table->string('bind')->nullable();
            $table->string('quantity')->nullable();
            $table->string('start')->nullable();
            $table->string('end')->nullable();
            $table->string('date')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });


         Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('unitid')->nullable();
            //$table->foreign('unitid')->references('client_id')->on('ee_clients')->onUpdate('cascade')->onDelete('cascade');
            $table->string('events_name')->nullable();
            $table->string('events_address')->nullable();
            $table->string('start')->nullable();
            $table->string('end')->nullable();
            $table->string('year')->nullable();
            $table->timestamps();
        });


         Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->string('units_name')->nullable();
            $table->string('units_shortname')->nullable();
            $table->string('units_fullname')->nullable();
            $table->string('units_code')->nullable();
            $table->string('units_status')->nullable();
            $table->string('year')->nullable();
            $table->timestamps();
        });


        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('unitid')->nullable();
            //$table->foreign('unitid')->references('client_id')->on('ee_clients')->onUpdate('cascade')->onDelete('cascade');
            $table->string('eventid')->nullable();
            $table->string('username')->nullable();
            $table->string('unitname')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();
            $table->string('usertype')->nullable();
            $table->string('year')->nullable();
            $table->string('access1')->nullable();
            $table->string('access2')->nullable();
            $table->string('access3')->nullable();
            $table->string('access4')->nullable();
            $table->string('access5')->nullable();
            $table->string('access6')->nullable();
            $table->string('access7')->nullable();
            $table->string('access8')->nullable();
            $table->string('access9')->nullable();
            $table->string('access10')->nullable();
            $table->string('access11')->nullable();
            $table->string('access12')->nullable();
            $table->string('access13')->nullable();
            $table->string('access14')->nullable();
            $table->string('access15')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
