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
        Schema::create('transaction', function (Blueprint $table) {
            $table->string('id', 50)->primary();
            $table->string('id_user', 50);
            $table->string('to_user', 50)->nullable();
            $table->string('id_category', 50)->nullable();
            $table->bigInteger('value');
            $table->text('note');
            $table->timestamps();

            $table->foreign("id_user")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("to_user")->references("id")->on("users")->onDelete("set null");
            $table->foreign("id_category")->references("id")->on("category")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction');
    }
};
