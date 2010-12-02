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
 * @version		$Id: Lists.php 401 2010-11-18 20:25:30Z alex $
 */


class Zend_View_Helper_Meme_Posts_Lists {

	function Meme_Posts_Lists($home = false, $number = 5, $list = null) {

		$PostsModel = new Admin_Model_DbTable_PostsTable();
		
		if($list == null)
		{
    		$PostsList = $PostsModel->getAll('post_date DESC', $number , '', true, $home);

		}
		else
		{
			$PostsList = $list;
		}

		$posts = '';
		if(count($PostsList)):
			foreach($PostsList as $post):
			
				$post_date = new Zend_Date($post['post_date'], Zend_Date::TIMESTAMP);
			
				$posts = $posts.'<div class="post">';
				$posts = $posts.'<a href="/article/'.$post['post_id'].'/'.$post['post_url'].'" class="title">'.$post['post_title'].'</a>' ;		
				$posts = $posts.'<p class="author">Posted on '.$post_date.' by '.$post['post_user']['user_username'].'</p>';

				$data = $post['post_content'] ;
		 		$data = explode('<!-- pagebreak -->',$data);
		 		$posts = $posts.$data[0];
		 		
		 		if(isset($data[1]))
		 		{
			 		$posts = $posts.'<a href="/article/'.$post['post_id'].'/'.$post['post_url'].'" class="more">Read</a>';
		 		}
				
				$categoryShow = '';
				
				foreach($post['category'] as $category): 
	
					$categoryShow = $categoryShow.'<a href="/articles/'.$category['category_name_url'].'">'.$category['category_name'].'</a>';
				
					if(end($post['category']) != $category) 
					{$categoryShow = $categoryShow.',';}						
							
	
				endforeach;
				
        		$posts = $posts.'<p class="category">Posted in '.$categoryShow.'</p>';
        		
        		$posts = $posts.'</div>';

			endforeach;
			
			
			
		else :
			$posts = '0 Posts';
		endif;


    	return $posts;

	}
	
	
}

?>