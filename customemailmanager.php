<?php
if (!defined('_PS_VERSION_')) {
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
    include_once($this->local_path.'sql/default_templates.php');

    foreach ($default_templates as $tpl) {
        Db::getInstance()->execute('
            INSERT INTO `' . _DB_PREFIX_ . 'custom_email_templates` (`name`, `content_html`, `active`)
            VALUES ("' . pSQL($tpl['name']) . '", "' . pSQL($tpl['content'], true) . '", 1)
        ');
    }
    return true;
}

    public function hookActionEmailSendBefore($params)
{
    $template_vars = &$params['template_vars'];
    
    // Csak akkor futunk le, ha van rendelés az adatok között
    if (isset($template_vars['{id_order}'])) {
        $order = new Order((int)$template_vars['{id_order}']);
        $products = $order->getProducts();
        $items_html = '';

        foreach ($products as $product) {
            // Termékkép URL generálása
            $image = Image::getCover($product['product_id']);
            $image_path = Context::getContext()->link->getImageLink($product['link_rewrite'], $image['id_image'], 'small_default');

            // Megépítjük a saját HTML sorunkat képpel
            $items_html .= '
            <tr style="border-bottom:1px solid #eee;">
                <td style="padding:10px;"><img src="'.$image_path.'" width="50"></td>
                <td style="padding:10px;"><strong>'.$product['product_name'].'</strong><br><small>Ref: '.$product['product_reference'].'</small></td>
                <td style="padding:10px; text-align:right;">'.Tools::displayPrice($product['total_price_tax_incl']).'</td>
            </tr>';
        }

        // Kicseréljük a gyári {items} változót a miénkre
        $template_vars['{items}'] = $items_html;
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
