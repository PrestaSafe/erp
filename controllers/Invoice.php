<?php namespace Prestasafe\Erp\Controllers;

use BackendMenu;
use Barryvdh\DomPDF\PDF;
use Backend\Facades\Backend;
use Backend\Classes\Controller;

use Prestasafe\Erp\Models\Currency;

use Prestasafe\Erp\Models\Customer;
use Prestasafe\Erp\Models\Settings;
use Illuminate\Support\Facades\Mail;
use October\Rain\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use October\Rain\Support\Facades\Flash;
use Illuminate\Support\Facades\Redirect;
use Prestasafe\Erp\Models\Invoice as InvoiceModel;


/**
* Invoice Back-end Controller
*/
class Invoice extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController',
    ];
    
    public $rules = [
        
    ];
    
    
    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    protected $widgetCustomer;    
    protected $itemsWidget;    
    protected $taxWidget;    
    protected $currencyWidget;    
    protected $paymentWidget;    
    protected $pdf;

    
    public function __construct(PDF $pdf)
    {
        $this->pdf = $pdf;
        parent::__construct();
        BackendMenu::setContext('Prestasafe.Erp', 'erp', 'invoice');
        $this->widgetCustomer = $this->createCustomerWidget();
        $this->itemsWidget = $this->createItemWidget();
        $this->taxWidget = $this->createTaxWidget();
        $this->currencyWidget = $this->createCurrencyWidget();
        $this->paymentWidget = $this->createpaymentWidget();

        // $this->addJs('/modules/system/assets/js/framework.extras.js');
        // $this->addCss('/modules/system/assets/css/framework.extras.css');
    }




    /**
     * Create widget for customers
     *
     * @return void
     */
    public function createCustomerWidget()
    {
        $config = $this->makeConfig('$/prestasafe/erp/models/customer/fields.yaml');
        $config->alias = 'customer';
        $config->context = 'create';
        $config->arrayName = 'Customer';
        $config->url = Backend::url('prestasafe/erp/customers/create');
        $config->model = new \Prestasafe\Erp\Models\Customer;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }

    /**
     * Create item widgets
     *
     * @return void
     */
    public function createItemWidget()
    {
        $config = $this->makeConfig('$/prestasafe/erp/models/invoicefields/fields.yaml');

        $config->alias = 'items';

        $config->arrayName = 'Items';

        // $config->url = Backend::url('prestasafe/erp/customers/create');

        $config->model = new \Prestasafe\Erp\Models\Customer;

        $widget = $this->makeWidget('Backend\Widgets\Form', $config);

        $widget->bindToController();

        return $widget;
    }
    /**
     * Create taxes widget
     *
     * @return void
     */
    public function createTaxWidget()
    {
        $config = $this->makeConfig('$/prestasafe/erp/models/tax/fields.yaml');
        $config->alias = 'taxes';
        $config->arrayName = 'Tax';
        $config->url = Backend::url('prestasafe/erp/taxes/create');
        $config->model = new \Prestasafe\Erp\Models\Tax;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }
    /**
     * Generate currency widget
     *
     * @return void
     */
    public function createCurrencyWidget()
    {
        $config = $this->makeConfig('$/prestasafe/erp/models/currency/fields.yaml');
        $config->alias = 'currency';
        $config->arrayName = 'Currency';
        $config->url = Backend::url('prestasafe/erp/currency/create');
        $config->model = new \Prestasafe\Erp\Models\Currency;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }
    /**
     * Widget for invoice paiements
     *
     * @return void
     */
    public function createpaymentWidget()
    {
        $config = $this->makeConfig('$/prestasafe/erp/models/paymenttype/fields.yaml');
        $config->alias = 'paymenttype';
        $config->arrayName = 'paymenttype';
        $config->url = Backend::url('prestasafe/erp/paymenttype/create');
        $config->model = new \Prestasafe\Erp\Models\PaymentType;
        $widget = $this->makeWidget('Backend\Widgets\Form', $config);
        $widget->bindToController();
        return $widget;
    }



    public function index()
    {
        $this->vars['postsTotal'] = InvoiceModel::count();
        $this->asExtension('ListController')->index();
    }
    
    /**
     * Extends default value of recordfinder (id_currency)
     *
     * @param [type] $host
     * @param [type] $fields
     * @return void
     */
    public function formExtendFields($host, $fields)
    {

      foreach ($fields as $field) {
          if($field -> fieldName == 'id_currency' && !$field -> value)
              $field -> value = Currency::getDefaultCurrencyId();

      }
    }
  
    /**
     * Assign default vars for rendering template.
     * Items numbers total prices / discounts.
     *
     * @param [type] $model
     * @param boolean $with_array
     * @return void
     */
    public function assignCalculVars($model, $with_array = false)
    {
        $this->vars['total_ht'] = $model->getTotalFields(false);
        $this->vars['total_ttc'] = $model->getTotalFields();
        $this->vars['items_number'] = $model->getItemsNumber();
        $this->vars['total_discount'] = $model->getTotalDiscount();
        $this->vars['currency'] = $model->currency->sign;
        $this->vars['customer_email'] = ($model->customers) ? $model->customers->mail : '';

        

        if($with_array)
        {
            
            return [
                '#total_ht' => $this->vars['total_ht'],
                '#total_ttc' => $this->vars['total_ttc'],
                '#items_number' => $this->vars['items_number'],
                '#total_discount' => $this->vars['total_discount'],
                '#currency' => $this->vars['currency'],
            ];
        }
    } 

    
    public function update($id = null)
    {

        $model = InvoiceModel::find($id);
        $this->assignCalculVars($model);
        parent::update($id);
    }

  
    /**
     * Load record find of user model
     * @return string|html
     */
    public function OnLoadContent()
    {

        $this->vars['widgetCustomer'] = $this->widgetCustomer;
        $this->vars['orderId'] = Request::segment(6);
        return $this->makePartial('$/prestasafe/erp/models/customer/_add_customer_form.htm');
    } 

    /**
     * Create user form popup
     *
     * @return void
     */
    public function onCreateCustomer()
    {
        $data = $this->widgetCustomer->getSaveData();

        $model = new \Prestasafe\Erp\Models\Customer;

        $model->fill($data);

        if($model->save())
        {
            Flash::success(trans('prestasafe.erp::lang.user.saved_success'));
        }   

        // return [
        //     '#currency-dropdown' => 'dropdown update'
        // ];
    }
    /**
     * Create tax from popup
     *
     * @return void
     */
    public function onCreateTax()
    {
        $data = $this->taxWidget->getSaveData();

        $model = new \Prestasafe\Erp\Models\Tax;

        $model->fill($data);

        $model->save();   
    }
    /**
     * Creater currency from popup
     *
     * @return void
     */
    public function onCreateCurrency()
    {
        $data = $this->currencyWidget->getSaveData();

        $model = new \Prestasafe\Erp\Models\Currency;

        $model->fill($data);

        $model->save();   
    }
    /**
     * Create payment ajax
     *
     * @return void
     */
    public function onCreatePayment()
    {
        $data = $this->paymentWidget->getSaveData();
        $model = new \Prestasafe\Erp\Models\PaymentType;
        $model->fill($data);
        $model->save(); 
    }

    /**
     * Set vars for PDF
     *
     * @param [type] $id_invoice
     * @return void
     */
    public function getInvoiceVars($id_invoice)
    {
        $invoice = InvoiceModel::find($id_invoice);
        $settings = Settings::instance();
        $this->vars['invoice'] = $invoice;
        $this->vars['title'] = trans('prestasafe.erp::lang.common.invoice'). ' #'.$invoice->id;
        $this->vars['fields'] = $invoice->fields;
        $this->vars['paiements'] = $invoice->paiements;
        $this->vars['css'] = $settings->css_invoice;
        $this->vars['img'] = $settings->img_invoice;
        // dd($settings->img_invoice->getPath());
        $this->vars['bottom'] = $settings->bottom_invoice_information;

    }

    /**
     * Get content of document for PDF
     *
     * @param [type] $id_invoice
     * @param boolean $page_break
     * @return void
     */
    public function getInvoiceContent($id_invoice,$page_break = false)
    {
        $this->getInvoiceVars($id_invoice);
        $this->vars['page_break'] = $page_break;
        return $this->makePartial('$/prestasafe/erp/views/_template_invoice_content.htm');
    }
    /**
     * Send to Customer by email.
     *
     * @return void
     */
    public function onSendInvoice()
    {  
        
        $data = ['subject' => trans('prestasafe.erp::mail.download_invoice') ];
        $id_invoice = (int)Request::segment(6);
        $invoice = InvoiceModel::find($id_invoice);
        $this->getInvoiceVars($id_invoice);
        $this->generatePdfLocal($id_invoice);
        if(!File::isDirectory(plugins_path('prestasafe/erp/pdf/')))
        {
            File::makeDirectory(plugins_path('prestasafe/erp/pdf/'));
        }
        
        $pathToFile = plugins_path('prestasafe/erp/pdf/invoice_'.$id_invoice.'.pdf');

        Mail::send('prestasafe.erp::mail.send_invoice', $data, function ($message) use($pathToFile,$invoice) {
            $message->to($invoice->customers->mail);
            $message->subject(trans('prestasafe.erp::lang.invoice_subject'));
            $message->attach($pathToFile);
        });

        unlink($pathToFile);
        if (Mail::failures()) {
            Flash::error(trans('prestasafe.erp::lang.invoice_sent_failed'));
            return false;
        }

        Flash::success(trans('prestasafe.erp::lang.invoice_sent_success'));
    }

    /**
     * Generate PDF localy
     *
     * @param [type] $id_invoice
     * @return void
     */
    public function generatePdfLocal($id_invoice)
    {
        $this->vars['content'] = $this->getInvoiceContent($id_invoice);
        $this->vars['title'] = 'Invoice';
        $view = $this->makePartial('$/prestasafe/erp/views/_template_invoice.htm');

        $this->pdf->loadHTML($view)->save(plugins_path('prestasafe/erp/pdf/invoice_'.$id_invoice.'.pdf'));

    }
    
    /**
     * Steam pdf
     *
     * @param [type] $id_invoice
     * @param boolean $view_only
     * @return void
     */
    public function generatePDF($id_invoice = null,$view_only = false)
    {
        if($id_invoice == null)
        {
            $id_invoice = (int)Request::segment(6);
        }
        $this->vars['content'] = $this->getInvoiceContent($id_invoice);
        $this->vars['title'] = trans('Invoice').' #'.$id_invoice;
        $view = $this->makePartial('$/prestasafe/erp/views/_template_invoice.htm');
        if($view_only){
            return $view;
        }
        $pdf = $this->pdf->loadHTML($view);

        return $pdf->stream('invoice.pdf');
    }
    /**
     * Generate PDF multiple
     *
     * @param [strin|base64_encode|serialize] $ids
     * @return PDF::download
     */
    public function generatePDFMultiple($ids)
    {
        $ids = unserialize(base64_decode($ids));
        if(!is_array($ids)){
            return false;
        }
        $content = '';
        $ids = collect($ids);
        foreach($ids as $id_invoice)
        {
            $page_break = true;
            if($id_invoice == $ids->last())
            {
                $page_break = false;
            }

            $content .= $this->getInvoiceContent($id_invoice,$page_break);
        }
        $this->vars['content'] = $content;
        $view = $this->makePartial('$/prestasafe/erp/views/_template_invoice.htm');
        // return $view;
        // die();
        $pdf = $this->pdf->loadHTML($view);
        return $pdf->stream('invoice.pdf');
    }

    public function seeInvoice()
    {
        $id_invoice = (int)Request::segment(6);
        $invoice = InvoiceModel::find($id_invoice);
        $settings = Settings::instance();
        $this->vars['invoice'] = $invoice;
        $this->vars['fields'] = $invoice->fields;
        $this->vars['paiements'] = $invoice->paiements;
        $this->vars['css'] = $settings->css_invoice;
        $this->vars['img'] = $settings->img_invoice;
        $this->vars['bottom'] = $settings->bottom_invoice_information;

        $view = $this->makePartial('$/prestasafe/erp/views/_template_invoice.htm');
        return $view;
    }



    /**
     * Load widgetTax from popup
     *
     * @return void
     */
    public function onLoadTaxWidget()
    {
        $this->vars['taxWidget'] = $this->taxWidget;
        return $this->makePartial('$/prestasafe/erp/models/tax/_popup_taxes.htm');
    }
    
    public function onLoadCurrency()
    {
        $this->vars['currencyWidget'] = $this->currencyWidget;
        return $this->makePartial('$/prestasafe/erp/models/currency/_popup_currency.htm');

    }

    public function relationExtendRefreshResults($field)
    {
        if ($field != 'fields')
            return;

        $model = $this->formGetModel();
        return $this->assignCalculVars($model,true);
    }

    /**
     * Load form create payment
     *
     * @return void
     */
    public function onLoadPaymentWidget()
    {
        $this->vars['paymentWidget'] = $this->paymentWidget;
        return $this->makePartial('$/prestasafe/erp/models/paymenttype/_popup_payment.htm');
    }
    /**
     * Export PDF list of invoice
     *
     * @return void
     */
    public function onExportPdfList()
    {
        $ids = input('checked');
        $ids = base64_encode(serialize($ids));
        return Redirect::to(Backend::url('prestasafe/erp/invoice/generatePDFMultiple/'.$ids));
    } 
    
}
