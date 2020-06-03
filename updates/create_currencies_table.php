<?php namespace Prestasafe\Erp\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Prestasafe\Erp\Models\Currency;

class CreateCurrenciesTable extends Migration
{
    public function up()
    {
        Schema::create('prestasafe_erp_currencies', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 30);
            $table->string('sign', 30);
            $table->integer('id_user')->nullable();
            $table->boolean('default')->default(false);
            $table->nullableTimestamps();
            $table->integer('active')->nullable()->default(1);
        });
        $c = new Currency;
        $c->name = 'Euro';
        $c->sign = '€';
        $c->default = true;
        $c->save();
    }

    public function down()
    {
        Schema::dropIfExists('prestasafe_erp_currencies');
    }
}
