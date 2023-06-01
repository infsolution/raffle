<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rafles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->date('drawn_date')->nullable();
            $table->integer('value_point');
            $table->integer('number_point');
            $table->enum('format', ['traditional', 'lottery']);
            $table->boolean('drawn')->default(false);
            $table->timestamps();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rafles');
    }
};
