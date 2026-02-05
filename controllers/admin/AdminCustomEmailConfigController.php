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
}
