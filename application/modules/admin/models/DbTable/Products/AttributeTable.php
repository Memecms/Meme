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
 * @version		$Id: AttributeTable.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_Model_DbTable_Products_AttributeTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_products_attribute';
	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
		$this->urlAdapter = new Admin_Model_urlAdapter();
	}


	
	/**
	 * return all attribute for product.
	 * 
	 * @access public
	 * @return void
	 */
	public function getAll()
	{
		$id = 'Products_attribute_getAll';
		
		if(!($data = $this->cache->load($id)))
		{
			$orderby = array('products_attribute_id ASC');
			$result = $this->fetchAll('1', $orderby )->toArray();
			
			$this->cache->save($result, $id, array($this->_name));

			return $result;
		}
		else
		{		
			return $this->cache->load($id);
		}
	}

	
	
	
	public function add($post)
	{

	}



	
	public function edit($attribute)
	{
	}



	public function deleteAttrtibute($id)
	{
	
		$where = $this->getAdapter()->quoteInto('page_id = ?', $id);

		$this->delete($where);
	
	}



}