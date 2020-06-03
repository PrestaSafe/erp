<?php namespace Prestasafe\Erp\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateQuotesTable extends Migration
{
    public function up()
    {
        Schema::create('prestasafe_erp_quotes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fake_id')->nullable();
            $table->text('infos_company');
            $table->text('infos_client')->nullable();
            $table->integer('id_customer');
            $table->integer('id_currency')->default(1);
            $table->string('objet', 1000);
            $table->timestamps();
            $table->integer('id_invoice')->default(0);
            $table->string('date_display', 255);
            $table->integer('active')->default(1);
            $table->integer('id_user')->default(1);
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestasafe_erp_quotes');
    }

}
