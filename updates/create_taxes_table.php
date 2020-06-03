<?php namespace Prestasafe\Erp\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Prestasafe\Erp\Models\Tax;
class CreateTaxesTable extends Migration
{
    public function up()
    {
        Schema::create('prestasafe_erp_taxes', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name', 255);
            $table->decimal('rate', 10, 3);
            $table->boolean('default')->default(false);
            $table->nullableTimestamps();
            $table->integer('active')->default(1);
        });
        
        $c = new Tax;
        $c->name = 'TVA 20%';
        $c->rate = 20.00;
        $c->default = true;
        $c->save();

        $c = new Tax;
        $c->name = 'Aucune';
        $c->rate = 0.00;
        $c->save();
    
    }



    public function down()
    {
        Schema::dropIfExists('prestasafe_erp_taxes');
    }
}
