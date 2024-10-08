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
        Schema::table('category', function (Blueprint $table) {
            if (!Schema::hasColumn('category', 'productID')) {
             $table->unsignedBigInteger('productID')->nullable();
             $table->foreign('productID')->references('id')->on('product')->onDelete('cascade');
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
