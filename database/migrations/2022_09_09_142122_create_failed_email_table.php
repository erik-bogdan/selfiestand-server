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
        Schema::create('failed_emails', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('uuid', 255);
            $table->string('email', 255);
            $table->boolean('is_success')->default(false);
            $table->integer('attempts');
            $table->dateTime('first_attempt');
            $table->dateTime('last_attempt')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('failed_emails');
    }
};
