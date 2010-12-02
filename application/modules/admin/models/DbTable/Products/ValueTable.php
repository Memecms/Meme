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
 * @version		$Id: ValueTable.php 401 2010-11-18 20:25:30Z alex $
 */



class Admin_Model_DbTable_Products_ValueTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_products_value';


	
	public function get()
	{
		$orderby = array('products_attribute_id DESC');
		$result = $this->fetchAll('1', $orderby );
		return $result->toArray();
	}

	
	
	
	public function saveValue($product_id, $product_attribute_id, $product_value)
	{
		$data = array( 
		
				'product_id'=> $product_id,
				'product_attribute_id'=> $product_attribute_id,
				'product_value'=> $product_value,
								
					);

		$this->insert($data);
		
	}
	
	
	
	public function getValues($product_id)
	{
		$product_id = (int)$product_id;
		$result = $this->fetchAll('product_id = ' . $product_id);
		return $result->toArray();
	}




	public function updateValues($value, $product_id, $product_attribute_id)
	{
		$data = array(
		
				
				'product_value'=> $value,

				);
		$where = 'product_id = '.$product_id.' AND product_attribute_id = '.$product_attribute_id ;
		$this->update($data , $where );
	}




	public function deleteAllProduct($product_id)
	{
	
		$where = $this->getAdapter()->quoteInto('product_id = ?', $product_id);

		$this->delete($where);

	}



}