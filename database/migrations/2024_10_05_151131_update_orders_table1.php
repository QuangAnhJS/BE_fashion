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
        Schema::table('orders', function (Blueprint $table) {
         
            if (!Schema::hasColumn('orders', 'city','huyen','xa','chitiet')) {
             $table->string('city');
             $table->string('huyen');
             $table->string('xa');
             $table->string('chitiet');
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
