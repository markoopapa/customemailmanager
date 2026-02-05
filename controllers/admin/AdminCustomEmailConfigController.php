<?php
class AdminCustomEmailConfigController extends ModuleAdminController
{
    public function __construct()
{
    $this->bootstrap = true;
    $this->table = 'custom_email_templates';
    $this->className = 'CustomEmailTemplate';
    $this->identifier = 'id_template';

    parent::__construct();

    $this->addRowAction('edit');
    $this->addRowAction('delete');

    $this->fields_list = array(
        'id_template' => array(
            'title' => $this->l('ID'),
            'align' => 'center',
            'class' => 'fixed-width-xs'
        ),
        'name' => array(
            'title' => $this->l('Template Name'),
            'width' => 'auto'
        ),
        'active' => array(
            'title' => $this->l('Status'),
            'active' => 'status',
            'type' => 'bool',
            'align' => 'center',
            'class' => 'fixed-width-sm'
        ),
    );
}

    public function renderForm()
{
    // Ez a HTML/JS kód felel a valós idejű előnézetért
    $preview_html = '
    <div class="panel">
        <div class="panel-heading"><i class="icon-eye"></i> Live Preview</div>
        <div class="alert alert-info">Ez egy minta előnézet. Írj a fenti HTML dobozba, és itt azonnal megjelenik az eredmény!</div>
        <iframe id="live_preview_frame" style="width:100%; height:600px; border:1px solid #d3d8db; background:#fff; border-radius:5px;"></iframe>
    </div>
    <script>
        $(document).ready(function() {
            var $textarea = $("#content_html");
            var $iframe = $("#live_preview_frame");
            
            // Mintaadatok a változók helyére (hogy szép legyen)
            var replacements = {
                "{shop_name}": "My PrestaShop Store",
                "{shop_logo}": "https://via.placeholder.com/150x50?text=Shop+Logo",
                "{firstname}": "John",
                "{lastname}": "Doe",
                "{email}": "john.doe@example.com",
                "{order_name}": "XJ9-TEST-ORDER",
                "{date}": "2026-02-05",
                "{payment}": "Credit Card",
                "{total_paid}": "15,000 Ft",
                "{carrier}": "GLS Hungary",
                "{message}": "Kérem a csengőt hosszan nyomni!",
                // Itt generálunk egy kamu terméklistát képpel
                "{items}": `<table style="width:100%; border-collapse:collapse;">
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:10px;"><img src="https://via.placeholder.com/64" style="border-radius:4px;"></td>
                        <td style="padding:10px;"><strong>Teszt Termék 1</strong><br><small style="color:#888;">Ref: DEMO-001</small></td>
                        <td style="padding:10px; text-align:right;">5,000 Ft</td>
                    </tr>
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:10px;"><img src="https://via.placeholder.com/64" style="border-radius:4px;"></td>
                        <td style="padding:10px;"><strong>Teszt Termék 2</strong><br><small style="color:#888;">Ref: DEMO-002</small></td>
                        <td style="padding:10px; text-align:right;">10,000 Ft</td>
                    </tr>
                </table>`
            };

            function updatePreview() {
                var content = $textarea.val();
                
                // Végigmegyünk a változókon és kicseréljük őket a mintaadatokra
                for (var key in replacements) {
                    // Globális csere (minden előfordulást)
                    content = content.split(key).join(replacements[key]);
                }

                var doc = $iframe[0].contentWindow.document;
                doc.open();
                doc.write(content);
                doc.close();
            }

            // Frissítés gépeléskor és betöltéskor
            $textarea.on("input keyup change", updatePreview);
            setTimeout(updatePreview, 500); // Kicsi késleltetés induláskor
        });
    </script>
    ';

    $this->fields_form = array(
        'legend' => array('title' => $this->l('Edit Email Template'), 'icon' => 'icon-envelope'),
        'input' => array(
            array(
                'type' => 'text', 
                'label' => $this->l('Template Name'), 
                'name' => 'name', 
                'required' => true
            ),
            array(
                'type' => 'select',
                'label' => $this->l('Target Email Type'),
                'name' => 'target_email',
                'options' => array(
                    'query' => array(
                        array('id' => 'all', 'name' => $this->l('All Emails')),
                        array('id' => 'order_conf', 'name' => $this->l('Customer Confirmation')),
                        array('id' => 'new_order', 'name' => $this->l('Admin Alert')),
                    ),
                    'id' => 'id',
                    'name' => 'name'
                )
            ),
            array(
                'type' => 'textarea', 
                'label' => $this->l('HTML Content'), 
                'name' => 'content_html', 
                'id' => 'content_html', // Fontos a JS-nek!
                'autoload_rte' => false, 
                'rows' => 15,
                'cols' => 100,
                'hint' => $this->l('Edit the HTML code here. The preview below will update automatically.')
            ),
            // Itt adjuk hozzá az előnézeti ablakot, mint egy "custom" mezőt
            array(
                'type' => 'free',
                'label' => $this->l('Preview'),
                'name' => 'live_preview_field',
            ),
            array(
                'type' => 'switch',
                'label' => $this->l('Active'),
                'name' => 'active',
                'is_bool' => true,
                'values' => array(
                    array('id' => 'active_on', 'value' => 1, 'label' => $this->l('Yes')),
                    array('id' => 'active_off', 'value' => 0, 'label' => $this->l('No'))
                ),
            ),
        ),
        'submit' => array('title' => $this->l('Save'))
    );

    // Átadjuk a HTML/JS kódot a 'free' mezőnek
    $this->fields_value['live_preview_field'] = $preview_html;

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
