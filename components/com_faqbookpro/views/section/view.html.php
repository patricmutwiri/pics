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

use Joomla\Registry\Registry;

jimport('joomla.application.component.view');
 
class FaqBookProViewSection extends JViewLegacy
{
  	function display($tpl = null) 
  	{
		JPluginHelper::importPlugin('content');
		$document = JFactory::getDocument();
	  	$app = JFactory::getApplication();	
		$this->user = JFactory::getUser();	
		$sectionId = $app->input->get('id', false);
		$this->model = $this->getModel();
		
		// Check if page exists
		if (empty($sectionId) || !$this->model->getSection($sectionId)) 
		{ 
			JError::raiseError(404, JText::_('COM_FAQBOOKPRO_ERROR_SECTION_NOT_FOUND'));
		}	
		
		// Get access levels
		$authorised = $this->model->authorizeSection($sectionId);
		if (!$authorised)
		{
			if ($this->user->id)
			{
				JError::raiseError(403, JText::_('JERROR_ALERTNOAUTHOR'));
	
				return false;
			}
			else
			{
				$app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');	
				$app->redirect(JRoute::_('index.php?option=com_users&view=login'));
			}
		}	
						
		$this->section = $this->model->getSection($sectionId);
	
		// Get Params & Attribs
		$this->utilities = $this->model->utilities;
		$this->params = $this->utilities->getParams('com_faqbookpro');
		$sectionAttribs = json_decode($this->section->attribs, false);
		
		$navigation = $this->model->navigation;
		
		// Get Top Navigation
		if (isset($sectionAttribs->topnav))
		{
			$topnav = $sectionAttribs->topnav;
		}
		else
		{
			$topnav = true;	
		}
		$this->topnavigation = false;
		
		if ($topnav)
		{
			$this->topnavigation = $navigation->getTopNav($sectionId);
		}
		
		// Get Left Navigation
		$leftnav = $sectionAttribs->leftnav;
		$this->leftnavigation = $navigation->getLeftNav($sectionId);
		
		if ($leftnav)
		{
		  	$this->content_class = 'fbpContent_core';
		} 
		else 
		{
		  	$this->content_class = 'fbpContent_core noleftnav';
		}
		
		// Load Endpoint Topics / All Topics
		$this->loadAllTopics = 1;
		if (isset($sectionAttribs->load_all_topics))
		{
			$this->loadAllTopics = $sectionAttribs->load_all_topics;
		}
		
		// Section Description
		if (isset($sectionAttribs->show_section_desc))
		{
			$this->showDescription = $sectionAttribs->show_section_desc;
		}
		else
		{
			$this->showDescription = true;	
		}
		$this->sectionDescription = $this->section->description;
		
		// Get Active topic
		$this->active_topic = false;
		$topicId = 0;
		if (isset($sectionAttribs->show_active_topic) && $sectionAttribs->show_active_topic 
		&& isset($sectionAttribs->topicid) && $sectionAttribs->topicid)
		{
			$this->active_topic = true;
			$topicModel = JModelLegacy::getInstance('Topic', 'FAQBookProModel'); 
			$this->topic = $topicModel->getTopic($sectionAttribs->topicid);
			$topicParams = json_decode($this->topic->params, false);
			$this->topic->topicImage = $topicParams->image;
			$this->topic->topicImageAlt = $topicParams->image_alt;
			$topicId = $sectionAttribs->topicid;
			
			// Show questions opened
			$this->params->questions_opened = $this->params->get('questions_opened', false);
			if (isset($sectionAttribs->section_questions_opened) && $sectionAttribs->section_questions_opened != '')
			{
				$this->params->questions_opened = $sectionAttribs->section_questions_opened;
			}
			if (isset($topicParams->topic_questions_opened) && $topicParams->topic_questions_opened != '')
			{
				$this->params->questions_opened = $topicParams->topic_questions_opened;
			}
		
			// Topic title
			if ($topicParams->topic_title == '')
			{
				$this->params->topic_title = $this->params->get('topic_title', '1');
			}
			else
			{
				$this->params->topic_title = $topicParams->topic_title;
			}
			
			// Topic description
			if ($topicParams->topic_description == '')
			{
				$this->params->topic_description = $this->params->get('topic_description', '1');
			}
			else
			{
				$this->params->topic_description = $topicParams->topic_description;
			}
			
			// Topic image
			if ($topicParams->topic_image == '')
			{
				$this->params->topic_image = $this->params->get('topic_image', '1');
			}
			else
			{
				$this->params->topic_image = $topicParams->topic_image;
			}
	
			// Topic image width
			if (!isset($topicParams->topic_imageSize) || !$topicParams->topic_imageSize)
			{
				$this->params->topic_image_width = $this->params->get('topic_imageSize', '300');
			}
			else
			{
				$this->params->topic_image_width = $topicParams->topic_imageSize;
			}
			
			// Topic image height
			if (!isset($topicParams->topic_imageHeight) || !$topicParams->topic_imageHeight)
			{
				$this->params->topic_image_height = $this->params->get('topic_imageHeight', '225');
			}
			else
			{
				$this->params->topic_image_height = $topicParams->topic_imageHeight;
			}
			
			// Sub-topics
			if ($topicParams->show_subtopics == '')
			{
				$this->params->show_subtopics = $this->params->get('show_subtopics', '1');
			}
			else
			{
				$this->params->show_subtopics = $topicParams->show_subtopics;
			}
		
			// Sub-topics title
			if ($topicParams->subtopics_title == '')
			{
				$this->params->subtopics_title = $this->params->get('subtopics_title', '1');
			}
			else
			{
				$this->params->subtopics_title = $topicParams->subtopics_title;
			}
			
			// Sub-topics description
			if ($topicParams->subtopics_description == '')
			{
				$this->params->subtopics_description = $this->params->get('subtopics_description', '1');
			}
			else
			{
				$this->params->subtopics_description = $topicParams->subtopics_description;
			}
			
			// Sub-topics image
			if ($topicParams->subtopics_image == '')
			{
				$this->params->subtopics_image = $this->params->get('subtopics_image', '1');
			}
			else
			{
				$this->params->subtopics_image = $topicParams->subtopics_image;
			}
			
			// Sub-topics image width
			if (!isset($topicParams->subtopics_imageSize) || !$topicParams->subtopics_imageSize)
			{
				$this->params->subtopics_image_width = $this->params->get('subtopics_image_width', '300');
			}
			else
			{
				$this->params->subtopics_image_width = $topicParams->subtopics_imageSize;
			}
			
			// Sub-topics image height
			if (!isset($topicParams->subtopics_imageHeight) || !$topicParams->subtopics_imageHeight)
			{
				$this->params->subtopics_image_height = $this->params->get('subtopics_image_height', '225');
			}
			else
			{
				$this->params->subtopics_image_height = $topicParams->subtopics_imageHeight;
			}
			
			// Sub-topics questions
			if ($topicParams->subtopics_faqs == '')
			{
				$this->params->subtopics_faqs = $this->params->get('subtopics_faqs', '1');
			}
			else
			{
				$this->params->subtopics_faqs = $topicParams->subtopics_faqs;
			}
			
			// Get Questions
			$questions_ordering = $this->params->get('questions_ordering', 'id');
			$questions_ordering_dir = $this->params->get('questions_ordering_dir', 'DESC');
			$this->topic->topicQuestions = $topicModel->getTopicQuestions($sectionAttribs->topicid, $questions_ordering, $questions_ordering_dir, $page = 1);
			
			// Extra Question data
			foreach ($this->topic->topicQuestions as $key=>$item)
			{
				// Image
				$item->images = json_decode($item->images, true);
								
				// Pre-text
				$item->pretext = $this->utilities->getWordLimit($item->introtext, $this->params->get('questions_text_limit', '20'));
				$item->pretext = preg_replace('/\{.*\}/', '', $item->pretext);
				$item->pretext = preg_replace('/\[.*\]/', '', $item->pretext);
				
				// Text
				$item->finaltext = $item->introtext.' '.$item->fulltext;
				$item->finaltext = JHtml::_('content.prepare', $item->finaltext);
			}
			
			// Get sub-topics
			if ($this->params->show_subtopics) 
			{
				$this->topic->subTopics = $this->model->getTopicChildren($sectionAttribs->topicid);
				
				// Extra sub-topic data
				foreach ($this->topic->subTopics as $key=>$subtopic)
				{
					// Questions
					$subtopic->questions = $topicModel->getTopicQuestions($subtopic->id, $questions_ordering, $questions_ordering_dir, $page = 1);
					
					foreach ($subtopic->questions as $subquestion)
					{
						// Image
						$subquestion->images = json_decode($subquestion->images, true);
												
						// Pre-text
						$subquestion->pretext = $this->utilities->getWordLimit($subquestion->introtext, $this->params->get('questions_text_limit', '20'));
						$subquestion->pretext = preg_replace('/\{.*\}/', '', $subquestion->pretext);
						$subquestion->pretext = preg_replace('/\[.*\]/', '', $subquestion->pretext);
						
						// Text
						$subquestion->finaltext = $subquestion->introtext.' '.$subquestion->fulltext;
						$subquestion->finaltext = JHtml::_('content.prepare', $subquestion->finaltext);
					}
				}
			}
		}
				
		// Get popular topics
		$this->show_popular_topics = $sectionAttribs->show_popular_topics;
		
		$this->popular_topics = false;
		if ($this->show_popular_topics)
		{
			if (isset($sectionAttribs->popular_topics_header))
			{
				$this->popular_topics_header = $sectionAttribs->popular_topics_header;
			}
			
			$this->popular_topics_cols = $sectionAttribs->popular_topics_cols;
			$this->popular_topics_count = $sectionAttribs->popular_topics_count;
			$this->popular_topics_title = $sectionAttribs->popular_topics_title;
			$this->popular_topics_desc = $sectionAttribs->popular_topics_description;
			$this->popular_topics_desc_limit = $sectionAttribs->popular_topics_description_limit;
			$this->popular_topics_image = $sectionAttribs->popular_topics_image;
			$this->popular_topics_image_width = $sectionAttribs->popular_topics_image_width;
			$this->popular_topics_image_height = $sectionAttribs->popular_topics_image_height;
			$this->popular_topics = $this->model->getPopularTopics($sectionId, $this->popular_topics_count);
		}
				
		// Get all topics
		$this->show_all_topics = $sectionAttribs->show_all_topics;
		
		if ($this->show_all_topics)
		{	
			if (isset($sectionAttribs->all_topics_header))
			{
				$this->all_topics_header = $sectionAttribs->all_topics_header;
			}
			
			$this->all_topics_icons = 0;
			if (isset($sectionAttribs->all_topics_icons))
			{
				$this->all_topics_icons = $sectionAttribs->all_topics_icons;
			}
					
			$this->all_topics_levels = $sectionAttribs->all_topics_levels;
			$this->all_topics_cols = $sectionAttribs->all_topics_cols;
			$this->first_level_topics = $this->model->getSectionTopics($sectionId);
		}
		
		// Get javascript variables
		$page_view = $app->input->get('view', false);
		$page_title = $document->getTitle();
		$section_link = JRoute::_(FaqBookProHelperRoute::getSectionRoute($sectionId));	 
		 	
		$document->addScriptDeclaration(
		'window.fbpvars = {
			token: "'.JSession::getFormToken().'",
			site_path: "'.JURI::root().'",
			page_view: "'.$page_view.'",
			page_title: "'.$page_title.'",
			sectionId: "'.$sectionId.'",
			topicId: "'.$topicId.'",
			activeTopic: "'.$topicId.'",
			leftnav: "'.$leftnav.'",
			loadAllTopics: "'.$this->loadAllTopics.'",
			
			thank_you_up: "'.JText::_('COM_FAQBOOKPRO_THANK_YOU_UP').'",
			thank_you_down: "'.JText::_('COM_FAQBOOKPRO_THANK_YOU_DOWN').'",
			already_voted: "'.JText::_('COM_FAQBOOKPRO_ALREADY_VOTED').'",
			why_not: "'.JText::_('COM_FAQBOOKPRO_WHY_NOT').'",
			incorrect_info: "'.JText::_('COM_FAQBOOKPRO_INCORRECT_INFO').'",
			dont_like: "'.JText::_('COM_FAQBOOKPRO_DO_NOT_LIKE').'",
			confusing: "'.JText::_('COM_FAQBOOKPRO_CONFUSING').'",
			not_answer: "'.JText::_('COM_FAQBOOKPRO_NOT_ANSWER').'",
			too_much: "'.JText::_('COM_FAQBOOKPRO_TOO_MUCH').'",
			other: "'.JText::_('COM_FAQBOOKPRO_OTHER').'",
			error_voting: "'.JText::_('COM_FAQBOOKPRO_ERROR_VOTING').'"
		};'
		);
	
		// Set metadata
		$document->setTitle($this->section->title);
		
		if ($this->section->metadesc)
		{
			$document->setDescription($this->section->metadesc);
		}
		elseif ($this->params->get('menu-meta_description'))
		{
			$document->setDescription($this->params->get('menu-meta_description'));
		}
		
		if ($this->section->metakey)
		{
			$document->setMetadata('keywords', $this->section->metakey);
		}
		elseif ($this->params->get('menu-meta_keywords'))
		{
			$document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		
		if (!is_object($this->section->metadata))
		{
			$this->section->metadata = new Registry($this->section->metadata);
		}
		
		$mdata = $this->section->metadata->toArray();

		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$document->setMetadata($k, $v);
			}
		}		
		
		// Menu page display options
		if ($this->params->get('page_heading'))
		{
		  	$this->params->set('page_title', $this->params->get('page_heading'));
		}
		$this->params->set('show_page_title', $this->params->get('show_page_heading'));
																									
		// Check for errors.
		if (count($errors = $this->get('Errors'))) 
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
			  
		// Display the view
		parent::display($tpl);
					
  	}
	
	public static function getTopicsTree($item, $maxLevels, $cols, $level = 1, $icons = 1)
	{ 
		$sectionModel = JModelLegacy::getInstance('Section', 'FAQBookProModel'); 
	
	  	$output = '';

		$subitems = $sectionModel->getTopicChildren($item->id);
		
		$style = '';
		if ($level == 1)
		{
			$style = 'style="width:'.number_format(100/$cols, 1).'%;"';
		}
		
		$output .= '<li '.$style.'>';
		$output .= '<a href="'.JRoute::_(FaqBookProHelperRoute::getTopicRoute($item->id)).'">';
		
		if ($level == 1 && $icons)
		{
			$output .= '<i class="fa fa-folder-o"></i>&nbsp;&nbsp;';	
		}
		if ($level > 2)
		{
			$output .= '&#45; ';	
		}
		$output .= $item->title;
		$output .= '</a>';
		
		if (count($subitems) && $level < $maxLevels)
		{
			$output .= '<ul class="level'.$level.'">';		
			foreach ($subitems as $subitem)
			{
				$output .= self::getTopicsTree($subitem, $maxLevels, $cols, $level + 1, $icons);
			}
			$output .= '</ul>';	
		}
		$output .= '</li>';		
		
		return $output;
	}
				
}