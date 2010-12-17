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
			$this->Analitycs = new Admin_Model_Analitycs();
			$this->SettingsModel = new Admin_Model_DbTable_SettingsTable();

		}


	public function indexAction()
		{
		
		$gapi = new Admin_Model_gapi($this->SettingsModel->get('analitycs_username'), $this->SettingsModel->get('analitycs_password'));

 		$gapi->requestReportData($this->SettingsModel->get('analitycs_id'),array('month'),array('bounces'), '-month', '', date('Y-m-d', strtotime('-1 month')), date('Y-m-d'), 1, 100);
		// requestReportData($report_id, $dimensions, $metrics, $sort_metric=null, $filter=null, $start_date=null, $end_date=null, $start_index=1, $max_results=30)

$cvs= "Date,visits,visitors \n";
foreach($gapi->getResults() as $result)
{
  $cvs = $cvs.$result.',';
  $cvs = $cvs.$result->getMonth() . ',';
  $cvs = $cvs.$result->getBounces() . "<br />";
}

echo $cvs;



 		}

	public function timeAction()
		{
		// Insert this block of code at the very top of your page: 

$time = microtime(); 
$time = explode(" ", $time); 
$time = $time[1] + $time[0]; 
$start = $time; 

// Place this part at the very end of your page 

$time = microtime(); 
$time = explode(" ", $time); 
$time = $time[1] + $time[0]; 
$finish = $time; 
$totaltime = ($finish - $start); 
printf ("This page took %f seconds to load.", $totaltime); 



$mic_time = explode(" ",microtime()); 
$mic_time = $mic_time[1] + $mic_time[0]; 
$starttime = $mic_time;

sleep(1);

$places = 5;      // However many decimal places you require
$mic_time = explode(" ",microtime()); 
$mic_time = $mic_time[1] + $mic_time[0]; 
$finishtime = $mic_time; 
echo "Page loaded in ". round(($finishtime - $starttime),$places) ." secs";


		}



}

