<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method_name');  // e.g. bank_transfer, gopay, credit_card
            $table->text('description')->nullable();
            $table->decimal('provider_admin_fee', 12, 2)->default(0.00);   // biaya dari provider ke admin
            $table->decimal('provider_merchant_fee', 12, 2)->default(0.00);  // biaya dari provider ke merchant
            $table->decimal('admin_fee', 12, 2)->default(0.00);            // biaya admin internal
            $table->decimal('merchant_fee', 12, 2)->default(0.00);         // biaya merchant internal
            $table->string('m_key', 255)->nullable();                      // API/merchant key
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
        Schema::dropIfExists('payment_methods');
    }
}
