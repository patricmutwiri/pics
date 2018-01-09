<?php
/**
* @title			Minitek Wall
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class FAQBookProHelperUtilities
{
	public static $extension = 'com_faqbookpro';

	public function getSideMenuHTML()
	{
		$token = JSession::getFormToken();
		
		$menus = Array(
			Array(
				'title' => JText::_('COM_FAQBOOKPRO_HOME'),
				'url' => 'index.php?option=com_faqbookpro',
				'class' => 'fa fa-home',
				'children' => Array()
			),
			Array(
				'title' => JText::_('COM_FAQBOOKPRO_SECTIONS'),
				'url' => 'index.php?option=com_faqbookpro&view=sections',
				'class' => 'fa fa-list-ul',
				'children' => Array()
			),
			Array(
				'title' => JText::_('COM_FAQBOOKPRO_TOPICS'),
				'url' => 'index.php?option=com_faqbookpro&view=topics',
				'class' => 'fa fa-folder-open',
				'children' => Array()
			),
			Array(
				'title' => JText::_('COM_FAQBOOKPRO_QUESTIONS'),
				'url' => 'index.php?option=com_faqbookpro&view=questions',
				'class' => 'fa fa-question-circle',
				'children' => Array()
			),
			Array(
				'title' => JText::_('COM_FAQBOOKPRO_DOCUMENTATION'),
				'url' => 'http://www.minitek.gr/support/documentation/joomla-extensions/components/minitek-faq-book',
				'class' => 'fa fa-book',
				'children' => Array()
			),
			Array(
				'title' => JText::_('COM_FAQBOOKPRO_ABOUT'),
				'url' => 'index.php?option=com_faqbookpro&view=about',
				'class' => 'fa fa-info-circle',
				'children' => Array()
			),
			Array(
				'title' => JText::_('COM_FAQBOOKPRO_RATE_IT'),
				'url' => 'https://extensions.joomla.org/extensions/extension/directory-a-documentation/faq/minitek-faq-book/',
				'class' => 'fa fa-star',
				'children' => Array()
			)
		);
		
		$view = JRequest::getcmd('view');
		$cfgSection = JRequest::getcmd('cfgSection','');
		$cfgSection = (!empty($cfgSection)) ? '&cfgSection='.$cfgSection : '';

		$html = '<ul class="nav nav-list">' . PHP_EOL;

		foreach ($menus as $menu)
		{
			$hasChildren = ! empty($menu['children']);
			$dropdownToggleClass = ($hasChildren) ? 'dropdown-toggle' : '';
			$isOpen = false;
			$current = '';

			$openClass = ($isOpen) ? 'open' : '';
			$openSubStyle = ($isOpen) ? 'display: block;' : '';
			$target = '';
			if($menu['url'] == 'http://www.minitek.gr/support/documentation/joomla-extensions/components/minitek-faq-book'
			|| $menu['url'] == 'https://extensions.joomla.org/extensions/extension/directory-a-documentation/faq/minitek-faq-book/')
			{
				$target='target="_blank"';
			}

			$html .= '<li class="' . $openClass . '"><a href="' . JRoute::_($menu['url']) . '" class="' . $dropdownToggleClass . '" '.$target.'>';
			$html .= '<i class="' . $menu['class'] . '"></i> <span class="menu-text"> ' . $menu['title'] .' </span>';
			$html .= '</a>';
			$html .= '</li>'. PHP_EOL;
		}
		
		$html .= '</ul>' . PHP_EOL;
		
		$html .= '<div class="sidebar-collapse" id="sidebar-collapse">';
			$html .= '<i class="fa fa-angle-double-left"></i>';
		$html .= '</div>';

		return $html;
	}
	
	public function getNavbarHTML()
	{
		$version = self::localVersion();
		$version_match = str_replace(".","", $version);
		$version_match = (int)$version_match;
		$newVersion = self::currentVersion();
		$newVersion_match = str_replace(".","", $newVersion);
		$newVersion_match = (int)$newVersion_match;
	
		$user  = JFactory::getUser();
				
		$html = '<div class="navbar">' . PHP_EOL;	
		
			$html .= '<div class="navbar-inner">' . PHP_EOL;	
		
				$html .= '<div class="container-fluid">' . PHP_EOL;
		
					$html .= '<div class="brand-cont">';
						$html .= '<a href="'.JRoute::_("index.php?option=com_faqbookpro").'" class="brand">';
						$html .= '<img src="components/com_faqbookpro/assets/images/logo-white.png" alt="" />';
						$html .= JText::_('COM_FAQBOOKPRO');
						$html .= '</a>';
						
						if ($newVersion) {
						if ($version_match == $newVersion_match) {
							$html .= '<span id="mn-version" class="badge badge-success">'.$version.'</span>' . PHP_EOL;
							$html .= '<span id="mn-version-info">';
								$html .= '<button class="btn btn-info" type="button" data-toggle="modal" data-target="#myModal1">';
									$html .= '<i class="fa fa-info"></i>';
								$html .= '</button>';
								$html .= '<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModal1Label" aria-hidden="true">';
								  $html .= '<div class="modal-dialog">';
									$html .= '<div class="modal-content">';
									  $html .= '<div class="modal-body">';
									  $html .= '<div class="thumbnail">';
										$html .= '<h3><i class="fa fa-check text-success"></i>&nbsp;&nbsp;'.JText::_('COM_FAQBOOKPRO_YOU_HAVE_THE_LATEST_VERSION');
										$html .= '</h3>';
									  $html .= '</div>';
									  $html .= '</div>';
									$html .= '</div>';
								  $html .= '</div>';
								$html .= '</div>';
							$html .= '</span>' . PHP_EOL;
						} else {
							$html .= '<span id="mn-version" class="badge badge-important">'.$version.'</span>' . PHP_EOL;
							$html .= '<span id="mn-version-info">';
								$html .= '<button class="btn btn-danger" type="button" data-toggle="modal" data-target="#myModal2">';
									$html .= '<i class="fa fa-info"></i>';
								$html .= '</button>';
								$html .= '<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModal2Label" aria-hidden="true">';
								  $html .= '<div class="modal-dialog">';
									$html .= '<div class="modal-content">';
									  $html .= '<div class="modal-body">';
									  $html .= '<div class="thumbnail">';
										$html .= '<h3>';
										$html .= JText::_('COM_FAQBOOKPRO_NEW_VERSION_IS_RELEASED');
										$html .= '</h3>';
										$html .= '<a href="http://www.minitek.gr/joomla-extensions/minitek-faq-book" target="_blank" class="btn btn-info">'.JText::_('COM_FAQBOOKPRO_LEARN_MORE').'</a>';
										$html .= '&nbsp;&nbsp;'.JText::_('COM_FAQBOOKPRO_NEW_VERSION_OR').'&nbsp;&nbsp;';
										$html .= '<a href="index.php?option=com_installer&view=update" class="btn btn-info">';
										$html .= JText::_('COM_FAQBOOKPRO_NEW_VERSION_UPDATE_TO').'&nbsp;';
										$html .= $newVersion;
										$html .= '</a>';
									  $html .= '</div>';
									  $html .= '</div>';
									$html .= '</div>';
								  $html .= '</div>';
								$html .= '</div>';
							$html .= '</span>' . PHP_EOL;
						}
						}
					$html .= '</div>';
					
					$html .= '<div class="configuration-cont pull-right">';
						
						// Configuration button
						if ($user->authorise('core.admin', 'com_faqbookpro')) 
						{  
							$html .= '<a class="btn-configuration" href="index.php?option=com_config&view=component&component=com_faqbookpro&path=&return='.base64_encode(JURI::getInstance()->toString()).'">';
								$html .= '<i class="fa fa-gear"></i>'.JText::_('COM_FAQBOOKPRO_CONFIGURATION');
							$html .= '</a>';
						}
							
					$html .= '</div>' . PHP_EOL;	
					
					$html .= '<div class="configuration-cont upgrade-cont pull-right">';
						
						// Upgrade button
						$html .= '<a class="btn-configuration btn-upgrade" href="https://www.minitek.gr/joomla-extensions/minitek-faq-book#subscriptionPlans" target="_blank">';
							$html .= '<i class="fa fa-download"></i>'.JText::_('COM_FAQBOOKPRO_UPGRADE');
						$html .= '</a>';						
							
					$html .= '</div>' . PHP_EOL;
		
				$html .= '</div>' . PHP_EOL;	
			
			$html .= '</div>' . PHP_EOL;	
			
		$html .= '</div>' . PHP_EOL;

		
		return $html;
	}
	  
	/**
	* Gets a list of the actions that can be performed.
	*
	* @param   string   $component  The component name.
	* @param   string   $section    The access section name.
	* @param   integer  $id         The item ID.
	*
	* @return  JObject
	*
	* @since   3.2
	*/
	public static function getActions($component = '', $section = '', $id = 0)
	{
		$user    = JFactory::getUser();
		$result    = new JObject;

		$path = JPATH_ADMINISTRATOR . '/components/' . $component . '/access.xml';
		
		if ($section && $id)
		{
			$assetName = $component . '.' . $section . '.' . (int) $id;
		}
		else
		{
			$assetName = $component;
		}

		$actions = JAccess::getActionsFromFile($path, "/access/section[@name='component']/");
		
		foreach ($actions as $action)
		{
			$result->set($action->name, $user->authorise($action->name, $assetName));
		}
		
		return $result;
	}
	
	public static function getAuthorisedTopics($action)
	{
		// Brute force method: get all published topic rows for the component and check each one
		// TODO: Modify the way permissions are stored in the db to allow for faster implementation and better scaling
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('t.id AS id, a.name AS asset_name')
			->from('#__minitek_faqbook_topics AS t')
			->join('INNER', '#__assets AS a ON t.asset_id = a.id')
			->where('t.published = 1');
		$db->setQuery($query);
		$allTopics = $db->loadObjectList('id');
		$allowedTopics = array();

		foreach ($allTopics as $topic)
		{
			if (JFactory::getUser()->authorise($action, $topic->asset_name))
			{
				$allowedTopics[] = (int) $topic->id;
			}
		}

		return $allowedTopics;
	}
	
	public static function getAllSectionsIds()
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('id')
			->from('#__minitek_faqbook_sections')
			->where('state = 1');
		$db->setQuery($query);
		$sections = $db->loadObjectList('id');

		return $sections;
	}
	
	public static function getCheckSectionMenuItem($sectionId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('COUNT(*)')
			->from('#__menu')
			->where('published = 1')
			->where('link='.$db->quote('index.php?option=com_faqbookpro&view=section&id='.$sectionId));
		$db->setQuery($query);
		$count = $db->loadResult();

		return $count;
	}
	
	public static function getSectionTitle($sectionId)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('title')
			->from('#__minitek_faqbook_sections')
			->where('id = '.$db->quote($sectionId));
		$db->setQuery($query);
		$section = $db->loadObject();

		return $section;
	}
	
	public static function getManagers()
	{
		static $cache = array();

		//AFAIK there is no way to get the list of users enabled to do something, so we have to improvise
		$db		   = JFactory::getDbo();
		$users 	   = array();
		$allowed   = array();
		$managers  = array();

		// First, let's get the whole list of groups
		$query = $db->getQuery(true)
					->select('id')
					->from('#__usergroups')
					->order('id DESC');
		$groups = $db->setQuery($query)->loadColumn();

		// Then check if they can admin tickets
		foreach ($groups as $group)
		{
			if (JAccess::checkGroup($group, 'core.admin', 'com_faqbookpro') || JAccess::checkGroup($group, 'core.manage', 'com_faqbookpro'))
			{
				//If so, let's get all the users
				$users = array_merge($users, JAccess::getUsersByGroup($group));
			}
		}

		// Let's check if any user has the right privileges
		foreach($users as $user)
		{
			$juser = JUser::getInstance($user);
			if ($juser->authorise('core.admin', 'com_faqbookpro') 	|| $juser->authorise('core.manage', 'com_faqbookpro'))
			{
				$allowed[] = $user;
			}
		}

		if($allowed)
		{
			$query = $db->getQuery(true)
						->select('id, name')
						->from('#__users')
						->where('id IN('.implode(',', $allowed).')')
						->where('block = 0');
			$managers = $db->setQuery($query)->loadObjectList('id');
		}

		return $managers;
	}
		
	public function currentVersion()
	{	
		$params  = JComponentHelper::getParams('com_faqbookpro');
		
		if ($params->get('version_check', 1)) 
		{
			if (self::isDomainAvailable('http://update.minitek.gr'))
			{
				if (self::isXMLAvailable('http://update.minitek.gr/joomla-extensions/minitek_faqbook.xml'))
				{
					$xml_file = file_get_contents('http://update.minitek.gr/joomla-extensions/minitek_faqbook.xml');
					if ($xml_file)
					{
						$updates = new SimpleXMLElement($xml_file);
						$version = (string)$updates->update[0]->version;
					} else {
						$version = 0;
					}
				}
				else
				{
					$version = 0;
				}
			}
			else
			{
				$version = 0;
			}
		}
		else
		{
			$version = 0;
		}
		
		return $version;
	}

	public function localVersion()
	{
		$xml = JFactory::getXML(JPATH_ADMINISTRATOR .'/components/com_faqbookpro/faqbookpro.xml');
		$version = (string)$xml->version;
	
		return $version;
	}
	
	function isDomainAvailable($domain)
   	{
		//check, if a valid url is provided
		if(!filter_var($domain, FILTER_VALIDATE_URL))
		{
			   return false;
		}
		
		//initialize curl
		$curlInit = curl_init($domain);
		curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
		curl_setopt($curlInit,CURLOPT_HEADER,true);
		curl_setopt($curlInit,CURLOPT_NOBODY,true);
		curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);
		
		//get answer
		$response = curl_exec($curlInit);
		
		curl_close($curlInit);
		
		if ($response) return true;
		
		return false;
   	}
	
	function isXMLAvailable($file)
   	{
		$ch = curl_init($file);

		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$response = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		curl_close($ch);
		
		if ($response >= 400)
		{
			return false;
		}
		else if ($response = 200)
		{
			return true;
		}
		else
		{
			return false;	
		}
	}
}
