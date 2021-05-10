<?php namespace Prestasafe\Erp\Models;

use Model;
use Prestasafe\Erp\Models\Currency;
use Prestasafe\Erp\Models\Customer;
use Prestasafe\Erp\Models\Settings as SettingsERP;
/**
* Invoice Model
*/
class Invoice extends Model
{

    /**
    * @var string The database table used by the model.
    */
    public $table = 'prestasafe_erp_invoices';
    
    /**
    * @var array Fillable fields
    */
    protected $fillable = [
        'fake_id','infos_company','infos_client','id_customer',
        'id_customer','id_currency','objet','created_at','updated_at',
        'id_devis','date_display','active','id_user'
    ];
    

    
    /**
    * @var array Relations
    */
    public $hasOne = [
        'currency' => ['\Prestasafe\Erp\Models\Currency','key' => 'id','otherKey' => 'id_currency'],
        'customers' => ['\Prestasafe\Erp\Models\Customer', 'key' => 'id','otherKey' => 'id_customer'],
    ];
    public $hasMany = [
        'fields' => ['\Prestasafe\Erp\Models\InvoiceFields', 'key' => 'id_invoice'],
        'paiements' => ['\Prestasafe\Erp\Models\InvoicePaiements', 'key' => 'id_invoice'],
        
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
    
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
     * get default value of company
     *
     * @return string
     */
    public function getInfosCompanyAttribute()
    {
        return $this->exists ? $this->attributes['infos_company'] : SettingsERP::instance()->compagny_name;
    }
    
    public function getDateDisplayAttribute()
    {
        return $this->exists ? $this->attributes['date_display'] : new \DateTime('NOW');
    }
    
    public function getInfosClientAttribute()
    {
        return $this->exists ? $this->attributes['infos_client'] : $this->customer->name;

    }
    

    /**
    * if Invoice is paid
    *
    * @return boolean
    */
    public function isPaid()
    {
        return (bool)($this->getTotalPaiements() >= $this->getTotalFields()) ? true : false;
    }
    
    /**
    * return amout not paid
    *
    * @param boolean $display_currency if true ex: 12 €
    * @return string|float
    */
    public function getAmountNeeded($display_currency = false)
    {
        return ($display_currency) ? $this->getTotalFields() - $this->getTotalPaiements().' '.$this->currency->sign : (float)$this->getTotalFields() - $this->getTotalPaiements();
    }
    
    /**
    * get paiements list
    *
    * @return array
    */
    public function getPaiements()
    {
        // return paiements collections... 
        return $this->paiements;
    }
    
    /**
    * get Totals paiements
    *
    * @return float
    */
    public function getTotalPaiements()
    {
        // return total amount of paiements

        return (float) round($this->paiements->sum('montant'),2);

    }
    
    /**
    * get Invoice Total TTC or HT with discount
    *
    * @param boolean $taxes_included
    * @return float
    */
    public function getTotalFields($taxes_included = true)
    {
        $collect = $this->fields->map(function($field) use ($taxes_included) {
            $price = ($taxes_included) ? $field->price_ttc : $field->price_ht;
            return  ($field->remise > 0) ? $price * $field->quantity * (1-($field->remise/100)) : $price * $field->quantity;
        });
        return round($collect->sum(),2);
    }


    /**
     * return quantity total of items
     *
     * @return int
     */
    public function getItemsNumber()
    {
        return $this->fields->sum('quantity');
    }
    /**
     * return total discount of invoice
     *
     * @return double
     */
    public function getTotalDiscount()
    {
        return round($this->fields->sum('total_discount_amount_ttc'),2);
    } 

    /**
     * return total invoice with taxes
     *
     * @return float
     */
    public function getTotalInvoiceTtcAttribute()
    {
        return $this->getTotalFields().$this->currency->sign;
    }
    
    /**
     * Return total invoice HT
     *
     * @return float
     */
    public function getTotalInvoiceHtAttribute()
    {
        return $this->getTotalFields(false).$this->currency->sign;
    }

    


}
