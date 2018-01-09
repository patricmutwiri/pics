<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

//var_dump($this->data);
//var_dump($this->data);
?>
<div class="profile<?php echo $this->pageclass_sfx?>">
<!-- custom view -->
<section class="profile module hero-banner hero-banner--cinema hero-banner--secondary hero-banner--dark-text" data-controller="heroBanner">
	<div class="grid-construct">
		<div class="top hero-banner__container" data-lg-src="/images/author_profile/bg_320px.png" data-sm-md-src="/images/author_profile/bg_320px.png" data-sm-src="/images/author_profile/bg_320px.png" style="background-image: url(&quot;/images/author_profile/bg_320px.png&quot;); height: 120px; background-size: 1340px 1005px;">
			<div class="hero-banner__inner">
				<div class="hero-banner__content">
				<div class="empty-profile">
				<h1 class="hero-banner__heading">Welcome to PerfectPics</h1>
				</div>
					<div class="profile-img">
					<a href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id=' . (int) $this->data->id);?>" class="update-btn" rel="nofollow">
						<img alt="Steps_avatar" class="no-image center-block" src="./images/steps_avatar.png" border="0">
					</a></div>
					<h1 class="hero-banner__heading"><?php echo $this->data->username;?></h1>
					<div class="hero-banner__btns">
					<?php if (JFactory::getUser()->id == $this->data->id) : ?>
						<a href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id=' . (int) $this->data->id);?>" class="update-btn" rel="nofollow"><i class="fa fa-pencil" aria-hidden="true"></i>
						Update Profile
						</a>
					<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="about-section">
<section class="module book-list">
	<div class="grid-construct book-list__header">
		<section class="module hero-banner hero-banner--dark-text no-books" data-controller="heroBanner">
			<div class="grid-construct">
				<div class="hero-banner__container">
					<div class="hero-banner__inner">
						<div class="hero-banner__content">
						<h1 class="hero-banner__heading">You have no PDF-to-books to display</h1>
							<p class="hero-banner__text">
							When you <a href="create/upload">make your first book</a>, it will be showcased here.
							</p>
							<div class="add-btn">
								<a href="create/upload" class="book-list__img-container" rel="nofollow"><div class="book-list__book-img-container">
								<i class="icon icon--add-book"></i>
								</div>
								</a>
							</div>
						<p></p>
						</div>
					</div>
				</div>
			</div>
	</section>

</div>
</section>
</section>
<!-- end custom view -->
<? /*
<?php if ($this->params->get('show_page_heading')) : ?>
<div class="page-header hidden">
	<h1>
		<?php echo $this->escape($this->params->get('page_heading')); ?>
	</h1>
</div>
<?php endif; ?>
<?php if (JFactory::getUser()->id == $this->data->id) : ?>
<ul class="btn-toolbar pull-right hidden">
	<li class="btn-group">
		<a class="btn" href="<?php echo JRoute::_('index.php?option=com_users&task=profile.edit&user_id=' . (int) $this->data->id);?>">
			<span class="icon-user"></span> <?php echo JText::_('COM_USERS_EDIT_PROFILE'); ?></a>
	</li>
</ul>
<?php endif; ?>
<?php echo $this->loadTemplate('core'); ?>

<?php echo $this->loadTemplate('params'); ?>

<?php echo $this->loadTemplate('custom'); ?>
*/?>
</div>
