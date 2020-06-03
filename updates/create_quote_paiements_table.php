<?php namespace Prestasafe\Erp\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateQuotePaiementsTable extends Migration
{
    public function up()
    {
        Schema::create('prestasafe_erp_quote_paiements', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('id_quote');
            $table->integer('type_id');
            $table->string('date', 255);
            $table->string('montant', 1055);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestasafe_erp_quote_paiements');
    }
}
