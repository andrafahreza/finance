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
        Schema::table('income', function (Blueprint $table) {
            $table->string('id_source', 50)->nullable()->change();
        });

        Schema::table('transaction', function (Blueprint $table) {
            $table->string('id_income', 50)->nullable()->after('date');

            $table->foreign("id_income")->references("id")->on("income")->onDelete("cascade");
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
