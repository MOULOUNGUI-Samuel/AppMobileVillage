<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up() {
        Schema::create('annees', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('annee');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('annees');
    }
};
