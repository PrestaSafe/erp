<?php namespace Prestasafe\Erp\Models;

use Model;

/**
 * QuoteFields Model
 */
class QuoteFields extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'prestasafe_erp_quote_fields';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    public $timestamps = false;

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Validation rules for attributes
     */
    public $rules = [];

    /**
     * @var array Attributes to be cast to native types
     */
    protected $casts = [];

    /**
     * @var array Attributes to be cast to JSON
     */
    protected $jsonable = [];

    /**
     * @var array Attributes to be appended to the API representation of the model (ex. toArray())
     */
    protected $appends = [];

    /**
     * @var array Attributes to be removed from the API representation of the model (ex. toArray())
     */
    protected $hidden = [];

    /**
     * @var array Attributes to be cast to Argon (Carbon) instances
     */
    protected $dates = [
        'created_at',
        'updated_at'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [
        'tax' => [Tax::class, 'key' => 'id', 'otherKey' => 'tax_id']
    ];
    public $hasMany = [];
    public $belongsTo = [
        'quote' => [
            Quote::class
        ]
    ];


      /**
     * get Tax list
     *
     * @return void
     */
    public function getTaxesList()
    {
        return Tax::pluck('name','id')->all();
    }

    /**
     * Set id default of taxes
     *
     * @return void
     */
    public function getTaxIdAttribute()
    {
        return $this->exists ? $this->attributes['tax_id'] : Tax::getDefaultTaxId();
    }

    /**
     * Refresh field with ajax
     *
     * @param [type] $fields
     * @param [type] $context
     * @return void
     */
    public function filterFields($fields, $context = null)
    {
        $tax_id = $fields->tax_id->value;
        if($context == 'create')
        {
            if($tax_id)
            {
                $tax_rate = Tax::find($tax_id)->ratio;
                $p_ht = $this->getPriceHT($tax_rate);
                $p_ttc = $this->getPriceTTC($tax_rate);
                
                $fields->price_ttc->value = $p_ttc;
                $fields->price_ht->value = $p_ht;
            }
        }else{
                $p_ht = $this->getPriceHT();
                $p_ttc = $this->getPriceTTC();
                $fields->price_ttc->value = $p_ttc;
                $fields->price_ht->value = $p_ht;
        }
    }
    

    public function getPriceHT($tax_rate = null)
    {
        if($this->tax)
        {
            return round($this->price_ttc / $this->tax->ratio,2);
        }else if ($tax_rate !== null){
            return round($this->price_ttc / $tax_rate,2);

        }
    }

    public function getPriceTTC($tax_rate = null)
    {
        if($this->tax)
        {
            return round($this->price_ht * $this->tax->ratio,2); 
        }else if ($tax_rate !== null){
            return round($this->price_ht * $tax_rate,2); 

        } 
    }

    public function calculatePriceWithDiscount($price,$quantity,$discount_percent)
    {
        return ($discount_percent>0) ? $price * $quantity * (1-($discount_percent/100)) : $price * $quantity;
    }

    /**
     * get Total TTC of one field for listing
     *
     * @return float
     */
    public function getTotalFieldTTCWithDiscountAttribute()
    {
        return (float)$this->getTotalPriceTtcAttribute(true);
    }

    /**
     * Get total HT of one field (for listing)
     *
     * @return float
     */
    public function getTotalFieldHTWithDiscountAttribute()
    {
        return (float)$this->getTotalPriceHtAttribute(true);
    }

    public function getTotalPriceHtAttribute($use_discount = true)
    {
        if($use_discount){

            return ($this->remise > 0) ? $this->price_ht * $this->quantity * (1-($this->remise/100)) : $this->price_ht * $this->quantity;
        }else{
            return $this->price_ht * $this->quantity;
        }
    }

    public function getTotalPriceTtcAttribute($use_discount = true)
    {
        if($use_discount){
            return ($this->remise > 0) ? $this->price_ttc * $this->quantity * (1-($this->remise/100)) : $this->price_ttc * $this->quantity;
        }else{
            return $this->price_ttc * $this->quantity;
        }
    }



    public function getTotalDiscountAmountTtcAttribute()
    {
        return (float)round($this->getTotalPriceTtcAttribute(false) - $this->getTotalPriceTtcAttribute(true),2);
    }

    public function getTotalDiscountAmountHtAttribute()
    {
        return (float)round($this->getTotalPriceHtAttribute(false) - $this->getTotalPriceHtAttribute(true),2);

    }
}
