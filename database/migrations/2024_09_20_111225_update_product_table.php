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
        Schema::table('product', function (Blueprint $table) {
         
           if (!Schema::hasColumn('category', 'categoryID')) {
            $table->unsignedBigInteger('categoryID')->nullable();
            $table->foreign('categoryID')->references('id')->on('category')->onDelete('cascade');
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
