<?php
/**
 * Epi wrapper class
 *
 * This class interacts with a remote oAUth server to get information about users.
 *
 * PHP version 5
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the
 * Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *
 *
 * @category  Chisimba
 * @package   security
 * @author Paul Scott <pscott@uwc.ac.za>
 * @copyright 2004-2007, University of the Western Cape & AVOIR Project
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @link      http://avoir.uwc.ac.za
 */
// security check - must be included in all scripts
if (! $GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}
// end security check


/**
 * Epi wrapper class
 *
 * This class interacts with a remote oAUth server to get information about users.
 *
 * @author Paul Scott <pscott@uwc.ac.za>
 */
class epiwrapper extends object
{

    /**
    * @var object $objConfig Config Object
    */
    public $objConfig;

    /**
    * @var object $objUser User Object
    */
    private $objUser;

    /**
    * @var object $objLanguage Language Object
    */
    private $objLanguage;

    /**
     * @var object $objEpiOAuth EPI oAuth object
     */
    public $objEpiOAuth;

    /**
     * @var object $objEpiTwitter EPI Twitter object
     */
    public $objEpiTwitter;
   
    /**
     * @var object $objEpiCurl EPI Curl object
     */
    public $objEpiCurl;

    /**
    * Constructor
    */
    public function init()
    {
        // $this->objConfig = $this->getObject('altconfig','config');
        // $this->objUser = $this->getObject('user','security');
        // $this->objLanguage = $this->getObject('language','language');
        include($this->getResourcePath('twitteroauth/EpiCurl.php'));
        include($this->getResourcePath('twitteroauth/EpiOAuth.php'));
        include($this->getResourcePath('twitteroauth/EpiTwitter.php'));
        // OK now instantiate them all in the derived classes... I think...
    }
}
?>