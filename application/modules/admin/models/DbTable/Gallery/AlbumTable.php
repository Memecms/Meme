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
 * @version		$Id: AlbumTable.php 401 2010-11-18 20:25:30Z alex $
 */



class Admin_Model_DbTable_Gallery_AlbumTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_gallery_album';
	
	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
	}
	
	public function getAlbum($where = '', $order = 'album_id DESC', $count = '', $offset = '')  //approvated
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

	
	
	
	
	public function getAlbumRow($album_id)
	{
	
			
				$id = 'Album_Id_'.$album_id;
				
				
				if(!($data = $this->cache->load($id)))
					{
						

					
						$album_id = (int)$album_id;
						$row = $this->fetchRow('album_id = ' . $album_id);
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


	
	public function saveAlbum($post)
	{

            Zend_Date::setOptions(array(
                'format_type'   => 'php'
            ));

            $dateTime = $post['datepicker'].' '.$post['hh'].':'.$post['mn'];
            $date = new Zend_Date($dateTime, 'Y-m-d H:i');
            $unixTimestamp = $date->get();


                $data = array(
				'album_title'=> $post['album_title'],
				'album_date'=> $unixTimestamp,
				'album_user'=> $post['album_user'],
				'album_description'=> $post['album_description'],
				'album_keywords'=> $post['album_keywords'],
				'album_content'=> stripslashes($post['album_content']),
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



	
	public function updateAlbum($post)
	{

            Zend_Date::setOptions(array(
                'format_type'   => 'php'
            ));

            $dateTime = $post['datepicker'].' '.$post['hh'].':'.$post['mn'];
            $date = new Zend_Date($dateTime, 'Y-m-d H:i');
            $unixTimestamp = $date->get();


                $data = array(
				'album_title'=> $post['album_title'],
				'album_date'=> $unixTimestamp,
				'album_user'=> $post['album_user'],
				'album_description'=> $post['album_description'],
				'album_keywords'=> $post['album_keywords'],
				'album_content'=> stripslashes($post['album_content']),
					);

		$where = 'album_id = '.$post['album_id'];
		
		

		
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



	public function deleteAlbum($id)
	{
	
		$where = $this->getAdapter()->quoteInto('album_id = ?', $id);

		
		


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