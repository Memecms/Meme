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
		//http://davidwalsh.name/jquery-flot
			//http://code.google.com/p/gapi-google-analytics-php-interface/wiki/GAPIDocumentation
			
			//
			echo date('Y-m-d', strtotime('-10 month'));
			
			
			
			$gapi = new Admin_Model_gapi();

 $gapi->requestReportData(4319768,array('date'),array('visits', 'visitors'), '-date', '', date('Y-m-d', strtotime('-10 month')), date('Y-m-d'), 1, 600);
// requestReportData($report_id, $dimensions, $metrics, $sort_metric=null, $filter=null, $start_date=null, $end_date=null, $start_index=1, $max_results=30)
 /*foreach($gapi->getResults() as $result)
{
  echo '<strong>'.$result.'</strong><br />';
  echo 'Pageviews: ' . $result->getPageviews() . ' ';
  echo 'Visits: ' . $result->getVisits() . '<br />';
}

echo '<p>Total pageviews: ' . $gapi->getPageviews() . ' total visits: ' . $gapi->getVisits() . '</p>';
 */
 
			  Zend_Debug::dump($gapi->getResults());

 		}




}

