<?php namespace Prestasafe\Erp\Models;

use Model;

/**
 * Taxes Model
 */
class Tax extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'prestasafe_erp_taxes';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [''];

    /**
     * @var array Fillable fields
     */

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'invoicefield' => [
            InvoiceFields::class
        ]
    ];

    /**
     * return tax ratio like 1.2 for 20%
     *
     * @return void
     */
    public function getRatioAttribute()
    {
        return (float) round(1 + ($this->rate/100),2);
    }

    /**
     * Retrun default ID taxe
     *
     * @return void
     */
    public static function getDefaultTaxId()
    {
        return Tax::where('default', 1)->first()->id ?? Tax::first()->id;
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
            Tax::whereNotIn('id', [$this->id])->update(['default' => false]);
        }
    }

    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
