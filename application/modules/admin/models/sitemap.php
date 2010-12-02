<?
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
 * @version		$Id: sitemap.php 401 2010-11-18 20:25:30Z alex $
 */

class Admin_Model_Sitemap
  {
    private $xml;
    private $sitemap;

    public function __construct(){
      $this->sitemap = 'sitemap.xml';
      $this->xml = $this->read($this->sitemap);
    }

    function read($file){
      if (file_exists($file)){
        try{
          return simplexml_load_file($file);
        }catch (Exception $e){
          throw new Exception("Errore nel caricamento del file");
        }
      }else{
        throw new Exception("File xml inesistente");
      }
    }

    function add($data){
      try{
        $node = $this->xml->addChild('url');
        $node->addChild("loc", $data['loc']);
        $node->addChild("lastmod", $this->datetime(isset($data['date']) && !empty($data['date']) && trim($data['date']) != '' ? $data['date'] : NULL));
        $node->addChild("changefreq", $data['changefreq']);
        $node->addChild("priority", $data['priority']);
      }catch(Exception $e){
        throw new Exception("Errore in add... i dati erano un array?");
      }
    }

    function get($couple){
      foreach($this->xml->url as $node){
        if ($node->$couple['node'] == $couple['value']){
          return $node;
        }
      }
      return NULL;
    }

    function edit($couple, $data){
      $node = $this->get($couple);
      $node->loc = $data['loc'];
      $node->lastmod = $this->datetime(isset($data['date']) && !empty($data['date']) ? $data['date'] : NULL);
      $node->changefreq = $data['changefreq'];
      $node->priority = $data['priority'];
    }

    function timestamp($data){
      try{
        $data = explode(" ", $data);
        list($y, $m, $d) = explode("-", trim($data[0]));
        list($h, $mi, $sc) = explode(":", trim($data[1]));
        return mktime($h,$mi,$sc,$d,$m,$y);
      }catch(error $e){
        throw new Exception("Errore in timestamp");
      }
    }

    function datetime($date = NULL){
      try{
        $mktime = $date === NULL && empty($date) ? mktime(date("H"), date("i"), date("s"), date("m"), date("d"), date("Y")) : $this->timestamp($date);
        //return date(DATE_ATOM,$mktime);
        $offset = date("O",$mktime);
        return date("Y-m-d\TH:i:s",$mktime).substr($offset,0,3).":".substr($offset,-2);
      }catch(Exception $e){
        throw new Exception("Errore in datetime");
      }
    }

    function save(){
      //file_put_contents($this->sitemap, $this->xml->asXML());
      $this->xml->asXML($this->sitemap);
    }

    function delete($couple){
      $node = $this->get($couple);
      if ($node != NULL){
        unset($node[0][0]);
      }
    }

    function erase(){
      unset($this->xml->url);
    }
  }
  ?>