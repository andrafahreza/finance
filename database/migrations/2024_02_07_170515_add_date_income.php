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
        Schema::table('transaction', function (Blueprint $table) {
            $table->dateTime('date')->after('note');
        });

        Schema::table('income', function (Blueprint $table) {
            $table->dateTime('date')->after('note');
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
