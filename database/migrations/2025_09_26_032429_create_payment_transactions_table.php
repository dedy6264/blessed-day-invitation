<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            $table->foreignId('payment_method_id')->constrained()->onDelete('cascade');
            $table->string('payment_method_name', 50)->nullable();                 // duplikasi untuk log cepat
            $table->decimal('provider_admin_fee', 12, 2)->default(0.00);
            $table->decimal('provider_merchant_fee', 12, 2)->default(0.00);
            $table->decimal('admin_fee', 12, 2)->default(0.00);
            $table->decimal('merchant_fee', 12, 2)->default(0.00);
            $table->string('status_code', 20)->nullable();                         // kode dari payment gateway
            $table->string('status_message', 200)->nullable();                     // pesan dari payment gateway
            $table->string('payment_other_reff', 200)->nullable();                 // reference dari provider (VA, trx id, dsb)
            $table->timestamps(); // This will create created_at and updated_at with proper defaults for SQLite
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_transactions');
    }
}
