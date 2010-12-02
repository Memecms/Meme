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
 * @version		$Id: PostsTable.php 401 2010-11-18 20:25:30Z alex $
 */



class Admin_Model_DbTable_PostsTable extends Zend_Db_Table_Abstract {

	protected $_name = 'meme_posts';
	
	protected $cache;
	
	
	function init()
	{
		$this->cache = Zend_Registry::get('Meme_Cache');
		
		//define Model
		$this->urlAdapter = new Admin_Model_urlAdapter();
		$this->CategoryAddModel = new Admin_Model_DbTable_CategoryAddTable();
		$this->UserModel = new Admin_Model_DbTable_Users();
		$this->SettingsModel = new Admin_Model_DbTable_SettingsTable();
		$this->SitemapModel = new Admin_Model_sitemap();
		
	}
	
	
	
	
	
    /**
     * Result all post 
     *
	 * @param  string	$order			Ordere for return of posts
	 * @param  int		$count			Number of item result
	 * @param  int		$offset			Offset limit result item
	 * @param  boolean	$published		Status of post request
	 * @param  boolean	$home			Visible on home
	 *
	 * @return Array	Result posts          
     *                          
     *
     */
 
	public function getAll($order = 'post_date DESC', $count = '', $offset = '', $published = true, $home = false, $where = null)
	{
		
		$id = 'Posts_getAll_'.$order.'_'.$count.'_'.$offset.'_'.$published.'_'.$home.'_'.$where;	
		$id = $this->urlAdapter->cleanURL($id);
		
		if(!($data = $this->cache->load($id)))
			{
				$select = $this->select()->order($order)
                	    	      		 ->limit($count, $offset);
                
                if($where != null)
                {
                $select = $select->where('post_id IN ('.$where.')');
				}

                
                if($published == true){$select = $select->where('post_status = 0');}
				if($home == true){$select = $select->where('post_home = 1');}


               	$posts = $this->fetchAll($select)->toArray();
				
               	$categories = $this->CategoryAddModel->getAllAdd(1);
				
            	$i = 0;
				foreach ($posts as $attributes):
					$posts[$i]['category'] = array();
					$posts[$i]['post_user'] = $this->UserModel->getUsername($attributes['post_user']);
								
					$i_cat = 0;
					foreach ($categories as $category):
						if($category['object_id'] == $attributes['post_id'])
						{
	        				$posts[$i]['category'][$i_cat] = $category;
						}
						$i_cat++;
        			endforeach;
				
					$i++;
        		endforeach;
               	
               	
				$this->cache->save($posts, $id, array($this->_name));
				return $posts;
			}
		else
			{
				return $this->cache->load($id);
			}
	}	
	
	
	
	
	
	
	
	
	/**
     * Result all post 
     *
	 * @param  string	$order			Ordere for return of posts
	 * @param  int		$count			Number of item result
	 * @param  int		$offset			Offset limit result item
	 * @param  boolean	$published		Status of post request
	 * @param  boolean	$home			Visible on home
	 *
	 * @return Array	Result posts          
     *                          
     *
     */
 
	public function getFromCategory($category_id, $order = 'post_date DESC', $count = '', $offset = '', $published = true, $home = false)
	{
				$addElement = $this->CategoryAddModel->getFromCategoryId(1, $category_id);
			
				$postsId = '';

				foreach ($addElement as $attributes ):
        			if(end($addElement) == $attributes)
        			{ 
					 	$postsId = $postsId.$attributes['object_id'];
					}
					else
					{
					   	$postsId = $attributes['object_id'].', '.$postsId;
					}
        		endforeach; 
               	
				return $this->getAll($order , $count , $offset , $published , $home , $postsId);
	}	

	
	
	
	
	
	
	
	
	
	/**
     * Add post on database
     *
	 * @param  int  $post  Post information for add
	 *
	 * @return int	Id post assigned on database          
     *                          
     *
     */
     
	public function add($post)
	{

            Zend_Date::setOptions(array(
                'format_type'   => 'php'
            ));

            $dateTime = $post['datepicker'].' '.$post['hh'].':'.$post['mn'];
            $date = new Zend_Date($dateTime, 'Y-m-d H:i');
            $unixTimestamp = $date->get();

			if($post['post_home'] == ''){$post_home = 0;}
			else {$post_home = 1;}

                $data = array(
				'post_user'=> $post['post_user'],
				'post_date'=> $unixTimestamp,
				'post_status'=> $post['post_status'],
				'post_title'=> $post['post_title'],
				'post_description'=> $post['post_description'],
				'post_keywords'=> $post['post_keywords'],
				'post_content'=> stripslashes($post['post_content']),
				'post_home'=> $post_home,
				'post_url'=> $this->urlAdapter->cleanURL($post['post_title']),
					);

		$this->insert($data);
		
		$lastid = $this->getAdapter()->lastInsertId();

		//sitemap
		if($post['post_status'] == '0'){
		
        $this->SitemapModel->add(array(
			'loc'=> $this->SettingsModel->get('address').'/article/'.$lastid.'/'.$data['post_url'],
			'changefreq'=>'monthly',
			'priority'=>'0.5'
				));

		$this->SitemapModel->save();
		}
		//-----------------------



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
		
		return $lastid;

	}


	
	
	
	
	/**
     * Search post from id post
     *
	 * @param  int  $post_id  Post id for search
	 *
	 * @return Array    Post      
     *                          
     *
     */
	
	public function getFromPostId($post_id)
	{
	
		$post_id = (int)$post_id;
		$id = 'Post_getFromPostId_'.$post_id;
		
		if(!($data = $this->cache->load($id)))
			{

					$post = $this->fetchRow('post_id = '. $post_id)->toArray();
	              	
	              	$categories = $this->CategoryAddModel->getFromObjectId(1, $post_id);
					$post['category'] = array();
					$post['post_user'] = $this->UserModel->getUsername($post['post_user']);
								
					$i_cat = 0;
					foreach ($categories as $category):
	        				$post['category'][$i_cat] = $category;
						$i_cat++;
        			endforeach;
				
		
				
				$this->cache->save($post, $id, array($this->_name));
				return $post;

			}
			else
			{
				return $this->cache->load($id);
			}
		
	}

	
	
	
	/**
     * Edit  post
     *
	 * @param  array  $post  Post information for edit
	 *
	 * @return null      
     *                          
     *
     */
	
	public function edit($post)
	{

            Zend_Date::setOptions(array(
                'format_type'   => 'php'
            ));

            $dateTime = $post['datepicker'].' '.$post['hh'].':'.$post['mn'];
            $date = new Zend_Date($dateTime, 'Y-m-d H:i');
            $unixTimestamp = $date->get();


		$data = array(
				'post_user'=> $post['post_user'],
				'post_date'=> $unixTimestamp,
				'post_status'=> $post['post_status'],
				'post_title'=> $post['post_title'],
				'post_description'=> $post['post_description'],
				'post_keywords'=> $post['post_keywords'],
				'post_content'=> stripslashes($post['post_content']),
				'post_home'=> $post['post_home'],
				'post_url'=> $this->urlAdapter->cleanURL($post['post_title']),
					);

		$where = 'post_id = '.$post['post_id'];
		
		
		//sitemap
		if($post['post_status'] == '0'){
		
 		$this->SitemapModel->delete(array('node'=>'loc','value'=> $this->SettingsModel->get('address').'/article/'.$post['post_id'].'/'.$post['post_url_old']));

        $this->SitemapModel->add(array(
			'loc'=> $this->SettingsModel->get('address').'/article/'.$post['post_id'].'/'.$data['post_url'],
			'changefreq'=>'monthly',
			'priority'=>'0.5'
				));
		}
		else
		{
		 $this->SitemapModel->delete(array('node'=>'loc','value'=> $this->SettingsModel->get('address').'/article/'.$post['post_id'].'/'.$post['post_url_old']));
		}

		$this->SitemapModel->save();
		
		//-----------------------

		
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
		
		
		
		$this->update($data , $where);
	}

	
	
	
	
	
	
	
	
	/**
     * Delete post
     *
	 * @param  int  $post_id  Id of post for delete
	 *
	 * @return null          
     *                          
     *
     */
	
	public function deletePostId($post_id)
	{
	
	
		$post_url = $this->getFromPostId($post_id);
	
	
		$where = $this->getAdapter()->quoteInto('post_id = ?', $post_id);

		
		
		//sitemap
		$settings = new Admin_Model_DbTable_SettingsTable();
        $sitemap = new Admin_Model_sitemap();


		$this->SitemapModel->delete(array('node'=>'loc','value'=> $this->SettingsModel->get('address').'/article/'.$post_id.'/'.$post_url['post_url']));
		
		
		$this->SitemapModel->save();
		//-----------------------

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