<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('approba_caisses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('caisse_id');
            $table->uuid('entreprise_id');
            $table->uuid('user_id');
            $table->text('observation');
            $table->enum('statut', ['en_attente', 'valide', 'rejete']);
            $table->timestamps();
            $table->foreign('caisse_id')->references('id')->on('caisses')->onDelete('cascade');
            $table->foreign('entreprise_id')->references('id')->on('entreprises')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approba_caisses');
    }
};
