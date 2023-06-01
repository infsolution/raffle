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
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->integer("number");
            $table->string("name_client")->nullable();
            $table->string("phone_client")->nullable();
            $table->boolean('paid')->default(false);
            $table->boolean('drawn')->default(false);
            $table->boolean('bought')->default(false);
            $table->integer("value_paid")->nullable();
            $table->timestamps();
            $table->bigInteger('rafle_id')->unsigned();
            $table->foreign('rafle_id')->references('id')->on('rafles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
