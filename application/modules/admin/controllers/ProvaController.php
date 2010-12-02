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
 * @version		$Id: ProvaController.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_ProvaController extends Zend_Controller_Action
{



    public function indexAction()
    {

 $file = "path del file.ext"; 
       $path_parts = pathinfo($file); 
       echo $path_parts['dirname'], "<br>"; //cartella dove è presente 
       echo $path_parts['basename'], "<br>"; //nome file 
       echo $path_parts['extension'], "<br>"; //estensione file 
       
}


public function provaAction()
{
	//Disable layout

//$this->_helper->layout()->disableLayout();
$this->_helper->viewRenderer->setNoRender(true);


				$file = 'meme-media/gallery/thumb/p15h7hpkpf1bal105ps0irlg5jb1.jpg';

				unlink($file);


}


}