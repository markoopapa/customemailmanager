<?php
if (!defined('_PS_VERSION_')) {
    require_once dirname(__FILE__) . '/classes/CustomEmailTemplate.php';
    exit;
}

class CustomEmailManager extends Module
{
    public function __construct()
    {
        $this->name = 'customemailmanager';
        $this->tab = 'administration';
        $this->version = '1.0.0';
        $this->author = 'YourName';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Custom Email Template Manager');
        $this->description = $this->l('Manage modern email templates with product images and previews.');
    }

    public function install()
{
    if (!parent::install() ||
        !$this->registerHook('actionEmailSendBefore') ||
        !$this->installTab()) {
        return false;
    }

    // SQL tábla létrehozása
    include_once($this->local_path.'sql/install.php');

    // Alapértelmezett sablonok betöltése
    return $this->installDefaultTemplates();
}

private function installDefaultTemplates()
{
    // Beimportáljuk a fájlt, amiben a $default_templates tömb van
    include_once($this->local_path . 'sql/default_templates.php');

    if (isset($default_templates) && is_array($default_templates)) {
        foreach ($default_templates as $tpl) {
            Db::getInstance()->execute('
                INSERT INTO `' . _DB_PREFIX_ . 'custom_email_templates` (`name`, `content_html`, `active`)
                VALUES (
                    "' . pSQL($tpl['name']) . '", 
                    "' . pSQL($tpl['content_html'], true) . '", 
                    1
                )
            ');
        }
    }
    return true;
}

    public function hookActionEmailSendBefore($params)
{
    // 1. Alapadatok kinyerése
    $template_vars = &$params['template_vars'];
    $active_id = (int)Configuration::get('CUSTOM_EMAIL_ACTIVE_ID');

    // 2. Termékképes lista generálása (ha van rendelés)
    if (isset($template_vars['{id_order}'])) {
        $order = new Order((int)$template_vars['{id_order}']);
        $products = $order->getProducts();
        $items_html = '<table width="100%" style="border-collapse: collapse;">';

        foreach ($products as $product) {
            $image = Image::getCover($product['product_id']);
            $image_path = Context::getContext()->link->getImageLink(
                $product['link_rewrite'], 
                $image['id_image'], 
                'small_default'
            );

            $items_html .= '
            <tr style="border-bottom:1px solid #edf2f7;">
                <td style="padding:12px 8px; width:60px;">
                    <img src="'.$image_path.'" width="50" style="border-radius:4px; display:block;">
                </td>
                <td style="padding:12px 8px;">
                    <div style="font-weight:600; color:#1a1c21; font-size:14px;">'.$product['product_name'].'</div>
                    <div style="color:#718096; font-size:12px;">Ref: '.$product['product_reference'].'</div>
                </td>
                <td style="padding:12px 8px; text-align:right; font-weight:600; color:#1a1c21;">
                    '.Tools::displayPrice($product['total_price_tax_incl']).'
                </td>
            </tr>';
        }
        $items_html .= '</table>';

        // Lecseréljük a gyári {items} változót a mi profi listánkra
        $template_vars['{items}'] = $items_html;
    }

    // 3. Sablon csere (Wrapper logika)
    // Ha van kiválasztott aktív sablon az adminban
    if ($active_id > 0) {
        $template_data = new CustomEmailTemplate($active_id);
        
        if (Validate::isLoadedObject($template_data)) {
            $full_html = $template_data->content_html;
            
            // Kicseréljük az összes változót (pl. {shop_name}, {items}, {total_paid}) a sablonban
            foreach ($template_vars as $key => $value) {
                // Biztosítjuk, hogy csak szöveges adatokat cserélünk
                if (is_string($value) || is_numeric($value)) {
                    $full_html = str_replace($key, $value, $full_html);
                }
            }

            // A PrestaShop e-mail küldőjének átadjuk a módosított tartalmat
            // Ezzel felülírjuk a gyári alapértelmezett fájlt
            $params['cart_display'] = $full_html; 
            
            // Néhány Mail modulnál a 'template_html' paramétert is érdemes állítani a biztonság kedvéért
            if (isset($params['template_html'])) {
                $params['template_html'] = $full_html;
            }
        }
    }
}

    private function installTab()
    {
        $tab = new Tab();
        $tab->class_name = 'AdminCustomEmailConfig';
        $tab->module = $this->name;
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminParentThemes');
        $tab->active = 1;
        
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = 'Email Template Manager';
        }

        return $tab->add();
    }

    public function uninstall()
    {
        return parent::uninstall();
    }
}
