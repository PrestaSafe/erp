<?php namespace Prestasafe\Erp\Models;

use Model;

/**
 * PaymentType Model
 */
class PaymentType extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'prestasafe_erp_payment_types';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [''];


    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        // 'invoicefields' => InvoiceFields::class
    ];
    public $belongsToMany = [
        'invoicefields' => InvoiceFields::class,
        'quotesfields' => QuoteFields::class,
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * Retrun default ID taxe
     *
     * @return void
     */
    public static function getDefaultPaymentTypeId()
    {
        return PaymentType::where('default', 1)->first()->id ?? PaymentType::first()->id;
    }

    

    /**
     * Check defautl tax
     *
     * @return void
     */
    public function afterSave()
    {
        if($this->default)
        {
            PaymentType::whereNotIn('id', [$this->id])->update(['default' => false]);
        }
    }
}
