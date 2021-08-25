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
            $table->string('name')->nullable();
            $table->string('username')->uniqid();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('on_card')->nullable();
            $table->date('dob')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('line')->nullable();
            $table->string('fb')->nullable();
            $table->string('address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('send_address')->nullable();
            $table->string('send_zip_code')->nullable();
            $table->integer('user_invite_id')->comment("คนแนะนำ")->nullable();
            $table->integer('user_upline_id')->nullable();
            $table->enum('position_space', ['left', 'right'])->comment("ตำแหน่วงว่าง");
            $table->string('bank_id')->nullable();
            $table->string('bank_no')->comment('เลขที่บันชี')->nullable();
            $table->string('bank_own_name')->comment('ชื่อบันชี ผู้ใช้')->nullable();
            $table->string('level')->comment('ตำแหน่ง')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('avatar')->nullable();

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
