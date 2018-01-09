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

$app = JFactory::getApplication();

if ($app->isSite())
{
	JSession::checkToken('get') or die(JText::_('JINVALID_TOKEN'));
}

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers/html');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.framework', true);
JHtml::_('formbehavior.chosen', 'select');

$function  = $app->input->getCmd('function', 'jSelectQuestion');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn  = $this->escape($this->state->get('list.direction'));
?>
										
<form action="<?php echo JRoute::_('index.php?option=com_faqbookpro&view=questions&layout=modal&tmpl=component&function=' . $function . '&' . JSession::getFormToken() . '=1'); ?>" method="post" name="adminForm" id="adminForm">

	<div id="j-main-container">
		
		<?php
		// Search tools bar
		echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
		?>
		
		<div class="clearfix"> </div>
		
		<?php if (empty($this->items)) : ?>
		
			<div class="alert alert-no-items">
				<?php echo JText::_('JGLOBAL_NO_MATCHING_RESULTS'); ?>
			</div>
			
		<?php else : ?>
		
			<table class="table table-striped table-condensed" id="articleList">
				<thead>
					<tr>
						<th class="title">
							<?php echo JHtml::_('searchtools.sort', 'JGLOBAL_TITLE', 'a.title', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort',  'JGRID_HEADING_ACCESS', 'a.access', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort',  'JAUTHOR', 'a.created_by', $listDirn, $listOrder); ?>
						</th>
						<th width="5%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn, $listOrder); ?>
						</th>
						<th width="10%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?>
						</th>
						<th width="1%" class="nowrap center hidden-phone">
							<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?>
						</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="6">
						</td>
					</tr>
				</tfoot>
				<tbody>
				<?php foreach ($this->items as $i => $item) : ?>
					<?php if ($item->language && JLanguageMultilang::isEnabled())
					{
						$tag = strlen($item->language);
						if ($tag == 5)
						{
							$lang = substr($item->language, 0, 2);
						}
						elseif ($tag == 6)
						{
							$lang = substr($item->language, 0, 3);
						}
						else {
							$lang = "";
						}
					}
					elseif (!JLanguageMultilang::isEnabled())
					{
						$lang = "";
					}
					?>
					<tr class="row<?php echo $i % 2; ?>">
													
						<td>
							<a href="javascript:void(0)" onclick="if (window.parent) window.parent.<?php echo $this->escape($function); ?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>', '<?php echo $this->escape($item->topicid); ?>', null, '', '<?php echo $this->escape($lang); ?>', null);">
								<?php echo $this->escape($item->title); ?>
							</a>
							<div class="small">
								<span>
									<?php echo JText::_('COM_FAQBOOKPRO_IN')." "; ?>
									<?php echo $this->escape($item->topic_title); ?>
								</span>
							</div>
						</td>
													
						<td class="center">
							<?php echo $this->escape($item->access_level); ?>
						</td>
						
						<td class="center">
							<span>
								<?php echo $this->escape($item->author_name); ?></a>
							</span>
						</td>
						
						<td class="center">
							<?php if ($item->language == '*'):?>
								<?php echo JText::alt('JALL', 'language'); ?>
							<?php else:?>
								<?php echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED'); ?>
							<?php endif;?>
						</td>
						
						<td class="center nowrap">
							<?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
						</td>
												
						<td class="center">
							<?php echo (int) $item->id; ?>
						</td>
						
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif;?>

		<?php echo $this->pagination->getListFooter(); ?>

		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
