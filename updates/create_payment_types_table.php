<?php namespace Prestasafe\Erp\Updates;

use Schema;
use Prestasafe\Erp\Models\PaymentType;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePaymentTypesTable extends Migration
{
    public function up()
    {
        Schema::create('prestasafe_erp_payment_types', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->boolean('default')->default(false);
            $table->timestamps();
        });
        $p = new PaymentType;
        $p->name = 'Paypal';
        $p->default = true;
        $p->save();
    }

    public function down()
    {
        Schema::dropIfExists('prestasafe_erp_payment_types');
    }
}
