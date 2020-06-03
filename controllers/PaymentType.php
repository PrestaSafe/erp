<?php namespace Prestasafe\Erp\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Payment Type Back-end Controller
 */
class PaymentType extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Prestasafe.Erp', 'erp', 'paymenttype');
    }
}
