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
        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn(['name', 'slug', 'image_path', 'description', 'link']);
            $table->json('slides')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('blocks', function (Blueprint $table) {
            //
        });
    }
};
