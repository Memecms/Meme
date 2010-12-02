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
 * @version		$Id: CategoryAddTable.php 401 2010-11-18 20:25:30Z alex $
 */



class Admin_Model_DbTable_CategoryAddTable extends Zend_Db_Table_Abstract {

/* object_type
 * 1 - Posts
 * 2 - Pages
 * 3 - Products
*/

	protected $_name = 'meme_category_add';

	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
		$this->urlAdapter = new Admin_Model_urlAdapter();

	}




    /**
     * Result of all category of object
     *
	 * @param  int  $object_type  Type of category post, product and more
	 * @return object          
     *                          
     *
     */
 
	public function getAllAdd($object_type)
	{
		$id = 'CategoryAdd_getAllAdd_'.$object_type;
		
		if(!($data = $this->cache->load($id)))
			{
			
				$select = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false);
			
				$select = $select->where('category_object_type = ?', $object_type);		
			
				$select = $select->join('meme_category', 'meme_category.category_id = meme_category_add.category_id');
						
				$categoryAdd = $this->fetchAll($select)->toArray();
				
				$this->cache->save($categoryAdd, $id, array($this->_name));
				
				return $categoryAdd;
			
			}
		else
			{
				return $this->cache->load($id);
			}
	}






    /**
     * Result of all category of object
     *
	 * @param  int  $object_type  Type of category post, product and more
	 * @return object          
     *                          
     *
     */

	public function addObject($object_type, $object_id, $category_id)
	{
		$data = array(
				'object_type' => $object_type,
				'object_id'=> $object_id,
				'category_id'=> $category_id,
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
     * Result of all category of object
     *
	 * @param  int  $object_type  Type of category post, product and more
	 * @return object          
     *                          
     *
     */

	public function deleteFromCategory($category_id, $object_type)
	{
	
		$select = array('category_id'=> $category_id, 'object_type' => $object_type);
		$where = $this->getAdapter()->quoteInto($select);
		
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

		$this->delete($where);
	}






    /**
     * Result of all category of object
     *
	 * @param  int  $object_type  Type of category post, product and more
	 * @return array          
     *                          
     *
     */

	public function getFromObjectId($object_type, $object_id)
	{
		$object_id = (int)$object_id;

		$id = 'CategoryAdd_getFromObjectId_'.$object_type.'_'.$object_id;
		
		if(!($data = $this->cache->load($id)))
			{				
				$select = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
										 ->where('object_type = ?', $object_type)
										 ->where('object_id = ?', $object_id);
				
				$select = $select->join('meme_category', 'meme_category.category_id = meme_category_add.category_id');

				$categoryAdd = $this->fetchAll($select)->toArray();
				
				$this->cache->save($categoryAdd, $id, array($this->_name));
				
				return $categoryAdd;
			
			}
		else
			{
				return $this->cache->load($id);
			}
	}








    /**
     * Result of all object of category
     *
	 * @param  int  $category_id  Id of category
	 * @return array          
     *                          
     *
     */

	public function getFromCategoryId($object_type, $category_id)
	{
		$category_id = (int)$category_id;
		$id = 'CategoryAdd_getFromCategoryId_'.$object_type.'_'.$category_id;
		
		if(!($data = $this->cache->load($id)))
			{
				$select = $this->select(Zend_Db_Table::SELECT_WITH_FROM_PART)->setIntegrityCheck(false)
										 ->where('object_type = ?', $object_type)
										 ->where('category_id = ?', $category_id);
				
				//$select = $select->join('meme_category', 'meme_category.category_id = meme_category_add.category_id');

				$categoryAdd = $this->fetchAll($select)->toArray();
				
				$this->cache->save($categoryAdd, $id, array($this->_name));
				
				return $categoryAdd;
			}
		else
			{
				return $this->cache->load($id);
			}
	}








    /**
     * Result of all category of object
     *
	 * @param  int  $object_type  Type of category post, product and more
	 * @return object          
     *                          
     *
     */

	public function deleteFromObjectId($object_id)
	{
	
		$where = $this->getAdapter()->quoteInto('object_id = ?', $object_id);


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

		$this->delete($where);

	}

}