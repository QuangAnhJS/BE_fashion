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
         
           if (!Schema::hasColumn('product', 'ImgID')) {
            $table->unsignedBigInteger('ImgID')->nullable();
            $table->foreign('ImgID')->references('id')->on('images')->onDelete('cascade');
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
