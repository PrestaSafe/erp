<?php namespace Prestasafe\Erp\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Illuminate\Support\Facades\View;

/**
 * Erp Back-end Controller
 */
class Erp extends Controller
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

        BackendMenu::setContext('Prestasafe.Erp', 'erp', 'dashboard');
    }

    public function index()
    {
        $this->pageTitle = 'Dashboard';
        return View::make('prestasafe.erp::dashboard');
    }
}
