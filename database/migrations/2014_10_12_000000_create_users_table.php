<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->bigInteger('phone')->nullable();
            $table->enum('role', ['user', 'vendor', 'admin'])->default('user');
            $table->string('location')->nullable();
            $table->string('about_me')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->string('business_name')->nullable();
            $table->string('business_address')->nullable();
            $table->string('gst_number')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
