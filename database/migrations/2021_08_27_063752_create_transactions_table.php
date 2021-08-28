<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer("user_id")->comment("user");
            $table->decimal('amount', $precision = 20, $scale = 2)->comment("จำนวนเงิน");
            $table->decimal('balance', $precision = 20, $scale = 2)->comment("ยอดคงเหลือ");
            $table->string("type", 255)->comment("DEPOSIT_* , WITHDRAW");
            $table->integer("fk_id")->comment("ค่า ID สำหรับอ้างอิง (MLM)")->nullable();
            $table->string('detail', 255)->nullable()->comment("รายละเอียด");
            $table->timestamp("transaction_timestamp")->comment("วันที่ทำรายการ");
            $table->timestamps();
            $table->timestamp("deleted_at")->nullable();
            $table->integer("user_approve_id")->comment("คนยืนยัน");
            $table->integer("user_create_id")->comment("คนสร้าง");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
