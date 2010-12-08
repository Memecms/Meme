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
	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
	}




	/**
	 * Result all value of the determinate id
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
	 
	public function getAllFromProductId($product_id)
	{
		$product_id = (int)$product_id;

		$id = 'Products_Value_getAllFromProductId_'.$product_id;
		
		if(!($data = $this->cache->load($id)))
			{
				$result = $this->fetchAll('product_id = ' . $product_id)->toArray();
		
				$this->cache->save($result, $id, array($this->_name));

				return $result;
			}
			else
			{		
				return $this->cache->load($id);
			}
	}


	
	
	
	/**
	 * add value
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @param mixed $product_attribute_id
	 * @param mixed $product_value
	 * @return void
	 */
	public function add($product_id, $product_attribute_id, $product_value)
	{
		$data = array( 
		
				'product_id'=> $product_id,
				'product_attribute_id'=> $product_attribute_id,
				'product_value'=> $product_value,
								
					);

		//cache
		if(extension_loaded('apc') == 1)
					{	// clean all records
						$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
 
					}
				else
					{
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array($this->_name));
						$this->cache->clean(Zend_Cache::CLEANING_MODE_OLD);					
					}
		//end cache

		$this->insert($data);		
	}
	
	

	/**
	 * edit value existent from product id
	 * 
	 * @access public
	 * @param mixed $value
	 * @param mixed $product_id
	 * @param mixed $product_attribute_id
	 * @return void
	 */
	public function edit($value, $product_id, $product_attribute_id)
	{
		$data = array(
		
				
				'product_value'=> $value,

				);
		$where = 'product_id = '.$product_id.' AND product_attribute_id = '.$product_attribute_id ;
		$this->update($data , $where );
	}




	/**
	 * Delete value attribute from product id
	 * 
	 * @access public
	 * @param mixed $product_id
	 * @return void
	 */
	public function deleteFromProductId($product_id)
	{
	
		$where = $this->getAdapter()->quoteInto('product_id = ?', $product_id);

		$this->delete($where);

	}



}