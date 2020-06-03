<?php namespace Prestasafe\Erp\Models;

use Model;

/**
 * Customer Model
 */
class Customer extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'prestasafe_erp_customers';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [];

    /**
     * @var array Fillable fields
     */


    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * Format identification for dropdown listing.
     *
     * @return string
     */
    public function getIdentificationAttribute()
    {
        return $this->name.' <> '.$this->mail.' <> '.$this->adresse.' <> '.$this->tel;
    }

    /**
    * list all customers of ERP
    *
    * @param [type] $fieldName
    * @param [type] $value
    * @param [type] $formData
    * @return array
    */
    public function listCustomers($fieldName, $value, $formData)
    {
        return Customer::all()->pluck('identification','id');
    }
    /**
    * list all currencies of ERP
    *
    * @param [type] $fieldName
    * @param [type] $value
    * @param [type] $formData
    * @return array
    */
    public function listCurrencies($fieldName, $value, $formData)
    {
        return Currency::all()->pluck('identification','id');
    }
}
