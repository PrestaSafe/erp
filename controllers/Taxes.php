<?php namespace Prestasafe\Erp\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Prestasafe\Erp\Models\Tax;

/**
 * Taxes Back-end Controller
 */
class Taxes extends Controller
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

        BackendMenu::setContext('Prestasafe.Erp', 'erp', 'tax');
    }

    public function index()
    {
        $this->vars['postsTotal'] = Tax::count();
        $this->asExtension('ListController')->index();
    }

    
}
