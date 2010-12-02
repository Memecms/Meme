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
 * @version		$Id: TryController.php 401 2010-11-18 20:25:30Z alex $
 */

class TryController extends Zend_Controller_Action
{


    public function init()
    {
		//$this->meme = new Admin_Model_MemeStart();

    }
    
    
   	public function preDispatch()
		{
			//Disable layout

			//$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender(true);
		$this->CategoryAddModel = new Admin_Model_DbTable_CategoryAddTable();
		$this->PostsModel = new Admin_Model_DbTable_PostsTable();
				$this->ProductsModel = new Admin_Model_DbTable_ProductsTable();
				
				$this->sitemapPing = new Admin_Model_sitemapPing();

	
		}


	public function indexAction()
		{
		
		
 		}




}

