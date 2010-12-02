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
 * @version		$Id: AlbumAddTable.php 401 2010-11-18 20:25:30Z alex $
 */



class Admin_Model_DbTable_Gallery_AlbumAddTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_gallery_album_add';

	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
	}



	public function getAlbumFromIdGallery($gallery_id)
	{
		$gallery_id = (int)$gallery_id;
		$result = $this->fetchAll('gallery_id = ' . $gallery_id);
		return $result->toArray();
	}



	public function addGallery($album_id, $gallery_id)
	{
		$data = array(
				'gallery_id'=> $gallery_id,
				'album_id'=> $album_id,
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

	public function getAllGallery()
	{
		$result = $this->fetchAll();
		return $result->toArray();
	}




	public function getGallery($id)
	{
		$id = (int)$id;
		$result = $this->fetchAll('album_id = ' . $id);
		return $result->toArray();
	}


	public function deleteAllAlbum($album_id)
	{
	
		$where = $this->getAdapter()->quoteInto('album_id = ?', $album_id);

		
		
		
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




	public function deleteAllGallery($gallery_id)
	{
	
		$where = $this->getAdapter()->quoteInto('gallery_id = ?', $gallery_id);

		
		
		
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




}