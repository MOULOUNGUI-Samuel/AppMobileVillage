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
        Schema::create('salles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nom');
            $table->integer('capacite')->nullable();
            $table->text('equipements')->nullable();
            $table->integer('montant_base')->nullable();
            $table->integer('caution')->nullable();
            $table->uuid('entreprise_id');
            $table->boolean('statut');
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
            $table->timestamps();
        });
    }
    public function down() {
        Schema::dropIfExists('salles');
    }
};
