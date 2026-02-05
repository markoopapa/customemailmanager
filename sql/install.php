<?php
$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'custom_email_templates` (
    `id_template` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL,
    `content_html` text NOT NULL,
    `active` tinyint(1) DEFAULT 1,
    PRIMARY KEY (`id_template`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
