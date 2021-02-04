<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('start_time');
            $table->string('end_time');
            $table->date('date')->index();

            $table->foreignId('trainer_id')
                ->references('id')
                ->on('trainers')
                ->onDelete('cascade');

            $table->foreignId('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->string('users_list')
                ->index()
                ->comment('Users list in |{id1}|{id2}|...| format to improve the speed of lookups');

            $table->softDeletes();
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
        Schema::dropIfExists('appointments');
    }
}
