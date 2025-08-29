<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infraction', function (Blueprint $table) {
            $table->id();
            $table->string('nom',100);
            $table->date('date');
            $table->string('adresse',255);
            $table->foreignId('commune_id')->constrained('commune')->cascadeOnDelete();
            $table->foreignId('violant_id')->constrained('violant')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('agent')->cascadeOnDelete() ;
            $table->foreignId('categorie_id')->constrained('categorie')->cascadeOnDelete();
            $table->double('latitude');
            $table->double('longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infraction');
    }
};
