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
 * @version		$Id: IndexController.php 401 2010-11-18 20:25:30Z alex $
 */

class IndexController extends Zend_Controller_Action
{


    public function init()
    {
		$this->view->headTitle($this->view->Meme_Page_get()->page_title);
		$this->view->headMeta()->appendName('keywords', $this->view->Meme_Page_get()->page_keywords);
		$this->view->headMeta()->appendName('Description', $this->view->Meme_Page_get()->page_description);

		$this->view->headTitle($this->view->Meme_Setting_get('sitetitle'));
    }
    
    
   	public function preDispatch()
	{

	
	}


    public function indexAction()
    {
    
	}
   
   

}