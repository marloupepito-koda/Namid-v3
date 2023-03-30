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
         Schema::create('ticket_bags', function (Blueprint $table) {
            $table->id();
            $table->string('unitid')->nullable();
            $table->string('eventid')->nullable();
            $table->string('ticketid')->nullable();
            $table->string('ticket_type')->nullable();
            $table->string('bagid')->nullable();
            $table->string('seller')->nullable();
            $table->string('bind')->nullable();
            $table->string('price')->nullable();
            $table->string('start')->nullable();
            $table->string('end')->nullable();
            $table->string('count')->nullable();
            $table->string('quantity')->nullable();
            $table->string('status')->nullable();
            $table->string('date')->nullable();
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
        Schema::dropIfExists('ticket_bags');
    }
};
