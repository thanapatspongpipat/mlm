<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->nullable();
            $table->string('username', 255)->nullable();
            $table->string('firstname', 255)->nullable();
            $table->string('lastname', 255)->nullable();
            $table->string('on_card', 255)->nullable();
            $table->date('dob')->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone_number', 255)->nullable();
            $table->string('line', 255)->nullable();
            $table->string('fb', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('zip_code', 255)->nullable();
            $table->string('send_address', 255)->nullable();
            $table->string('send_zip_code', 255)->nullable();
            $table->integer('user_invite_id')->comment("คนแนะนำ")->nullable();
            $table->integer('user_upline_id')->nullable();
            $table->enum('position_space', ['left', 'right'])->comment("ตำแหน่วงว่าง");
            $table->string("bank_id", 255)->nullable();
            $table->string("bank_no", 255)->nullable()->comment("เลขบัญชี");
            $table->string("bank_own_name", 255)->nullable()->comment("ชื่อบัญชีผู้ใช้");
            $table->string('level', 255)->nullable()->comment('ตำแหน่ง');
            $table->timestamp("email_verified_at")->nullable();
            $table->string('password', 255)->nullable();
            $table->string('avatar', 255)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
            $table->string('district', 255)->nullable()->comment("เขต/อำเภอ");
            $table->string('sub_district', 255)->nullable()->comment("แขวง/ตำบล");
            $table->string('ig', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
