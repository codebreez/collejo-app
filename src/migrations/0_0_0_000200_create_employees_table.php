<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->string('id', 45)->primary();
            $table->string('user_id', 45)->unique();
            $table->string('admission_number', 45)->unique();
            $table->timestamp('joined_on');
            $table->string('employee_category_id', 45);
            $table->string('employee_position_id', 45);
            $table->string('employee_department_id', 45);
            $table->string('employee_grade_id', 45);
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('employee_category_id')->references('id')->on('employee_categories');
            $table->foreign('employee_position_id')->references('id')->on('employee_positions');
            $table->foreign('employee_department_id')->references('id')->on('employee_departments');
            $table->foreign('employee_grade_id')->references('id')->on('employee_grades');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('employees');
    }
}
