<?php namespace Prestasafe\Erp\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Settings Back-end Controller
 */
class Settings extends Controller
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

        BackendMenu::setContext('Prestasafe.Erp', 'erp', 'settings');
    }

    public function index()
    {
        $this->asExtension('ListController')->index();
    }


}

