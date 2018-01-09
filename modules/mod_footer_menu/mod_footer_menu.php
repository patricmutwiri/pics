<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

// Include the syndicate functions only once
require_once __DIR__ . '/helper.php';
$item = ModFooter_menuHelper::getItem();

require JModuleHelper::getLayoutPath('mod_footer_menu', $params->get('layout', 'default'));