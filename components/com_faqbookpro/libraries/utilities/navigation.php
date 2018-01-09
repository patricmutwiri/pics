<?php
/**
* @title			Minitek FAQ Book
* @copyright   		Copyright (C) 2011-2015 Minitek, All rights reserved.
* @license   		GNU General Public License version 3 or later.
* @author url   	http://www.minitek.gr/
* @developers   	Minitek.gr
*/

// no direct access
defined('_JEXEC') or die ;

class FaqBookProLibUtilitiesNavigation
{

	public static function getTopNav($sectionId)
	{
		$sectionModel = JModelLegacy::getInstance('Section', 'FAQBookProModel');
		$sectionTitle = $sectionModel->getSection($sectionId)->title;

		$utilities = new FAQBookProLibUtilities;
		$params = $utilities->getParams('com_faqbookpro');
		$sectionAttribs = json_decode($sectionModel->getSection($sectionId)->attribs, false);
		$user = JFactory::getUser();

      	$output = '';
	  	$output .= '<div class="fbpTopNavigation_core_outer">';

			$output .= '<div class="fbpTopNavigation_core">';

				$output .= '<div class="fbpTopNavigation_wrap col-md-offset-1">';

					$output .= '<ul class="fbpTopNavigation_root fpb-hidden-phone">';

						if ($params->get('home_link'))
						{
							$output .= '<li class="NavTopUL_home">';
								$output .= '<a class="NavTopUL_link" href="'.JRoute::_(FaqBookProHelperRoute::getSectionsRoute()).'">';
									$output .= '<i class="fa fa-home NavTopUL_homeIcon"></i>&nbsp;&nbsp;';
									$output .= JText::_('COM_FAQBOOKPRO_HOME_LINK');
								$output .= '</a>';
							$output .= '</li>';
						}

						$output .= '<li id="top_liid_home" class="NavTopUL_item NavTopUL_firstChild NavTopUL_lastChild">';
							$output .= '<a class="NavTopUL_link" href="'.JRoute::_(FaqBookProHelperRoute::getSectionRoute($sectionId)).'" onclick="return false;">';
								if ($params->get('home_link'))
								{
									$output .= '<i class="fa fa-caret-right NavTopUL_homeIcon"></i>&nbsp;&nbsp;';
								}
								else
								{
									$output .= '<i class="fa fa-home NavTopUL_homeIcon"></i>&nbsp;&nbsp;';
								}
								$output .= $sectionTitle;
							$output .= '</a>';
						$output .= '</li>';

					$output .= '</ul>';
					$output .= '<span class="NavTopUL_loading"></span>';

				$output .= '</div>';

				$output .= '<div class="fbpNavTopUL_buttons">';

					// Menu icon
					if ($sectionAttribs->leftnav)
					{
						$output .= '<div class="show_menu"><a href="#" onclick="return false;" class="btn btn-default"><i class="fa fa-bars"></i></a></div>';
					}

				$output .= '</div>';

			$output .= '</div>';

		$output .= '<div class="clearfix"> </div>';

		$output .= '</div>';

		return $output;
	}

	public static function getLeftNav($sectionId)
	{
		$utilities = new FAQBookProLibUtilities;
		$params = $utilities->getParams('com_faqbookpro');
		$sectionModel = JModelLegacy::getInstance('Section', 'FAQBookProModel');
		$section = $sectionModel->getSection($sectionId);
		$sectionAttribs = json_decode($section->attribs, false);
		$user = JFactory::getUser();
		$items = $sectionModel->getSectionTopics($sectionId);
		$leftnav = $sectionAttribs->leftnav;
		if (!$leftnav)
		{
			$leftnav_class = 'leftnav-hidden';
		}
		else
		{
			$leftnav_class = '';
		}

		if ($items)
		{
			foreach ($items as $item)
			{
				$topicsTree = self::getTopicsTree($item);
				$topics_tree[] = $topicsTree;
			}

			$output = '';
			$output .= '<div class="fbpLeftNavigation_core fbp-hidden col-md-offset-1 '.$leftnav_class.'">';

				$output .= '<div class="fbpLeftNavigation_root">';

					$output .= '<div id="fbp_l_n" class="fbpLeftNavigation_wrap">';
						$output .= '<ul id="NavLeftUL" class="NavLeftUL_parent level0">';

						foreach ($topics_tree as $topic_tree)
						{
							$output .= $topic_tree;
						}

						$output .= '</ul>';
					$output .= '</div>';

			$output .= '</div>';
			$output .= '</div>';

			return $output;
		}
	}

	public static function getTopicsTree($item, $level = 1)
	{
		$sectionModel = JModelLegacy::getInstance('Section', 'FAQBookProModel');

	  	$output = '';

		$subitems = $sectionModel->getTopicChildren($item->id);

		if (count($subitems))
		{
			$depth = self::getTopicDepth($item, $levels = 0);
			if ($depth === 0)
			{
			  	$item_class = ' NavLeftUL_endpoint';
				$span_class = 'NavLeftUL_endpointIcon';
			}
			else
			{
			  	$item_class = '';
				$span_class = 'NavLeftUL_navIcon fa fa-chevron-right';
			}
			$output .= '<li id="liid'.$item->id.'" class="NavLeftUL_item'.$item_class.'"><a href="'.JRoute::_(FaqBookProHelperRoute::getTopicRoute($item->id)).'" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;"><span class="catTitle">'.$item->title.'<span class="'.$span_class.'"></span></span></a>';
			$output .= '<ul class="NavLeftUL_sublist level'.$level.'">';
			foreach ($subitems as $subitem)
			{
			  	$output .= self::getTopicsTree($subitem, $level + 1);
			}
			$output .= '<li id="backliid'.$item->id.'" class="NavLeftUL_backItem"><a href="#" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;"><span>'.JText::_('COM_FAQBOOKPRO_BACK').'<span class="NavLeftUL_navBackIcon fa fa-mail-reply"></span></span></a></li>';
			$output .= '</ul>';
			$output .= '</li>';
		}
		else
		{
		  	$output .= '<li id="liid'.$item->id.'" class="NavLeftUL_item NavLeftUL_endpoint"><a href="'.JRoute::_(FaqBookProHelperRoute::getTopicRoute($item->id)).'" class="NavLeftUL_anchor" rel="nofollow" onclick="return false;"><span class="catTitle">'.$item->title.'<span class="NavLeftUL_endpointIcon"></span></span></a>';
			$output .= '</li>';
		}

		return $output;
	}

	public static function getTopicDepth($item, $levels)
	{
		$sectionModel = JModelLegacy::getInstance('Section', 'FAQBookProModel');
		$children = $sectionModel->getTopicChildren($item->id);

		if (count($children))
		{
		  	$levels++;
		  	foreach ($children as $child)
			{
			  	if (count($sectionModel->getTopicChildren($child->id)))
				{
					return;
				}
			}
		}

		return $levels;
  	}

}
