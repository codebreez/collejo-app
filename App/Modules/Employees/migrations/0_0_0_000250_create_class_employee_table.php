<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateClassEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_employee', function (Blueprint $table) {
            $table->string('id', 45)->primary();
            $table->string('class_id', 45);
            $table->string('employee_id', 45);
            $table->string('batch_id', 45);
            $table->string('created_by')->nullable();
            $table->timestamp('created_at');
        });

        Schema::table('class_employee', function (Blueprint $table) {
            $table->foreign('class_id')->references('id')->on('classes');
            $table->foreign('batch_id')->references('id')->on('batches');
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('class_employee');
    }
}
