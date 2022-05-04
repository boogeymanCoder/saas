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
        Schema::create('grading_system_categories', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->decimal("percentage");
            $table->unsignedBigInteger('grading_system_id');
            $table->foreign('grading_system_id')->references('id')->on('grading_systems')->onDelete("cascade");
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
        Schema::dropIfExists('grading_system_categories');
    }
};
