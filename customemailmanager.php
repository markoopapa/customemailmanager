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
        // Adatbázis létrehozása és Hook-ok regisztrálása
        return parent::install() &&
            $this->registerHook('actionEmailSendBefore') &&
            $this->installTab();
    }

    // Ez a függvény kapja el az e-mail küldést
    public function hookActionEmailSendBefore($params)
    {
        // Itt fogjuk kicserélni a sablont a te modern designodra
        // És itt adjuk hozzá a képeket a {items} változóhoz
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
