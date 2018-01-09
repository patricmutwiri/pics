<?php
/**
* @title   	     Minitek FAQ Book
* @copyright     Copyright (C) 2011-2016 Minitek, All rights reserved.
* @license       GNU General Public License version 2 or later.
* @author url    https://www.minitek.gr/
* @developer     minitek.gr
*/

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

use Joomla\Registry\Registry;

jimport('joomla.application.component.view');
 
class FaqBookProViewTopic extends JViewLegacy
{
	function display($tpl = null) 
	{
		JPluginHelper::importPlugin('content');
	  	$document = JFactory::getDocument();
	  	$app = JFactory::getApplication();	
		$this->user = JFactory::getUser();	
		$this->topicId = $app->input->get('id', false);
		$this->model = $this->getModel();
		
		// Check if topic exists		
		if (empty($this->topicId) || !$this->model->getTopic($this->topicId)) 
		{ 
			JError::raiseError(404, JText::_('COM_FAQBOOKPRO_ERROR_TOPIC_NOT_FOUND'));
		}
		
		// Get access level for topic
		$authorised = $this->model->authorizeTopic($this->topicId);
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
		
		$this->topic = $this->model->getTopic($this->topicId);
		$this->sectionId = $this->topic->section_id;
		$sectionModel = JModelLegacy::getInstance('Section', 'FAQBookProModel'); 
		$this->section = $sectionModel->getSection($this->sectionId);
		
		// Get Params & Attribs
		$sectionAttribs = json_decode($this->section->attribs, false);
		$this->utilities = $this->model->utilities;
		$this->params = $this->utilities->getParams('com_faqbookpro');
		$topicParams = json_decode($this->topic->params, false);
		$this->topic->topicImage = $topicParams->image;
		$this->topic->topicImageAlt = $topicParams->image_alt;
		
		// Check if we have an active topic
		$this->active_topic = 0;
		if (isset($sectionAttribs->show_active_topic) && $sectionAttribs->show_active_topic 
		&& isset($sectionAttribs->topicid) && $sectionAttribs->topicid)
		{
			$this->active_topic = $sectionAttribs->topicid;
		}
		
		// Add hit
		$this->model->addHit($this->topic->id);
		
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
			$this->topnavigation = $navigation->getTopNav($this->sectionId);
		}
		
		// Get Left Navigation
		$leftnav = $sectionAttribs->leftnav;
		$this->leftnavigation = $navigation->getLeftNav($this->sectionId);
		
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
						
		// Get Questions
		$this->page = $app->input->get('page', '1');
		$questions_ordering = $this->params->get('questions_ordering', 'id');
		$questions_ordering_dir = $this->params->get('questions_ordering_dir', 'DESC');
		$this->topic->topicQuestions = $this->model->getTopicQuestions($this->topicId, $questions_ordering, $questions_ordering_dir, $this->page);
		
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
			$this->topic->subTopics = $sectionModel->getTopicChildren($this->topicId);
			
			// Extra sub-topic data
			foreach ($this->topic->subTopics as $key=>$subtopic)
			{
				// Questions
				$subtopic->questions = $this->model->getTopicQuestions($subtopic->id, $questions_ordering, $questions_ordering_dir, $page = 1);
				
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
		
		// Get javascript variables
		$page_view = JRequest::getVar('view');
		$page_title = $document->getTitle();
		 		
		$document->addScriptDeclaration(
			'window.fbpvars = {
				token: "'.JSession::getFormToken().'",
				site_path: "'.JURI::root().'",
				page_view: "'.$page_view.'",
				page_title: "'.$page_title.'",
				sectionId: "'.$this->sectionId.'",
				topicId: "'.$this->topicId.'",
				activeTopic: "'.$this->active_topic.'",
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
		$document->setTitle($this->topic->title);
		
		if ($this->topic->metadesc)
		{
			$document->setDescription($this->topic->metadesc);
		}
		elseif ($this->params->get('menu-meta_description'))
		{
			$document->setDescription($this->params->get('menu-meta_description'));
		}
		
		if ($this->topic->metakey)
		{
			$document->setMetadata('keywords', $this->topic->metakey);
		}
		elseif ($this->params->get('menu-meta_keywords'))
		{
			$document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}
		
		if (!is_object($this->topic->metadata))
		{
			$this->topic->metadata = new Registry($this->topic->metadata);
		}
		
		$mdata = $this->topic->metadata->toArray();

		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$document->setMetadata($k, $v);
			}
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}
		
		// Display the view
		parent::display($tpl);	
	}
	
}