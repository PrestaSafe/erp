<?php namespace Prestasafe\Erp\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Prestasafe\Erp\Models\Currency as Curr;

/**
 * Currency Back-end Controller
 */
class Currency extends Controller
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

        BackendMenu::setContext('Prestasafe.Erp', 'erp', 'currency');
    }

    public function index()
    {
        $this->vars['postsTotal'] = Curr::count();
        $this->asExtension('ListController')->index();
    }
}
