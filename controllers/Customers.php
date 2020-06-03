<?php namespace Prestasafe\Erp\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Prestasafe\Erp\Models\Customer;
/**
 * Customers Back-end Controller
 */
class Customers extends Controller
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

        BackendMenu::setContext('Prestasafe.Erp', 'erp', 'customers');
    }

    public function index()
    {
        $this->vars['postsTotal'] = Customer::count();
        $this->asExtension('ListController')->index();
    }

    public function onCreate() 
    {
        $this->asExtension('FormController')->create_onSave();
    }

}
