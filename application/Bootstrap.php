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
 * @version		$Id: Bootstrap.php 402 2010-11-20 18:02:14Z alex $
 */
 
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	public $cahce ;

    protected function _initCache()
    {
        // Cache options
        $frontendOptions = array(
           'lifetime' => 86400*7,                      // Cache lifetime of 7 days
           'automatic_serialization' => true,
           'cache_id_prefix' => 'meme_',
        );
        $backendOptions = array(
            'cache_dir' => '../application/meme-tmp/',   // Directory where to put the cache files
        );
        
        //se non  abilitato APC usa file
        $backend = extension_loaded('apc') ? 'Apc' : 'File';

        // Get a Zend_Cache_Core object
        $this->cache = Zend_Cache::factory('Core', $backend, $frontendOptions, $backendOptions);
       
       
       	Zend_Registry::set('Meme_Cache', $this->cache);
     	Zend_Db_Table_Abstract::setDefaultMetadataCache($this->cache);  
       
     }


protected function _initZFDebug()
{
    $autoloader = Zend_Loader_Autoloader::getInstance();
    $autoloader->registerNamespace('ZFDebug');
    
	$db = $this->getPluginResource('db')->getDbAdapter();
	
    $options = array(
        'plugins' => array('Variables', 
                           'Database' => array('adapter' => $db), 
                           'File' => array('basePath' => '/path/to/project'),
                           'Memory', 
                           'Time', 
                           'Registry',
                           'Cache' => array('backend' => $this->cache->getBackend()), 
                           'Exception')
    );
    $debug = new ZFDebug_Controller_Plugin_Debug($options);

    $this->bootstrap('frontController');
    $frontController = $this->getResource('frontController');
    $frontController->registerPlugin($debug);
}




    /**
     * Bootstrap the view doctype
     * 
     * @return void
     */
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_TRANSITIONAL');
        $view->headMeta()->appendName('Generator', 'Memecms 0.1.3dev');
        $this->view->headTitle()->setSeparator(' | ');


    }
    
   
   //insert modules for admin area
	protected function _initAutoloadModuleAdmin()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
							'namespace' => 'Admin',
							'basePath' => APPLICATION_PATH.'/modules/admin',
		));
		return $autoloader;
	}

	protected function _initAutoloadModuleDefault()
	{
		$autoloader = new Zend_Application_Module_Autoloader(array(
			'namespace' => '',
			'basePath' => dirname(__FILE__)
		));
		return $autoloader;
	}

	protected function _initLayoutHelper()
	{
		$this->bootstrap('frontController');
		$layout = Zend_Controller_Action_HelperBroker::addHelper(
		new Meme_Controller_Action_Helper_LayoutLoader());
	}
    
    
    
    protected function _initZendX()
    {
    
		$view = new Zend_View();
		$view->addHelperPath('ZendX/JQuery/View/Helper/', 'ZendX_JQuery_View_Helper');
 
		$viewRenderer = new Zend_Controller_Action_Helper_ViewRenderer();
		$viewRenderer->setView($view);
		Zend_Controller_Action_HelperBroker::addHelper($viewRenderer);
	    
    }


}
