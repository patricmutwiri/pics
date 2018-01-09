<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2016 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	https://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$data = array();
$data['params'] = $this->params;
$data['topic'] = $this->topic;
$data['utilities'] = $this->utilities;

$layout = new JLayoutFile('fbp_topic');
echo $layout->render($data);