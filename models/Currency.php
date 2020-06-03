<?php namespace Prestasafe\Erp\Models;

use Model;

/**
 * Currency Model
 */
class Currency extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'prestasafe_erp_currencies';

    /**
     * @var array Guarded fields
     */
    protected $guarded = [''];



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

    public static function getAll()
	{
		// return Currency::where('id_user',Auth::id())->where('active',1)->get();
		
	}
	public function getSign()
	{
		// $cur = Currency::find($id);
        return $this->sign;

    }

    /**
     * Return name and sign for listing dropdown.
     *
     * @return string
     */
    public function getIdentificationAttribute()
    {
        return $this->name.' '.$this->sign;
    }
    /**
     * list currencies for dropdown
     *
     * @param [type] $fieldName
     * @param [type] $value
     * @param [type] $formData
     * @return void
     */
    public function listCurrencies($fieldName, $value, $formData)
    {
        return Currency::all()->pluck('identification','id');
    }

    /**
     * Retrun default ID taxe
     *
     * @return void
     */
    public static function getDefaultCurrencyId()
    {
        return Currency::where('default', 1)->first()->id ?? Currency::first()->id;
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
            Currency::whereNotIn('id', [$this->id])->update(['default' => false]);
        }
    }
    
}
