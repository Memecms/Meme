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
 * @version		$Id: imgAdapter.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_Model_Analitycs
{


	protected $cache;





	public function __construct() {
	    	$this->cache = Zend_Registry::get('Meme_Cache');

    	// Setting model
		$this->SettingsModel = new Admin_Model_DbTable_SettingsTable();
		
		$this->urlAdapter = new Admin_Model_urlAdapter();

		
	}

		
	function bounce()
	{
		$id = 'Analytics22';
		
		if(!($data = $this->cache->load($id)))
			{
			
		$gapi = new Admin_Model_gapi($this->SettingsModel->get('analitycs_username'), $this->SettingsModel->get('analitycs_password'));

 		$gapi->requestReportData($this->SettingsModel->get('analitycs_id'),array('date'),array('visits', 'bounces'), '-date', '', date('Y-m-d', strtotime('-12 month')), date('Y-m-d'), 1, 600);
		// requestReportData($report_id, $dimensions, $metrics, $sort_metric=null, $filter=null, $start_date=null, $end_date=null, $start_index=1, $max_results=30)

		
		$result = $gapi->getResults();

		$this->cache->save($result, $id);

		echo $result;
		}
		else
		{		
			echo $this->cache->load($id);
		}

	}
	
	
	
	function visits()
	{
	
	
			$id = 'Analytics_visits';
		
		if(!($data = $this->cache->load($id)))
			{
			
		$gapi = new Admin_Model_gapi($this->SettingsModel->get('analitycs_username'), $this->SettingsModel->get('analitycs_password'));

 		$gapi->requestReportData($this->SettingsModel->get('analitycs_id'),array('date'),array('visits', 'visitors'), '-date', '', date('Y-m-d', strtotime('-12 month')), date('Y-m-d'), 1, 600);
		// requestReportData($report_id, $dimensions, $metrics, $sort_metric=null, $filter=null, $start_date=null, $end_date=null, $start_index=1, $max_results=30)

$cvs= "Date,visits,visitors \n";
foreach($gapi->getResults() as $result)
{
  $cvs = $cvs.$result.',';
  $cvs = $cvs.$result->getVisits() . ',';
  $cvs = $cvs.$result->getVisitors() . "\n";
}

		$this->cache->save($cvs, $id);

		return $cvs;
		}
		else
		{		
			return $this->cache->load($id);
		}
	
	}
	
	
	
	
}