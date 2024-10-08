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
        
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string("img1")->nullable();
            $table->string("img2")->nullable();
            $table->string("img3")->nullable();
            $table->string("img4")->nullable();
            $table->foreignId("productID")->references('id')->on('product')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
