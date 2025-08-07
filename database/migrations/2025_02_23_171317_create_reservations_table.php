<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('client_id');
            $table->uuid('user_id');
            $table->uuid('salle_id');
            $table->string('ref_quitance');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('montant_total');
            $table->integer('montant_quitance');
            $table->integer('montant_payer');
            $table->integer('montant_reduction');
            $table->enum('statut', ['confirmée', 'annulée', 'en attente', 'En cours', 'Terminée']);
            $table->boolean('statut_valider')->default(0);
            $table->text('description_rejet')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('salle_id')->references('id')->on('salles')->onDelete('cascade');
        });
    }
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
};
