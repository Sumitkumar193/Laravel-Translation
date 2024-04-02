<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDoctorExpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doctor_exps', function (Blueprint $table) {
            $table->id();
            $table->string('hospital_name');
            $table->string('designation');
            $table->string('from');
            $table->string('to');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('cascade');
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
        Schema::dropIfExists('doctor_exps');
    }
}
