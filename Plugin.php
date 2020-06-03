<?php namespace Prestasafe\Erp;

use Backend;
use System\Classes\PluginBase;
use Config;
use RainLab\User\Models\User;
/**
* Erp Plugin Information File
*/
class Plugin extends PluginBase
{

    public $require = [
        'Inetis.ListSwitch',
    ];


    /**
    * Returns information about this plugin.
    *
    * @return array
    */
    public function pluginDetails()
    {
        return [
            'name'        => 'Erp',
            'description' => 'No description provided yet...',
            'author'      => 'Prestasafe',
            'icon'        => 'icon-cloud'
        ];
    }
    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'Erp Settings',
                'description' => 'Manage your erp settings.',
                'category'    => 'Erp',
                'icon'        => 'icon-cloud-upload',
                'class'       => 'Prestasafe\Erp\Models\Settings',
                'order'       => 500,
                'keywords'    => 'security location',
                'permissions' => ['prestasafe.erp.*']
            ]
        ];
    }
    /**
     * load Packages
     *
     * @return void
     */
    public function bootPackages()
    {
        // Get the namespace of the current plugin to use in accessing the Config of the plugin
        $pluginNamespace = str_replace('\\', '.', strtolower(__NAMESPACE__));
        
        // Instantiate the AliasLoader for any aliases that will be loaded
        $aliasLoader = AliasLoader::getInstance();
        
        // Get the packages to boot
        $packages = Config::get($pluginNamespace . '::packages');
        
        // Boot each package
        foreach ($packages as $name => $options) {
            // Setup the configuration for the package, pulling from this plugin's config
            if (!empty($options['config']) && !empty($options['config_namespace'])) {
                Config::set($options['config_namespace'], $options['config']);
            }
            
            // Register any Service Providers for the package
            if (!empty($options['providers'])) {
                foreach ($options['providers'] as $provider) {
                    App::register($provider);
                }
            }
            
            // Register any Aliases for the package
            if (!empty($options['aliases'])) {
                foreach ($options['aliases'] as $alias => $path) {
                    $aliasLoader->alias($alias, $path);
                }
            }
        }
    }
    
    /**
    * Register method, called when the plugin is first registered.
    *
    * @return void
    */
    public function register()
    {
    }
    
    /**
    * Boot method, called right before the request route.
    *
    * @return array
    */
    public function boot()
    {
        User::extend(function($model) {
            $model->full_name = $model->name . ' ' .$model->surname;
        });

    }
    
    /**
    * Registers any front-end components implemented in this plugin.
    *
    * @return array
    */
    public function registerComponents()
    {
        return []; // Remove this line to activate
        
        return [
            'Prestasafe\Erp\Components\MyComponent' => 'myComponent',
        ];
    }
    
    /**
    * Registers any back-end permissions used by this plugin.
    *
    * @return array
    */
    public function registerPermissions()
    {
        return []; // Remove this line to activate
        
        return [
            'prestasafe.erp.some_permission' => [
                'tab' => 'Erp',
                'label' => 'Some permission'
            ],
        ];
    }
    
    /**
    * Registers back-end navigation items for this plugin.
    *
    * @return array
    */
    public function registerNavigation()
    {
        
        
        return [
            'erp' => [
                'label'       => 'Erp',
                'url'         => Backend::url('prestasafe/erp/invoice'),
                'icon'        => 'icon-cloud-upload',
                'permissions' => ['prestasafe.erp.*'],
                'order'       => 500,
                'sideMenu' => [
                    // 'dashboard' => [
                    //     'label' => 'Dashboard',
                    //     'icon' => 'icon-tachometer',
                    //     'url' => Backend::url('prestasafe/erp/Erp'),
                    //     'permissions' => ['prestasafe.erp.*']
                    // ],
                    'invoice' => [
                        'label'       => trans('prestasafe.erp::lang.common.invoices'),
                        'icon'        => 'icon-usd',
                        'url'         => Backend::url('prestasafe/erp/invoice'),
                        'permissions' => ['prestasafe.erp.*']
                    ],
                    'quotes' => [
                        'label'       => trans('prestasafe.erp::lang.common.quotes'),
                        'icon'        => 'icon-usd',
                        'url'         => Backend::url('prestasafe/erp/quotes'),
                        'permissions' => ['prestasafe.erp.*']
                    ],
                    'customers' => [
                        'label'       => trans('prestasafe.erp::lang.common.customers'),
                        'icon'        => 'icon-user',
                        'url'         => Backend::url('prestasafe/erp/customers'),
                        'permissions' => ['prestasafe.erp.*']
                    ],
                    'tax' => [
                        'label'       => trans('prestasafe.erp::lang.common.taxes'),
                        'icon'        => 'icon-usd',
                        'url'         => Backend::url('prestasafe/erp/taxes'),
                        'permissions' => ['prestasafe.erp.*']
                    ],
                    'currency' => [
                        'label'       => trans('prestasafe.erp::lang.common.currencies'),
                        'icon'        => 'icon-eur',
                        'url'         => Backend::url('prestasafe/erp/currency'),
                        'permissions' => ['prestasafe.erp.*']
                    ],
                    'paymenttype' =>[
                        'label' => trans('prestasafe.erp::lang.common.payment_type'),
                        'icon' => 'icon-eur',
                        'url' => Backend::url('prestasafe/erp/paymenttype'),
                        'permissions' => ['prestasafe.erp.*']
                    ], 
                    'settings' => [
                        'label'       => trans('prestasafe.erp::lang.common.settings'),
                        'icon'        => 'icon-gear',
                        'url'         => Backend::url('system/settings/update/prestasafe/erp/settings'),
                        'permissions' => ['prestasafe.erp.*']
                        ]
                        
                        ]
                    ],
                ];
            }
        }
        