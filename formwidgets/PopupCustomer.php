<?php namespace PrestaSafe\Erp\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * PopupCustomer Form Widget
 */
class PopupCustomer extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'prestasafe_erp_popup_customer';

    /**
     * @inheritDoc
     */
    public function init()
    {
    }

    
    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('popupcustomer');
    }



    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addCss('css/popupcustomer.css', 'PrestaSafe.Erp');
        $this->addJs('js/popupcustomer.js', 'PrestaSafe.Erp');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
