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
    // 1. Alapadatok és e-mail típus kinyerése
    $template_name = $params['template']; // Ez pl. 'order_conf' vagy 'new_order'
    $template_vars = &$params['template_vars'];

    // 2. Termékképes lista generálása (ha van rendelés az adatok között)
    if (isset($template_vars['{id_order}'])) {
        $order = new Order((int)$template_vars['{id_order}']);
        $products = $order->getProducts();
        $items_html = '<table width="100%" style="border-collapse: collapse; font-family: sans-serif;">';

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

    // 3. Specifikus sablon keresése az adatbázisban a célpont alapján
    $id_template = (int)Db::getInstance()->getValue('
        SELECT id_template 
        FROM ' . _DB_PREFIX_ . 'custom_email_templates 
        WHERE active = 1 
        AND (target_email = "' . pSQL($template_name) . '" OR target_email = "all")
        ORDER BY FIELD(target_email, "' . pSQL($template_name) . '", "all") LIMIT 1
    ');

    // 4. Sablon csere (Wrapper logika), ha találtunk megfelelőt
    if ($id_template > 0) {
        $template_data = new CustomEmailTemplate($id_template);
        
        if (Validate::isLoadedObject($template_data)) {
            $full_html = $template_data->content_html;
            
            // Kicseréljük az összes változót a sablonban
            foreach ($template_vars as $key => $value) {
                if (is_string($value) || is_numeric($value)) {
                    $full_html = str_replace($key, $value, $full_html);
                }
            }

            // Felülírjuk a gyári tartalmat a választott sablonnal
            $params['cart_display'] = $full_html; 
            
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
