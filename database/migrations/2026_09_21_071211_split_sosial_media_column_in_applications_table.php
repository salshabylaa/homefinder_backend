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
        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn('sosial_media');
            $table->string('sosial_media_ig')->nullable()->after('nomor_wa');
            $table->string('sosial_media_tiktok')->nullable()->after('sosial_media_ig');
            $table->string('sosial_media_linkedin')->nullable()->after('sosial_media_tiktok');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('sosial_media')->nullable()->after('nomor_wa');
            $table->dropColumn(['sosial_media_ig', 'sosial_media_tiktok', 'sosial_media_linkedin']);
        });
    }
};
