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
        Schema::table('items', function (Blueprint $table) {
            $table->integer('min_temperature')->nullable();
            $table->integer('max_temperature')->nullable();
            $table->integer('min_uv_index')->nullable();
            $table->integer('max_uv_index')->nullable();
            $table->unsignedInteger('cloud_cover_threshold')->nullable();

            $table->integer('min_temperature_offset')->default(0);
            $table->integer('max_temperature_offset')->default(0);

            $table->boolean('is_waterproof')->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('min_temperature');
            $table->dropColumn('max_temperature');
            $table->dropColumn('min_uv_index');
            $table->dropColumn('max_uv_index');
            $table->dropColumn('cloud_cover_threshold');

            $table->dropColumn('min_temperature_offset');
            $table->dropColumn('max_temperature_offset');
        });
    }
};
