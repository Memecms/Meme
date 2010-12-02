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
 * @version		$Id: RssController.php 401 2010-11-18 20:25:30Z alex $
 */

class RssController extends Zend_Controller_Action
{


    public function init()
    {
    }
    
    
   	public function preDispatch()
		{
			//Disable layout

			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
	
		}


	public function indexAction()
	{
		
		$PostsModel = new Admin_Model_DbTable_PostsTable();
		
		
		$PostList = $PostsModel->getAll('post_date DESC', 20);
				
		// Create array to store the RSS feed entries
     	$entries = array();
		
		
		
		
		// Cycle through the rankings, creating an array storing
     	// each, and push the array onto the $entries array
     	foreach ($PostList AS $post) {
     	 $data = $post['post_content'] ;
		 $data = explode('<!-- pagebreak -->',$data);

        	$entry = array(
           		'title'       => $post['post_title'],
           		'link'        => $this->view->Meme_Setting_get('address').'/article/'.$post['post_id'].'/'.$post['post_url'],
           		'description' => $data[0],
           		'guid'		  => $post['post_id'],
           		'content' => $data[1],
           		'lastUpdate' => $post['post_date'],
         );
         array_push($entries, $entry);
     		}
     
     // Create the RSS array
     $rss = array(
       'title'   => $this->view->Meme_Setting_get('sitetitle'),
       'link'    => $this->view->Meme_Setting_get('address'),
       'charset' => 'ISO-8859-1',
       'description' => $this->view->Meme_Setting_get('setting_tagline'),
       'generator' => 'Memecms 0.1.1dev',
       'entries' => $entries
     );
     
     // Import the array
     $feed = Zend_Feed::importArray($rss, 'rss');

     
          // Write the feed to a variable
     $feed->send();
		
		

		
	}

}