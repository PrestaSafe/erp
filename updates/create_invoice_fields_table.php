<?php namespace Prestasafe\Erp\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateInvoiceFieldsTable extends Migration
{
    public function up()
    {
        Schema::create('prestasafe_erp_invoice_fields', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('id_invoice');
            $table->text('reference');
            $table->text('description');
            $table->integer('quantity');
            $table->float('price_ht', 11,2)->default(0.00);
            $table->integer('tax_id');
            $table->float('price_ttc', 11,2)->default(0.00);
            $table->float('remise', 11,2)->default(0.00);
            $table->float('total_ht', 11,2)->default(0.00);
            $table->float('total_ttc', 11,2)->default(0.00);
        });
    }

    public function down()
    {
        Schema::dropIfExists('prestasafe_erp_invoice_fields');
    }
}
