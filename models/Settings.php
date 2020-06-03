<?php namespace Prestasafe\Erp\Models;

use Model;
use October\Rain\Support\Facades\File;

/**
 * Settings Model
 */
class Settings extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'prestasafe_erp_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    public $attachOne = [
        'img_invoice' => 'System\Models\File'
    ];

    public function beforeSave()
    {
        // need to fix
        $tpl_invoice = input('Settings')['template_invoice'];
        $tpl_content = input('Settings')['template_content'];

        \trace_log($tpl_invoice);
        \trace_log($tpl_content);

        // File::put dont works
        file_put_contents(plugins_path('prestasafe/erp/views/_template_invoice.htm'),$tpl_invoice);

        file_put_contents(plugins_path('prestasafe/erp/views/_template_invoice_content.htm'),$tpl_content);


    }

    public function getCssInvoiceAttribute()
    {
        return $this->exists ? $this->attributes['css_invoice'] : File::get(plugins_path('prestasafe/erp/views/default_templates/default.css'));
    }

    public function getTemplateInvoiceAttribute()
    {
        return File::get(plugins_path('prestasafe/erp/views/_template_invoice.htm')) ?? File::get(plugins_path('prestasafe/erp/views/default_templates/_template_quote_default.htm'));
    }
    
    public function getTemplateContentAttribute()
    {

        return File::get(plugins_path('prestasafe/erp/views/_template_invoice_content.htm')) ?? File::get(plugins_path('prestasafe/erp/views/default_templates/_template_invoice_content_default.htm'));

    }
}
