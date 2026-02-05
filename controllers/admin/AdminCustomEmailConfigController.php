<?php
class AdminCustomEmailConfigController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'custom_email_templates';
        $this->className = 'CustomEmailTemplate'; // Ehhez kell majd egy ObjectModel fájl is
        $this->identifier = 'id_template';

        parent::__construct();

        $this->fields_list = array(
            'id_template' => array('title' => $this->l('ID'), 'width' => 30),
            'name' => array('title' => $this->l('Template Name'), 'width' => 'auto'),
            'active' => array('title' => $this->l('Status'), 'active' => 'status'),
        );
    }

    public function renderForm()
    {
        $this->fields_form = array(
            'legend' => array('title' => $this->l('Edit Template'), 'icon' => 'icon-envelope'),
            'input' => array(
                array('type' => 'text', 'label' => $this->l('Template Name'), 'name' => 'name', 'required' => true),
                array('type' => 'textarea', 'label' => $this->l('HTML Content'), 'name' => 'content_html', 'autoload_rte' => false, 'desc' => $this->l('Paste your modern responsive HTML here.')),
            ),
            'submit' => array('title' => $this->l('Save'))
        );

        return parent::renderForm();
    }

    public function ajaxProcessSendTestEmail()
{
    $test_email = Tools::getValue('test_email');
    $id_template = (int)Tools::getValue('id_template');
    
    // Sablon lekérése az adatbázisból
    $template = new CustomEmailTemplate($id_template);
    
    if (Validate::isEmail($test_email) && $template->id) {
        $content = $template->content_html;
        
        // Mintaadatok behelyettesítése a teszthez
        $vars = [
            '{shop_name}' => Configuration::get('PS_SHOP_NAME'),
            '{firstname}' => 'Teszt',
            '{lastname}' => 'Felhasználó',
            '{order_name}' => 'ABC12345',
            '{date}' => date('Y-m-d'),
            '{payment}' => 'Bank Transfer',
            '{carrier}' => 'Fan Courier',
            '{items}' => '<tr><td><img src="https://placehold.co/50" width="50"></td><td>Teszt Termék</td><td align="right">100 RON</td></tr>',
            '{total_paid}' => '100 RON',
            '{message}' => 'Ez egy teszt üzenet a vásárlótól.'
        ];

        $subject = 'Test Email - ' . $template->name;

        // Küldés a PrestaShop saját levelezőjével
        $res = Mail::Send(
            (int)Context::getContext()->language->id,
            'test_template', // dummy template név
            $subject,
            $vars,
            $test_email,
            null, null, null, null, null,
            _PS_MODULE_DIR_ . 'customemailmanager/mails/', // modul belső mappája
            false,
            (int)Context::getContext()->shop->id
        );

        die(json_encode(['success' => $res]));
    }
    die(json_encode(['success' => false, 'error' => 'Invalid data']));
}
}
