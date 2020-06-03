<?php namespace Prestasafe\Erp\Models;

use Model;
use Prestasafe\Erp\Models\PaymentType;
use Illuminate\Support\Facades\Request;

/**
 * InvoicePaiements Model
 */
class InvoicePaiements extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'prestasafe_erp_invoice_paiements';

    /**
     * @var array Guarded fields
     */
    protected $fillable = [
        "id_quote",
        "type_id",
        "date",
        "montant",
        "created_at",
        "updated_at",
    ];

    public $timestamps = false;

    /**
     * @var array Relations
     */


    public function getAmountNeedAttribute()
    {
        $id_invoice = (int)Request::segment(6);
        $invoice = Invoice::find($id_invoice);
        return $invoice->getAmountNeeded();
    }

    public function getDateAttribute()
    {
        return $this->exists ? $this->attributes['date'] : new \DateTime('NOW');
    }

    public function getTypeIdAttribute()
    {
        return $this->exists ? $this->attributes['type_id'] : PaymentType::getDefaultPaymentTypeId();
    }

    public function getTypeIdOptions()
    {
        return PaymentType::pluck('name','id')->all();
    }

    public $hasOne = [
        'type' => [PaymentType::class, 'key' => 'id', 'otherKey' => 'type_id'],
    ];
    public $hasMany = [];
    public $belongsTo = [
        'invoice' => [
            Invoice::class, 'key' => 'id_invoice','otherKey' => 'id'
        ]
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
