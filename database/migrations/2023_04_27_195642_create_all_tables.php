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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('password', 100);
            $table->string('hour_entry', 20);
            $table->string('hour_pause', 20);
            $table->string('hour_return', 20);
            $table->string('hour_exit', 20);
            $table->string('isAdmin',1)->default('0');
            $table->string('token', 200)->nullable();
        });

        Schema::create('register_date', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->date('date_register');
        });

        Schema::create('register_hour', function (Blueprint $table) {
            $table->id();
            $table->time('hour');
            $table->integer('id_register_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('register_hour');
        Schema::dropIfExists('register_date');
    }
};
