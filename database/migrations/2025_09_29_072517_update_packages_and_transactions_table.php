<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePackagesAndTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add period column to packages table with nullable first, then copy data from duration_days
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('period')->nullable()->after('price');  // Add period column as nullable first
        });
        
        // Copy data from duration_days to period
        DB::statement('UPDATE packages SET period = duration_days');
        
        // Now make the period column NOT NULL
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('period')->nullable(false)->change();  // Make period NOT NULL
        });
        
        // Drop the duration_days column
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('duration_days');
        });
        
        // Add period and package_name columns to transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->integer('period')->after('package_id');  // Add period column
            $table->string('package_name', 100)->after('period');  // Add package_name column
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Add duration_days column back to packages table and copy data from period
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('duration_days')->after('price');  // Add duration_days column after price
        });
        
        // Copy data from period to duration_days
        DB::statement('UPDATE packages SET duration_days = period');
        
        // Drop the period column
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('period');
        });
        
        // Remove period and package_name columns from transactions table
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['package_name', 'period']);
        });
    }
}
