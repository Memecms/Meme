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
 * @version		$Id: Lists.php 401 2010-11-18 20:25:30Z alex $
 */


class Zend_View_Helper_Products_Lists {

	function Products_Lists($home = '', $number = 5, $list = null) {

		$meme = new Admin_Model_MemeStart();
		
		if($list == null)
		{
    		$ProductsList = $meme->getProductsList($number, $home);
		}
		else
		{
			$ProductsList = $list;
		}

		$products = '';
		if(count($ProductsList)):
			foreach($ProductsList as $product):
			
				$product_date = new Zend_Date($product['product_date'], Zend_Date::TIMESTAMP);
			
				$products = $products.'<div class="product">';
				$products = $products.'<a href="/product/'.$product['product_id'].'/'.$product['product_url'].'" class="title">'.$product['product_name'].'</a>' ;		
				$products = $products.'<p class="author">Posted on '.$product_date.' <!-- by admin --></p>';
					
					
					if(is_file('meme-media/products/'.$product['product_id'].'/thumb.jpg')){
					$products = $products.'<a href="/product/'.$product['product_id'].'/'.$product['product_url'].'" style="float: left; margin-right: 5px;"><img src="/meme-media/products/'.$product['product_id'].'/thumb.jpg" /></a>';
					}


				$data = $product['product_content'] ;
		 		$data = explode('<!-- pagebreak -->',$data);
		 		$products = $products.$data[0];
		 		
		 		if(isset($data[1]))
		 		{
			 		$products = $products.'<a href="/product/'.$product['product_id'].'/'.$product['product_url'].'" class="more">Read</a>';
		 		}
				
				$categoryShow = '';
				
				foreach($product['product_category'] as $category): 
	
					$categoryShow = $categoryShow.'<a href="/products/'.$category['category_name_url'].'">'.$category['category_name'].'</a>';
				
					if(end($product['product_category']) != $category) 
					{$categoryShow = $categoryShow.',';}						
							
	
				endforeach;
				
        		$products = $products.'<div class="clear"></div><p class="category">Product in '.$categoryShow.'</p>';
        		
        		$products = $products.'</div>';

			endforeach;
			
			
			
		else :
			$products = '0 products';
		endif;


    	return $products;

	}
	
	
}

?>