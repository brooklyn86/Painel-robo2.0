<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatedataorderProcessoSituacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('processo_situacao', function (Blueprint $table) {
            $table->date('data') // Nome da coluna
            ->nullable() // Preenchimento não obrigatório
            ->after('password'); // Ordenado após a coluna "password"
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('processo_situacao', function (Blueprint $table) {
            $table->dropColumn('situacao');
        });
    }
}
