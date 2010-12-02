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
 * @version		$Id: GalleryAddTable.php 401 2010-11-18 20:25:30Z alex $
 */



/* object_type

1- Posts
2- Pages
3- Products

*/


class Admin_Model_DbTable_Gallery_GalleryAddTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_gallery_add';

	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
	}
	
	
	
	public function getList($object_type, $object_id, $gallery_id)
	{
	
	
	
	
	
	
	}
	
	public function get()
	{
	
	}

	public function add($object_type, $object_id, $gallery_id)
	{
	
	
			$data = array(
				'object_type'=> $object_type,
				'object_id'=> $object_id,
				'gallery_id'=> $gallery_id,
					);


		
		
		
		//cache
		if(extension_loaded('apc') == 1)
					{	// clean all records
						$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
 
					}
				else
					{
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array($this->_name, 'gallery'));
						$this->cache->clean(Zend_Cache::CLEANING_MODE_OLD);					
					}
		//end cache

		$this->insert($data);


	
	
	}

	public function update()
	{
	
	}



	public function deleteObject($object_type, $object_id)
	{
	
		$select = array('object_id'=> $object_id, 'object_type' => $object_type);
	
		$where = $this->getAdapter()->quoteInto($select);
							
									
		//cache
		if(extension_loaded('apc') == 1)
					{	// clean all records
						$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
 
					}
				else
					{
						$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_ANY_TAG, array($this->_name, 'gallery'));
						$this->cache->clean(Zend_Cache::CLEANING_MODE_OLD);					
					}
		//end cache

		$this->delete($where);

	}

	
	public function getFromIdObject($object_type, $object_id)
	{
		
		$object_id = (int)$object_id;
		$object_type = (int)$object_type;
		$row = $this->fetchRow($this->select()->where('object_id =?', $object_id)
												->where('object_type =?', $object_type));
		return $row;
		
	}

	
	
	

}