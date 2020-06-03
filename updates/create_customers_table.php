<?php namespace Prestasafe\Erp\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('prestasafe_erp_customers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
            $table->string('name', 255);
            $table->string('mail', 1055);
            $table->text('adresse');
            $table->string('tel', 255);
            $table->string('fax', 255);
            $table->integer('active')->default(1);
            $table->integer('id_user')->default(1);

        });
    }

    public function down()
    {
        Schema::dropIfExists('prestasafe_erp_customers');
    }
}
