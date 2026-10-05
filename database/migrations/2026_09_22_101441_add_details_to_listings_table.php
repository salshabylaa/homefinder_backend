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
        Schema::table('listings', function (Blueprint $table) {
            $table->string('category')->nullable();
            $table->string('badge')->nullable();
            $table->boolean('is_rent')->default(false);
            $table->bigInteger('price')->nullable();
            $table->string('price_formatted')->nullable();
            $table->string('location')->nullable();
            $table->string('city')->nullable();
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('area')->nullable();
            $table->string('legal_status')->nullable();
            $table->string('electricity')->nullable();
            $table->string('garage')->nullable();
            $table->json('features')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn([
                'category', 'badge', 'is_rent', 'price', 'price_formatted', 
                'location', 'city', 'bedrooms', 'bathrooms', 'area', 
                'legal_status', 'electricity', 'garage', 'features'
            ]);
        });
    }
};
