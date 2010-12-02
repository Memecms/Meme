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
 * @version		$Id: GalleryTable.php 401 2010-11-18 20:25:30Z alex $
 */



class Admin_Model_DbTable_Gallery_GalleryTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_gallery';
	
	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
	}
	
	public function getGallery($where = '', $order = 'gallery_id DESC', $count = '', $offset = '')  //approvated
		{
				
				$urlAdp = new Admin_Model_urlAdapter();
				
				$whereexplode = '';
				
				if(is_array($where))
					{
						
						foreach ($where as $attributes ):
        					$whereexplode = $whereexplode.$attributes['where'];
        				endforeach;
					}
				elseif($where != '')
					{$whereexplode = $where;}
			
			
				$id = $this->_name.'_'.$whereexplode.'_'.$order.'_'.$count.'_'.$offset;
				$id = $urlAdp->cleanURL($id);
				

				
				
				if(!($data = $this->cache->load($id)))
					{
					
		
						$select = $this->select()
											->order($order)
                	    	      			->limit($count, $offset);
			
			
			
						if(is_array($where))
							{
									foreach ($where as $attributes ):
								
        								$select = $select->where($attributes['where']);
        								
        							endforeach;
							}
						elseif($where != '')
							{$select = $select->where($where);}
		
					
						
						$gallery = $this->fetchAll($select)->toArray();
											
							
									
				       	$this->cache->save($gallery, $id, array($this->_name));


					return $gallery;

					}
					else
					{
						
						return $this->cache->load($id);

					
					}


		}

	
	
	
	public function findPostsFromId($id)
	{
			
			$result = $this->find($id);
			return $result->toArray();
		
	
	}
	
	
	public function getGalleryRow($id_gallery)
	{
	
			
				$id = 'Gallery_Id_'.$id_gallery;
				
				
				if(!($data = $this->cache->load($id)))
					{
						

					
						$id_gallery = (int)$id_gallery;
						$row = $this->fetchRow('gallery_id = ' . $id_gallery);
						if (!$row) {
							throw new Exception("Count not find row $id_post");
						}
						
						
						   	$this->cache->save($row, $id, array($this->_name));
						   	

					return $row;

					}
					else
					{
						
						return $this->cache->load($id);

					
					}
	}


	
	public function saveGallery($post)
	{

            Zend_Date::setOptions(array(
                'format_type'   => 'php'
            ));

            $dateTime = $post['datepicker'].' '.$post['hh'].':'.$post['mn'];
            $date = new Zend_Date($dateTime, 'Y-m-d H:i');
            $unixTimestamp = $date->get();


                $data = array(
				'gallery_title'=> $post['gallery_title'],
				'gallery_date'=> $unixTimestamp,
				'gallery_user'=> $post['gallery_user'],
				'gallery_description'=> $post['gallery_description'],
				'gallery_keywords'=> $post['gallery_keywords'],
				'gallery_content'=> stripslashes($post['gallery_content']),
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



	
	public function updateGallery($post)
	{

            Zend_Date::setOptions(array(
                'format_type'   => 'php'
            ));

            $dateTime = $post['datepicker'].' '.$post['hh'].':'.$post['mn'];
            $date = new Zend_Date($dateTime, 'Y-m-d H:i');
            $unixTimestamp = $date->get();


		$data = array(
				'gallery_title'=> $post['gallery_title'],
				'gallery_date'=> $unixTimestamp,
				'gallery_user'=> $post['gallery_user'],
				'gallery_description'=> $post['gallery_description'],
				'gallery_keywords'=> $post['gallery_keywords'],
				'gallery_content'=> stripslashes($post['gallery_content']),
					);

		$where = 'gallery_id = '.$post['gallery_id'];
		
		

		
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
		
		
		
		$this->update($data , $where);
	}



	public function deleteGallery($id)
	{
	
		$where = $this->getAdapter()->quoteInto('gallery_id = ?', $id);

		
		


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