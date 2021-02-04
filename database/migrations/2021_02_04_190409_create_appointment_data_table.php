<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_data', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')
                ->references('id')
                ->on('clients')
                ->onDelete('cascade');

            $table->foreignId('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('cascade');

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
        Schema::dropIfExists('appointment_data');
    }
}
