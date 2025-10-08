<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameBankAccountsToGifts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Make existing columns nullable
        Schema::table('bank_accounts', function (Blueprint $table) {
            $table->string('bank_name')->nullable()->change();
            $table->string('account_number')->nullable()->change();
            $table->string('account_holder_name')->nullable()->change();
        });

        // Rename the table from bank_accounts to gifts
        Schema::rename('bank_accounts', 'gifts');

        // Add new columns
        Schema::table('gifts', function (Blueprint $table) {
            $table->string('gift_type')->default('gift'); // Options: 'gift' or 'support'
            $table->text('gift_description')->nullable();
        });

        // Drop the is_active column
        Schema::table('gifts', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add back the is_active column
        Schema::table('gifts', function (Blueprint $table) {
            $table->boolean('is_active')->default(true);
        });

        // Drop new columns
        Schema::table('gifts', function (Blueprint $table) {
            $table->dropColumn(['gift_type', 'gift_description']);
        });

        // Make the existing columns non-nullable again
        Schema::table('gifts', function (Blueprint $table) {
            $table->string('bank_name')->nullable(false)->change();
            $table->string('account_number')->nullable(false)->change();
            $table->string('account_holder_name')->nullable(false)->change();
        });

        // Rename the table back to bank_accounts
        Schema::rename('gifts', 'bank_accounts');
    }
}
