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
 * @version		$Id: ImageTable.php 401 2010-11-18 20:25:30Z alex $
 */



class Admin_Model_DbTable_Gallery_ImageTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_gallery_img';
	
	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
	}
	
	
	public function saveImg($img_file, $img_title, $img_link, $img_content)
	{

                $data = array(
                'img_file'=> $img_file,
				'img_title'=> $img_title,
				'img_link'=> $img_link,
				'img_content'=> $img_content,
				'img_description'=> ' ',
				'img_user'=> 0,
				'img_date'=> mktime(),
				'img_status'=> 0,
					);

		$this->insert($data);
		
		$lastid = $this->getAdapter()->lastInsertId();




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
		
		return $lastid;

	}
	
	
	
	public function getImage($id)
	{
		$id = (int)$id;
		$result = $this->fetchRow('img_id = ' . $id);
		return $result;
	}


	public function getImages() 
	{
												

		return $this->fetchAll()->toArray();
		

	}
	
	
	
	public function updateImage($image)
	{


		$data = array(
				'img_title'=> $image['img_title'],
				'img_link'=> $image['img_link'],
				'img_content'=> $image['img_content'],
				'img_description'=> $image['img_description'],
				'img_keywords'=> $image['img_keywords'],
				);

		$where = 'img_id = '.$image['img_id'];
		
		
	
		
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
		
		
		
		$this->update($data , $where );
	}

	public function deleteImage($image)
	{
	
			$where = $this->getAdapter()->quoteInto('img_id = ?', $image);

		
		
		
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