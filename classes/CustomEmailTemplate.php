<?php
class CustomEmailTemplate extends ObjectModel
{
    public $id_template;
    public $name;
    public $content_html;
    public $active;

    public static $definition = array(
        'table' => 'custom_email_templates',
        'primary' => 'id_template',
        'fields' => array(
            'name' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true, 'size' => 255),
            'content_html' => array('type' => self::TYPE_HTML, 'validate' => 'isCleanHtml', 'required' => true),
            'target_email' => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'size' => 50),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
        ),
    );
}
