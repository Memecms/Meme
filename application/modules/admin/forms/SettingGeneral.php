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
 * @version		$Id: SettingGeneral.php 401 2010-11-18 20:25:30Z alex $
 */


class Admin_Form_SettingGeneral extends Zend_Form
{
	public function init()
	{
		$sitetitle= $this->createElement('text','sitetitle');
        $sitetitle->setLabel('Site Title:')
                ->setRequired(true);
                
        $tagline = $this->createElement('text','tagline');
        $tagline->setLabel('Tagline:')
                ->setRequired(true);
                
        $address = $this->createElement('text','address');
        $address->setLabel('Site address (URL):')
                ->setRequired(true);
                
        $emailaddress = $this->createElement('text','emailaddress');
        $emailaddress->setLabel('E-mail address:')
                ->setRequired(true)
                ->addValidator('EmailAddress');
                
        $register = $this->createElement('submit','save');
        $register->setLabel('Save')
                ->setIgnore(true);
                
        $this->addElements(array(
                        $sitetitle,
                        $tagline,
                        $address,
                        $emailaddress,
                        $register
        ));
	}
}