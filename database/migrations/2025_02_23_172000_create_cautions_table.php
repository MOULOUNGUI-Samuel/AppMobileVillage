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
        Schema::create('cautions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ref_caution', 50);
            $table->uuid('reservation_id');
            $table->integer('montant_caution');
            $table->integer('montant_rembourse');
            $table->enum('statut', ['confirmée', 'annulée', 'en attente', 'En cours', 'Terminée']);
            $table->timestamps();

            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('cautions');
    }
};
