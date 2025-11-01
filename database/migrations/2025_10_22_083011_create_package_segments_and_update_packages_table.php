<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageSegmentsAndUpdatePackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create the package_segments table
        Schema::create('package_segments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id'); // Foreign key to packages table
            $table->decimal('price', 12, 2)->default(0.00);
            $table->string('status', 20)->nullable();
            $table->integer('period'); // masa aktif dalam hari
            $table->timestamps();
            
            // Add foreign key constraint
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });

        // Drop the price and period columns from the packages table
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['price', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Recreate price and period columns in packages table
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('price', 12, 2);
            $table->integer('period'); // masa aktif dalam hari
        });

        // Drop the package_segments table
        Schema::dropIfExists('package_segments');
    }
}
