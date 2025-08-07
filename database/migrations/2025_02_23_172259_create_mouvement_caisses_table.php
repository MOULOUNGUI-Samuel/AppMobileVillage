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
        Schema::create('mouvements_caisses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ref_mouvement', 50);
            $table->uuid('caisse_id')->nullable();
            $table->uuid('client_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->uuid('reservation_id')->nullable();
            $table->uuid('caution_id')->nullable();
            $table->text('nature_mouvement')->nullable();
            $table->enum('type_mouvement', ['ENTREE', 'SORTIE']);
            $table->enum('mode_paiement', ['carte', 'espèces', 'virement']);
            $table->string('sum_solde', 23)->nullable();
            $table->integer('montant');
            $table->integer('montant_base');
            $table->integer('nouveau_montant');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('caisse_id')->references('id')->on('caisses')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
            $table->foreign('caution_id')->references('id')->on('cautions')->onDelete('cascade');
        });
    }
    public function down() {
        Schema::dropIfExists('mouvements_caisses');
    }
};
