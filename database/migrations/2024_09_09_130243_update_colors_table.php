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
        Schema::table('colors', function (Blueprint $table) {
         
            if (!Schema::hasColumn('colors', 'userID')) {
             $table->unsignedBigInteger('userID')->nullable();
             $table->foreign('userID')->references('id')->on('product')->onDelete('cascade');
         }
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
