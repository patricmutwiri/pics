<?php
/**
 * @version    CVS: 1.0.0
 * @package    Com_Orders
 * @author     Michael Buluma <michael@buluma.me.ke>
 * @copyright  2016 Michael Buluma
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');

// Load admin language file
$lang = JFactory::getLanguage();
$lang->load('com_orders', JPATH_SITE);
$doc = JFactory::getDocument();
$doc->addScript(JUri::base() . '/media/com_orders/js/form.js');

$user    = JFactory::getUser();
$canEdit = OrdersHelpersOrders::canUserEdit($this->item, $user);

// var_dump($user);
var_dump($this->item);
?>

<div class="grid-construct order-edit front-end-edit">
	<?php if (!$canEdit) : ?>
		<h3>
			<?php throw new Exception(JText::_('COM_ORDERS_ERROR_MESSAGE_NOT_AUTHORISED'), 403); ?>
		</h3>
	<?php else : ?>
		<?php if (!empty($this->item->id)): ?>
			<h1><?php echo JText::sprintf('COM_ORDERS_EDIT_ITEM_TITLE', $this->item->id); ?></h1>
		<?php else: ?>
			<h1><?php echo JText::_('COM_ORDERS_ADD_ITEM_TITLE'); ?></h1>
		<?php endif; ?>

		<form id="form-order"
			  action="<?php echo JRoute::_('index.php?option=com_orders&task=order.save'); ?>"
			  method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

	<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>" />
	<input type="hidden" name="jform[ordering]" value="<?php echo $this->item->ordering; ?>" />
	<input type="hidden" name="jform[state]" value="<?php echo $this->item->state; ?>" />
	<input type="hidden" name="jform[checked_out]" value="<?php echo $this->item->checked_out; ?>" />
	<input type="hidden" name="jform[checked_out_time]" value="<?php echo $this->item->checked_out_time; ?>" />

				<?php echo $this->form->getInput('created_by'); ?>
				<?php echo $this->form->getInput('modified_by'); ?>
	<?php //echo $this->form->renderField('customers_name'); ?>

	<div class="control-group">
			<div class="control-label">
				<label id="jform_customers_name-lbl" for="jform_customers_name" class="hasPopover" title="" data-content="Autopopulated" data-original-title="Customers Name">
		Customers Name</label>
	</div>
			<div class="controls">
				<input type="text" name="jform[customers_name]" id="jform_customers_name" value="<?php echo $user->name;?>" readonly="true">
			</div>
	</div>

	<div class="control-group">
			<div class="control-label">
				<label id="jform_customers_email-lbl" for="jform_customers_email" class="hasPopover" title="" data-content="Autopopulated" data-original-title="Customers Email">
		Customers Email</label>
	</div>
			<div class="controls">
				<input type="text" name="jform[customers_email]" id="jform_customers_email" value="<?php echo $user->email;?>" readonly="true">
			</div>
	</div>


	<?php //echo $this->form->renderField('customers_email'); ?>
	<?/*<div class="control-group">
			<div class="control-label"><label id="jform_customers_phone-lbl" for="jform_customers_phone" class="hasPopover" title="" data-content="Enter customers_phone" data-original-title="Customers Phone">
	Customers Phone</label>
</div>
		<div class="controls"><input type="text" name="jform[customers_phone]" id="jform_customers_phone" value="<?php echo $user->phone;?>" placeholder="Customers Phone"></div>
</div>*/?>

	<?php echo $this->form->renderField('customers_phone'); ?>

	<?php echo $this->form->renderField('upload_pdf'); ?>

	<?php if (!empty($this->item->upload_pdf)) :
		foreach ((array) $this->item->upload_pdf as $singleFile) :
			if (!is_array($singleFile)) :
				echo '<a href="' . JRoute::_(JUri::root() . 'uploads' . DIRECTORY_SEPARATOR . $singleFile, false) . '">' . $singleFile . '</a> ';
			endif;
		endforeach;
	endif; ?>
	<input type="hidden" name="jform[upload_pdf_hidden]" id="jform_upload_pdf_hidden" value="<?php echo str_replace('Array,', '', implode(',', (array) $this->item->upload_pdf)); ?>" />
	<?php echo $this->form->renderField('book_size'); ?>

	<?php echo $this->form->renderField('cover_type'); ?>

	<?php echo $this->form->renderField('paper_type'); ?>

	<?php echo $this->form->renderField('book_title'); ?>

	<?php echo $this->form->renderField('author_name'); ?>

	<?php echo $this->form->renderField('category_title'); ?>

	<?php //echo $this->form->renderField('payment_status'); ?>

	<?php //echo $this->form->renderField('order_status'); ?>
				<?/*<div class="fltlft" <?php if (!JFactory::getUser()->authorise('core.admin','orders')): ?> style="display:none;" <?php endif; ?> >
                <?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
                <?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
                <fieldset class="panelform">
                    <?php echo $this->form->getLabel('rules'); ?>
                    <?php echo $this->form->getInput('rules'); ?>
                </fieldset>
                <?php echo JHtml::_('sliders.end'); ?>
            </div>*/?>
				<?php if (!JFactory::getUser()->authorise('core.admin','orders')): ?>
                <script type="text/javascript">
                    jQuery.noConflict();
                    jQuery('.tab-pane select').each(function(){
                       var option_selected = jQuery(this).find(':selected');
                       var input = document.createElement("input");
                       input.setAttribute("type", "hidden");
                       input.setAttribute("name", jQuery(this).attr('name'));
                       input.setAttribute("value", option_selected.val());
                       document.getElementById("form-order").appendChild(input);
                    });
                </script>
             <?php endif; ?>
			<div class="control-group">
				<div class="controls">

					<?php if ($this->canSave): ?>
						<button type="submit" class="validate btn btn-primary">
							<?php echo JText::_('JSUBMIT'); ?>
						</button>
					<?php endif; ?>
					<a class="btn"
					   href="<?php echo JRoute::_('index.php?option=com_orders&task=orderform.cancel'); ?>"
					   title="<?php echo JText::_('JCANCEL'); ?>">
						<?php echo JText::_('JCANCEL'); ?>
					</a>
				</div>
			</div>

			<input type="hidden" name="option" value="com_orders"/>
			<input type="hidden" name="task"
				   value="orderform.save"/>
			<?php echo JHtml::_('form.token'); ?>
		</form>
	<?php endif; ?>
</div>
