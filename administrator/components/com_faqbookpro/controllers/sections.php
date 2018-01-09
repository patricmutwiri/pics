<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die;

class FAQBookProControllerSections extends JControllerAdmin
{
	public function __construct($config = array())
	{
		parent::__construct($config);
	}
	
	public function getModel($name = 'Section', $prefix = 'FAQBookProModel', $config = array('ignore_request' => true))
	{
		$model = parent::getModel($name, $prefix, $config);

		return $model;
	}

	protected function postDeleteHook(JModelLegacy $model, $ids = null)
	{
	}

}
