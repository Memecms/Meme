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
 * @version		$Id: Recent.php 401 2010-11-18 20:25:30Z alex $
 */


class Zend_View_Helper_Meme_Posts_Recent {

	function Meme_Posts_Recent($number = 5) {

		$PostsModel = new Admin_Model_DbTable_PostsTable();
    	$PostsList = $PostsModel->getAll('post_date DESC', $number );

		$posts = '<h3>Recent Posts</h3>';
		if(count($PostsList)):
			foreach($PostsList as $post):
			
				$posts = $posts.'<a href="/article/'.$post['post_id'].'/'.$post['post_url'].'">'.$post['post_title'].'</a>' ;		
				
			endforeach;
			
		else :
			$posts = $posts.'0 Posts';
		endif;
			
    	return $posts;

	}
	
	
}

?>