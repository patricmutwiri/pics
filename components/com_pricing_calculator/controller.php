<?php
/**
 * @author		
 * @copyright	
 * @license		
 */

defined("_JEXEC") or die("Restricted access");

class Pricing_calculatorController extends JControllerLegacy
{
	/**
	 * The default view for the display method.
	 *
	 * @var    string
	 * @since  12.2
	 */
	protected $default_view = 'pricings';

	/**
     * Method to display a view.
     *
     * @param   boolean If true, the view output will be cached
     * @param   array   An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
     *
     * @return  JController	This object to support chaining.
     * @since   1.5
     */
    public function display($cachable = false, $urlparams = false)
	{
		JForm::addFormPath(JPATH_COMPONENT_ADMINISTRATOR . '/models/forms');
		JForm::addFieldPath(JPATH_COMPONENT_ADMINISTRATOR . '/models/fields');

        // Get the document object.
		$document	= JFactory::getDocument();

		// Set the default view name and format from the Request.
		$vName   = $this->input->getCmd('view', 'pricings');
		$vFormat = $document->getType();
		$lName   = $this->input->getCmd('layout', 'default');
		
		// Get the model and the view
		$model = $this->getModel($vName);
		$view = $this->getView($vName, $vFormat);
		
		// Push the model into the view (as default).
		$view->setModel($model, true);
		$view->setLayout($lName);
		
		// Push document object into the view.
		$view->document = $document;

		// Display the view
		$view->display();
    }

    public function getproduct($id)
    {
    	$id 	= (int)$id;
		$db 	= JFactory::getDbo();
		$query 	= $db->getQuery(true);
		$query 	= $query->select('*')
					->from('#__perfectpics_product')
					->where($db->quoteName('id') .' = '.$db->quote($id));
		$db->setQuery($query);
		$product = $db->loadObject();
		return $product;
    }

    public function getcategory($id)
    {
    	$id 	= (int)$id;
		$db 	= JFactory::getDbo();
		$query 	= $db->getQuery(true);
		$query 	= $query->select('*')
					->from('#__categories')
					->where($db->quoteName('extension') .' = '.$db->quote('com_perfectpics_products'))
					->where($db->quoteName('id') .' = '.$db->quote($id));
		$db->setQuery($query);
		$category = $db->loadObject();
		return $category;
    }

    public function getcoupon($pid=0,$cid=0)
    {
    	$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$user 	= JFactory::getUser();
		$input 	= $app->input;
		$nullDate = $db->quote($db->getNullDate());
		$nowDate  = $db->quote(JFactory::getDate()->toSql());
		$couponcode = $input->getString('couponcode');
		$couponcode = trim($couponcode);
		//loadcoupons
		if($pid > 0 || $cid > 0) {
	    	$query 	= $db->getQuery(true);
			$query 	= $query->select('id,name,percentage,products,categories')
						->from('#__discounts')
						->where('publish_up <= ' . $nowDate . '')
						->where('publish_down >= ' . $nowDate . '')
						->where('type = 0')
						->where('name = "'.$couponcode.'"')
						->where('published = 1');
			$db->setQuery($query);
			$coupons = $db->loadObjectList();
			$perc = array();
			if(!empty($coupons)) {
				if($pid > 0) {
					foreach ($coupons as $key => $coupon) {
						$pids = json_decode($coupon->products,true);
						if(in_array($pid, $pids)) {
							$perc[] = $coupon->percentage;
						}
					}
				} else {
					if($cid > 0) {
						foreach ($coupons as $key => $coupon) {
							$cids = json_decode($coupon->categories);
							if(in_array($cid, $cids)) {
								$perc[] = $coupon->percentage;
							}
						}
					}
				}
				$totaloff = array_sum($perc);
			} else {
				$totaloff = 0;
			}
			return $totaloff;
    	} else {
    		return 0;
    	}
    }

    public function getdiscount($pid=0,$cid=0)
    {
		$app 	= JFactory::getApplication();
		$db 	= JFactory::getDbo();
		$user 	= JFactory::getUser();
		$input 	= $app->input;
		$nullDate = $db->quote($db->getNullDate());
		$nowDate  = $db->quote(JFactory::getDate()->toSql());
		//loadDiscounts
		if($pid > 0 || $cid > 0) {
	    	$query 	= $db->getQuery(true);
			$query 	= $query->select('id,percentage,products,categories')
						->from('#__discounts')
						->where('publish_up <= ' . $nowDate . '')
						->where('publish_down >= ' . $nowDate . '')
						->where('type = 1')
						->where('published = 1');
			$db->setQuery($query);
			$discounts = $db->loadObjectList();
			if(!empty($discounts)) {
				$perc = array();
				if($pid > 0) {
					foreach ($discounts as $key => $discount) {
						$pids = json_decode($discount->products,true);
						if(in_array($pid, $pids)) {
							$perc[] = $discount->percentage;
						}
					}
				} else {
					if($cid > 0) {
						foreach ($discounts as $key => $discount) {
							$cids = json_decode($discount->categories);
							if(in_array($cid, $cids)) {
								$perc[] = $discount->percentage;
							}
						}
					}
				}
				$totaloff = array_sum($perc);
			} else {
				$totaloff = 0;
			}
			return $totaloff;
    	} else {
    		return 0;
    	}
    }

    public function MoreDiscounts()
    {
    	$user 	= JFactory::getUser();
    	$app 	= JFactory::getApplication();
    	$input  = $app->input;
    	$more = array();
    	$d10 	= $app->getParams('com_perfectpics_products')->get('discount10');
    	if(empty($d10)) {
    		$d10 = 0;
    	}
    	$photographers = 0;
		$groups = $user->groups;
		//photographers = 10
		if(in_array(10, $groups)) {
			$photographers = $app->getParams('com_perfectpics_products')->get('photographers');
			if(empty($photographers)) {
				$photographers = 0;
			}
		}
		$more['photographers'] 	= $photographers;
		$coupons = $this->getcoupon($input->getString('prodid'),$input->getString('catid'));
		if($input->get('quan') > 10 && $coupons > 0) {
			$d10 = 0;	
		}
		$more['d10'] 			= $d10;
		return $more;
    }

    public function CalculateDiscount($perc,$price)
    {
    	$amountoff = ($perc/100) * $price;
    	return $amountoff;
    }

    public function saveorder()
    {
    	$app 	= JFactory::getApplication();
    	$db 	= JFactory::getDbo();
    	$user 	= JFactory::getUser();
    	$input 	= $app->input;
    	$config = JFactory::getConfig();

    	if(!$user->id) {
    		$redirectUrl 	= 'index.php?option=com_pricing_calculator&view=pricings&Itemid=586';
    		$joomlaLoginUrl = 'index.php?option=com_users&view=login';
    		$redirectUrl 	= '&return='.urlencode(base64_encode($redirectUrl)); 
    		$finalUrl 		= $joomlaLoginUrl . $redirectUrl;
    		$app->redirect($finalUrl,'Login to place Order Please');
    	} else {
    		if($input->get('quantity') > 99) {
	    		$app->redirect('index.php?option=com_pricing_calculator&view=pricings&Itemid=586','For 100+ Items, contact our Client Services team for full support.');
	    	}
	    	if(empty($input->get('baseprice'))) {
	    		$app->redirect('index.php?option=com_pricing_calculator&view=pricings&Itemid=586','Size Not Available');
	    	}

    		$mailer = JFactory::getMailer();
	    	$order 				= new stdClass();
	    	$order->id 			= NULL;
	    	$order->userid 		= $input->get('userid');
	    	$order->productid 	= $input->get('prodid');
	    	$order->coverid 	= $input->getString('selectedcover');
	    	$order->catid 		= $input->getString('catid');
	    	$order->quantity 	= $input->get('quantity');
	    	$percentageoff 		= $this->getdiscount($input->getString('prodid'),$input->getString('catid'));
			$couponamount 		= 0;
			$coupons 			= $this->getcoupon($input->getString('prodid'),$input->getString('catid'));
	    	$totalprice 		= $input->get('totalprice');
	    	$originalprice 		= $input->get('totalprice');
	    	//normal discounts
	    	if(!empty($percentageoff)) {
	    		$offamount = ($percentageoff/100) * $totalprice;
	    	} else {
	    		$offamount = 0;
	    	}
	    	$totalprice = $totalprice - $offamount;
	    	//coupons discount
	    	if(!empty($coupons)) {
	    		$offamount = ($coupons/100) * $totalprice;
	    	} else {
	    		$offamount = 0;
	    	}
	    	$totalprice = $totalprice - $offamount;
	    	//MoreDiscounts
	    	$morediscounts = $this->MoreDiscounts();
	    	//photos
	    	$photoDiscount 	= $morediscounts['photographers'];
	    	if(!empty($photoDiscount)) {
	    		$offamount      = ($photoDiscount/100) * $totalprice;
	    		$totalprice 	= $totalprice - $offamount;
	    	}	
	    	//>10
	    	$over10 		= 0;
	    	if($input->get('quantity') > 10 && $coupons > 0) {
				$over10 		= 0;
	    		$offamount      = ($over10/100) * $totalprice;
	    		$totalprice 	= $totalprice - $offamount;
	    	} else {
				$over10 		= $morediscounts['d10'];
	    		$offamount      = ($over10/100) * $totalprice;
	    		$totalprice 	= $totalprice - $offamount;
	    	}
	    	$order->totalprice 	= $totalprice;
	    	$order->totalpages 	= $input->get('pagecount');
	    	$order->phone 		= $input->getString('userphone');
	    	$order->state 		= 1;
	    	$order->published	= 1;

	    	if($db->insertObject('#__orders',$order,'id')) {
	    		$message  = '<html><body>';
				$message .= '<table rules="all" style="width:750px;border-color: #666;" cellpadding="10">';
				$message .= '<p><img src="'.JURI::root().'images/perfectpics/logo.jpg" alt="Order Request" /></p>';
				$message .= "<tr style='background: #eee;'><td><strong>Name:</strong> </td><td>" . $user->name . "</td></tr>";
				$message .= "<tr><td><strong>Email:</strong> </td><td>" . $user->email . "</td></tr>";
				$message .= "<tr><td><strong>Phone:</strong> </td><td>" . $input->getString('userphone') . "</td></tr>";
				$message .= "<tr><td><strong>Product:</strong> </td><td>" . $this->getproduct($input->get('prodid'))->product_name.'('. $this->getproduct($input->get('prodid'))->range.')'. "</td></tr>";
				$message .= "<tr><td><strong>Category:</strong> </td><td>" . $this->getcategory($input->get('catid'))->title."</td></tr>";
				
				if(!empty($percentageoff)) {
				$message .= "<tr><td><strong>Discount Percentage Off:</strong> </td><td>" . number_format($percentageoff,2)."% </td></tr>";	
				}
				
				if(!empty($coupons)) {
				$message .= "<tr><td><strong>Coupon Code Percentage Off:</strong> </td><td>" . number_format($coupons,2)."% </td></tr>";
				$message .= "<tr><td><strong>Coupon Code Used:</strong> </td><td>" . $input->get('couponcode') ." </td></tr>";	
				}

				if(!empty($photoDiscount)) {
				$message .= "<tr><td><strong>Photographers Percentage Off:</strong> </td><td>" . number_format($photoDiscount,2)."% </td></tr>";
				}
				
				if(!empty($over10)) {
					$message .= "<tr><td><strong>Bulk Buying Percentage Off:</strong> </td><td>" . number_format($over10,2)."% </td></tr>";	
				}
				$message .= "<tr><td><strong>Cover Type:</strong> </td><td>" . $input->getString('selectedcover')."</td></tr>";
				$message .= "<tr><td><strong>Page Count:</strong> </td><td>" . $input->get('pagecount'). "</td></tr>";
				$message .= "<tr><td><strong>Quantity:</strong> </td><td>" . $input->get('quantity'). "</td></tr>";
				$message .= "<tr><td><strong>Base Price:</strong> </td><td>Ksh. " . number_format($input->get('totalprice'),2) . "</td></tr>";
				if($totalprice < $input->get('totalprice')) {
					$message .= "<tr><td><strong>Discounted Price:</strong> </td><td>Ksh. " . number_format($totalprice,2) . "</td></tr>";
				}
				$message .= "<tr><td><strong><br/>Regards,<br/> ".$config->get('sitename')."<br/> </strong> </td><td>&nbsp;</td></tr>";
				$message .= "</table>";
				$message .= "</body></html>";

	    		$sender = array( 
				    $config->get( 'mailfrom' ),
				    $config->get( 'fromname' ) 
				);

				$mailer->setSubject($config->get('sitename').' - New Order Created by '.ucwords($user->name));
				$mailer->setSender($sender);
				$recipient = $config->get('mailfrom');
				//$recipient = 'pmutwiri@gbc.co.ke';
				$mailer->addRecipient($recipient);
				$mailer->addCc($user->email);
				$mailer->addBcc('pmutwiri@gbc.co.ke');
				$mailer->isHtml(true);
				$mailer->Encoding = 'base64';
				$mailer->setBody($message);
				$andmailed = '';
				if($mailer->Send()) {
					$andmailed = ' and mailed';
				} 
	    		$app->redirect('index.php?option=com_pricing_calculator&view=pricings&Itemid=586','Order Saved'.$andmailed.' Successfully');
	    	}
    	}

    }

    public function getsessioncoupon(){
    	$app 	= JFactory::getApplication();
    	$input 	= $app->input;
    	$pid = $input->get('jspid');
    	$cid = $input->get('jscid');
    	$morediscounts = $this->getcoupon($pid,$cid);
    	echo json_encode($morediscounts,true); 
    }
    
    public function getdessiondiscount(){
    	$app 	= JFactory::getApplication();
    	$input 	= $app->input;
    	$pid = $input->get('jspid');
    	$cid = $input->get('jscid');
    	$morediscounts = $this->getdiscount($pid,$cid);
    	echo json_encode($morediscounts,true); 
    }

    public function getSessionDiscounts(){
    	$app 	= JFactory::getApplication();
    	$input 	= $app->input;
    	$db 	= JFactory::getDbo();
    	$user 	= JFactory::getUser();
    	$config = JFactory::getConfig();
    	$morediscounts = $this->MoreDiscounts();
    	
    	echo json_encode($morediscounts,true); 
    }
}
?>