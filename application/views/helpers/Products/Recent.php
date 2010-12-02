<?php
/**
 * Meme CMS
 *
 * LICENSE
 *
 * This source file is subject to the GPL license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://memecms.com/license/gnu-gpl
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@memecms.com so we can send you a copy immediately.
 *
 * @version		MEME
 * @package		MemeCMS
 * @copyright	Copyright (C) 2009 - 2012 Alessio Pigliacelli, Studio Pigliacelli S.a.s. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt http://memecms.com/license/gnu-gpl
 * @version		$Id: Recent.php 401 2010-11-18 20:25:30Z alex $
 */


class Zend_View_Helper_Products_Recent {

	function Products_Recent($number = 5) {

		$meme = new Admin_Model_MemeStart();
    	$ProductsList = $meme->getProductsList($number);

		$products = '<div id="recent_product"><h3>Recent Products</h3>';
		if(count($ProductsList)):
			foreach($ProductsList as $product):
			
				$products = $products.'<a href="/product/'.$product['product_id'].'/'.$product['product_url'].'">'.$product['product_name'].'</a>' ;		
				
			endforeach;
			
		else :
			$products = $products.'0 product';
		endif;
			
			$products = $products.'</div>';

    	return $products;

	}
	
	
}

?>