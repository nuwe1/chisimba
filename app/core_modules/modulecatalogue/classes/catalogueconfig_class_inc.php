<?php
/**
 * Class for building and reading the xml catalogue used by modulecatalogue.
 * It is based as adaptor around the PEAR Config Object
 *
 * This class will provide the catalogue configuration for module registration
 *
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
 * @category  Chisimba
 * @package   modulecatalogue
 * @author    Prince Mbekwa <pmbekwa@uwc.ac.za>
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

// security check - must be included in all scripts
if (!
/**
 * Description for $GLOBALS
 * @global unknown $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS['kewl_entry_point_run']){
    die("You cannot view this page directly");
}

/**
 * Adaptor Pattern around the PEAR::Config Object
 *
 *
 * @category  Chisimba
 * @package   modulecatalogue
 * @author    Prince Mbekwa <pmbekwa@uwc.ac.za>
 * @copyright 2007 AVOIR
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 */

//grab the pear::Config properties
// include class
require_once 'Config.php';

class catalogueconfig extends object {

    /**
     * The pear config object
     *
     * @access public
     * @var    string
    */

    protected $_objPearConfig;

    /**
     * The path of the files to be read or written
     * @access public
     * @var    string
     */
    public $_path = null;
    /**
     * The root object for configs read
     *
     * @access private
     * @var    string
    */
    protected $_root;
    /**
     * The root object for properties read
     *
     * @access private
     * @var    string
    */
    protected $_property;
    /**
     * The options value for altconfig read / write
     *
     * @access private
     * @var    string
    */
    protected $_options;

    /**
     * The catalogueconfig object for catalogueconfig storage
     *
     * @access private
     * @var    array
     */
    protected $_catalogueconfigVars;


    /**
     * The site configuration object
     *
     * @var object $config
     */
    public $config;


    /**
    * Method to construct the class.
    */
    public function init()
    {
        // instantiate object
        try{
            $this->_objPearConfig = new Config();
            $this->objConfig = $this->getObject('altconfig','config');
            $this->objLanguage = $this->getObject('language','language');
        }catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }

    /**
     * Method to parse catalogue lists.
     * For use when reading configuration options
     *
     * @access protected
     * @param  string    $config   xml file or PHPArray to parse
     * @param  string    $property used to set property value of incoming config string
     *                             $property can either be:
     *                             1. PHPArray
     *                             2. XML
     * @return boolean   True/False result.
     *
     */
    protected function readCatalogue($property)
    {

        try {
            // read catalogue data and get reference to root
            $this->_path = $this->objConfig->getsiteRootPath();
            if (preg_match('/\/$/',$this->_path)) {
                $this->_path .= "config/";
            } else {
                $this->_path .= "/config/";
            }
            if (file_exists($this->_path.'catalogue.xml')) {
                $this->_root =& $this->_objPearConfig->parseConfig("{$this->_path}catalogue.xml",$property);
            } else {
                throw new customException("Could not find catalogue.xml: looked in {$this->_path}catalogue.xml");
            }
            if (PEAR::isError($this->_root)) {
                throw new customException("Can not read Catalogue. Please make sure that your site_path is set correctly\nlooked in {$this->_path}catalogue.xml");
            }
            return $this->_root;
        }catch (Exception $e)
        {
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }

    }
    /**
     * Method to wirte catalogue options.
     * For use when writing catalogue options
     *
     * @access public
     * @param  string  values   to be saved
     * @param  string  property used to set property value of incoming catalogue string
     * @return boolean TRUE for success / FALSE fail .
     *
     */
    public function writeCatalogue()
    {
        // set xml root element
        try {
            $objModFile = $this->getObject('modulefile','modulecatalogue');
            $xmlStr = "<?xml version='1.0' encoding='ISO-8859-1'?>\n<settings xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:noNamespaceSchemaLocation='catalogue.xsd'>\n";
            $this->_path = "{$this->objConfig->getsiteRootPath()}/config/";

            //$xmlStr .= "    <catalogue>\n";
            //$categories = $objModFile->getCategories();
            //if (is_array($categories)) {
            //        foreach ($categories as $cat) {
            //            $xmlStr .= "        <category>$cat</category>\n";
            //        }
            //}
            //$xmlStr .= "    </catalogue>\n";
            $modules = $objModFile->getLocalModuleList();
            $id = 001;
            foreach ($modules as $mod) {
                if ($mod) {
                    $xmlStr .= "    <module>
        <id>$id</id>\n";
                    $reg = $objModFile->readRegisterFile($objModFile->findregisterfile($mod));
                    if (is_array($reg)) {
                        $from = $this->objLanguage->languageText('phrase_frommodule');
                        if (isset($reg['MODULE_ID'])){
                            $module_id = htmlentities($reg['MODULE_ID']);
                        } else {
                            $module_id = 'unknown';
                            log_debug($this->objLanguage->languageText('mod_modulecatalogue_missingtag','modulecatalogue').": MODULE_ID");
                        }
                        if (isset($reg['MODULE_NAME'])){
                            $module_name = htmlentities($reg['MODULE_NAME']);
                        } else {
                            $module_name = $module_id;
                        }
                        if (isset($reg['MODULE_AUTHORS'])){
                            $module_authors = htmlentities($reg['MODULE_AUTHORS']);
                        } else {
                            $module_authors = '';
                            log_debug($this->objLanguage->languageText('mod_modulecatalogue_missingtag','modulecatalogue').": MODULE_AUTHORS $from $module_name");
                        }
                        if (isset($reg['MODULE_RELEASEDATE'])){
                            $module_releasedate = htmlentities($reg['MODULE_RELEASEDATE']);
                        } else {
                            $module_releasedate = '';
                            log_debug($this->objLanguage->languageText('mod_modulecatalogue_missingtag','modulecatalogue').": MODULE_RELEASEDATE $from $module_name");
                        }
                        if (isset($reg['MODULE_DESCRIPTION'])){
                            $module_description = htmlentities($reg['MODULE_DESCRIPTION']);
                        } else {
                            $module_description = '';
                            log_debug($this->objLanguage->languageText('mod_modulecatalogue_missingtag','modulecatalogue').": MODULE_DESCRIPTION $from $module_name");
                        }
                        if (isset($reg['MODULE_VERSION'])){
                            $module_version = htmlentities($reg['MODULE_VERSION']);
                        } else {
                            $module_version = '';
                            log_debug($this->objLanguage->languageText('mod_modulecatalogue_missingtag','modulecatalogue').": MODULE_VERSION $from $module_name");
                        }
                        if (isset($reg['TAGS'])){
                            $module_tags = '';
                            foreach($reg['TAGS'] as $tags)
                            {
                                $module_tags .= htmlentities($tags).", ";
                            }
                        } else {
                            $module_tags = '';
                            // log_debug($this->objLanguage->languageText('mod_modulecatalogue_missingtag','modulecatalogue').": MODULE_TAGS $from $module_name");
                        }
                        if (isset($reg['DEPENDS'])){
                            $module_deps = '';
                            foreach($reg['DEPENDS'] as $deps)
                            {
                                $module_deps .= htmlentities($deps).", ";
                            }
                        } else {
                            $module_deps = '';
                            // log_debug($this->objLanguage->languageText('mod_modulecatalogue_nodeps','modulecatalogue').": MODULE_DEPENDS $from $module_name");
                        }
                        if (isset($reg['MODULE_STATUS'])){
                            $module_stats = $reg['MODULE_STATUS'];
                        } else {
                            $module_stats = 'pre-alpha';
                            log_debug($this->objLanguage->languageText('mod_modulecatalogue_missingtag','modulecatalogue').": MODULE_STATUS $from $module_name");
                        }
                        $xmlStr .= "        <module_id>$module_id</module_id>
        <module_name>$module_name</module_name>
        <module_authors>$module_authors</module_authors>
        <module_releasedate>$module_releasedate</module_releasedate>
        <module_description>$module_description</module_description>
        <module_version>$module_version</module_version>
        <module_tags>$module_tags</module_tags>
        <module_dependency>$module_deps</module_dependency>
        <module_status>$module_stats</module_status>\n";
                        if (isset($reg['MODULE_CATEGORY'])) {
                            foreach ($reg['MODULE_CATEGORY'] as $cat) {
                                $cat = htmlentities($cat);
                                $xmlStr .= "        <module_category>$cat</module_category>\n";
                            }
                        }
                    } else {
                        $mod = htmlentities($mod);
                        $xmlStr .= "        <module_id>$mod</module_id>\n";
                    }
                    $xmlStr .= "    </module>\n";
                    $id++;
                }
            }
            $xmlStr .= '    <engine_version>'.$this->objEngine->version."</engine_version>\n";
            $xmlStr .= '</settings>';
            if(!file_exists($this->_path))
            {
                mkdir($this->_path);
            }
            if(file_exists($this->_path.'catalogue.xml'))
            {
                unlink($this->_path.'catalogue.xml');
                touch($this->_path.'catalogue.xml');
                chmod($this->_path . 'catalogue.xml',0666);
            }
            if(!file_exists($this->_path.'catalogue.xml'))
            {
                touch($this->_path.'catalogue.xml');
                chmod($this->_path . 'catalogue.xml',0666);
            }
            $fh = fopen($this->_path.'catalogue.xml','w');
            fwrite($fh,$xmlStr);
            fclose($fh);
            return true;
        } catch (Exception $e)
        {
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }

    }

    /**
    * Method to get modulelist for catalogue categories.
    *
    * @var    string $pname The name of the parameter being set
    * @return $value
    */
    public function getModulelist($pname)
    {
        try {

            $this->_path = $this->objConfig->getsiteRootPath()."config/catalogue.xml";

            $xml = simplexml_load_file($this->_path);
            if($pname !="all"){
                $query = "//module[module_category='{$pname}']";
            }else{
                $query = "//module";

            }
            $entries = $xml->xpath($query);


            foreach ($entries as $module) {
                $moduleName = $this->objLanguage->abstractText((string)$module->module_name);
                if (empty($moduleName)) {
                    $result[(string)$module->module_id] = ucwords((string)$module->module_id);
                } else {
                    $result[(string)$module->module_id] = ucwords($moduleName);
                }
            }
            if (!isset($result)) {
                return FALSE;
            }else {
                return $result;
            }

        }catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }

    /**
    * Method to get basic module data for all modules.
    *
    * @return array of key module_id with values being the module name and description
    */
    public function getModuleDetails()
    {
        try {

            $this->_path = $this->objConfig->getsiteRootPath()."config/catalogue.xml";

            $xml = simplexml_load_file($this->_path);
            $entries = $xml->xpath("//module");

            foreach ($entries as $module) {
                $moduleDesc = $this->objLanguage->abstractText((string)$module->module_description);
                $moduleName = $this->objLanguage->abstractText((string)$module->module_name);
                $moduleVer  = (string)$module->module_version;
                $moduleStatus = (string)$module->module_status;
                if (empty($moduleName)) {
                    $result[] = array((string)$module->module_id,ucfirst((string)$module->module_id),ucfirst((string)$module->module_id), $module->module_status);
                } else {
                    $result[] = array((string)$module->module_id,ucwords($moduleName),ucfirst($moduleDesc),$moduleVer, $moduleStatus);
                }
            }
            if (!isset($result)) {
                return FALSE;
            }else {
                return $result;
            }

        }catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }


    /**
    * Method to get basic module tags for all modules.
    *
    * @return array of key module_tasg with values being the module tags
    */
    public function getModuleTags()
    {
        try {
            $this->_path = $this->objConfig->getsiteRootPath()."config/catalogue.xml";
            $xml = simplexml_load_file($this->_path);
            $entries = $xml->xpath("//module");
            foreach ($entries as $moduletags) {
                $moduleName = $this->objLanguage->abstractText((string)$moduletags->module_name);
                $moduleTag = $this->objLanguage->abstractText((string)$moduletags->module_tags);
                if (empty($moduleName)) {
                    $result[] = array('id' => (string)$moduletags->module_id,
                    'tags' => (string)$moduletags->module_tags,
                    'name' => (string)$moduletags->module_name
                    );
                } else {
                    $result[] = array('id' => (string)$moduletags->module_id,'name' => ucwords($moduleName),'tags' => (string)$moduleTag);
                }
            }
            if (!isset($result)) {
                return FALSE;
            }else {
                return $result;
            }

        }catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }

    /**
    * Method to get basic module dependencies.
    *
    * @return array of key module_deps with values being the module deps
    */
    public function getModuleDeps($module)
    {
        $this->_path = $this->objConfig->getsiteRootPath()."config/catalogue.xml";
        $xml = simplexml_load_file($this->_path);
        $entries = $xml->xpath("//module[module_id='{$module}']");
        //log_debug($entries[0]->module_dependency);
        return @$entries[0]->module_dependency;
    }

    /**
    * Method to get module status.
    *
    * @return array of key module_status
    */
    public function getModuleStatus($module)
    {
        $this->_path = $this->objConfig->getsiteRootPath()."config/catalogue.xml";
        $xml = simplexml_load_file($this->_path);
        $status = $xml->xpath("//module[module_id='{$module}']");
        //var_dump($status[0]->module_status);
        return $status[0]->module_status;
    }

    /**
    * Method to get modulelist for catalogue categories.
    *
    * @var    string $pname The name of the parameter being set
    * @var    string $type either search module_id,description or both
    * @return $value
    */
    public function searchModulelist($str,$type)
    {
        try {
            $this->_path = $this->objConfig->getsiteRootPath()."config/catalogue.xml";
            //echo "$str $type<br/>";
            $str = strtolower($str);
            $xml = simplexml_load_file($this->_path);
            switch ($type) {
                case 'name':
                    $query = "//module[contains(translate(module_id, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),'$str') or contains(translate(module_name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),'$str')]";
                    break;
                case 'description':
                    $query = "//module[contains(translate(module_description, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),'$str')]";
                    break;
                case 'tags':
                    $query = "//module[contains(translate(module_tags, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'), '$str')]";
                    break;
                default:
                    $query = "//module[contains(translate(module_id, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),'$str') or contains(translate(module_description, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),'$str') or contains(translate(module_name, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'),'$str')]";
                    break;
            }
            $entries = $xml->xpath($query);

            foreach ($entries as $module) {
                $moduleName = $this->objLanguage->abstractText((string)$module->module_name);
                if (empty($moduleName)) {
                    $result[(string)$module->module_id] = ucwords((string)$module->module_id);
                } else {
                    $result[(string)$module->module_id] = ucwords($moduleName);
                }
            }
            if (!isset($result)) {
                return FALSE;
            }else {
                return $result;
            }

        } catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }


    /**
     * Method to get module description from the catalogue
     *
     * @author Nic Appleby
     * @param  string $modname module name
     * @return string module description|FALSE if none exists
     */
    public function getModuleDescription($modname) {
        try {
            $this->_path = $this->objConfig->getsiteRootPath()."config/catalogue.xml";
            $xml = simplexml_load_file($this->_path);
            $query = "//module[module_id='$modname']/module_description";
            $entries = $xml->xpath($query);

            if (!isset($entries)) {
                return FALSE;
            } else {
                return $entries;
            }
        } catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }

    /**
     * Method to get module name from the catalogue
     *
     * @author Nic Appleby
     * @param  string $moduleId module id
     * @return string module name|FALSE if none exists
     */
    public function getModuleName($moduleId) {
        try {
            $this->_path = $this->objConfig->getsiteRootPath()."config/catalogue.xml";
            $xml = simplexml_load_file($this->_path);
            $query = "//module[module_id='$moduleId']/module_name";
            $entries = $xml->xpath($query);

            if (!isset($entries)) {
                return FALSE;
            } else {
                return $entries;
            }
        } catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }

    /**
    * Method to get a system configuration parameter.
    *
    * @var    string $pmodule The module code of the module owning the config item
    * @var    string $pname The name of the parameter being set, use UPPER_CASE
    * @return string $value The value of the config parameter
    */
    public function getNavParam($pmodule)
    {
        try {

            //Read conf
            //if (!isset($this->_root)) {
            $this->readCatalogue('XML');
            //}
            //Lets get the parent node section first

            $Settings =& $this->_root->getItem("section", "settings");
            //Now onto the directive node
            //check to see if one of them isset to search by
            $Settings =& $Settings->getItem("section","catalogue");

            if(isset($pmodule))$SettingsDirective =& $Settings->getItem("directive", "{$pmodule}");
            $SettingsDirective =& $Settings->toArray();
            //finally unearth whats inside
            if (!$SettingsDirective) {
                throw new Exception("Catalogue Navigation items are missing! {$pmodule}");
            }else{
                $value = $SettingsDirective;
                return $value;
            }


        } catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }

    /**
     * Method to return the list of categories stored in
     * the catalogue xml document
     *
     * @return array Categories within the document
     * @access public
     */
    public function getCategories() {
        try {
            if(!file_exists($this->objConfig->getsiteRootPath()."config/systemtypes.xml"))
            {
                copy($this->objConfig->getsiteRootPath()."installer/dbhandlers/systemtypes.xml", $this->objConfig->getsiteRootPath()."config/systemtypes.xml");
                //unlink($this->objConfig->getsiteRootPath()."installer/dbhandlers/systemtypes.xml");
            }
            $sysTypes = $this->objConfig->getsiteRootPath()."config/systemtypes.xml";
            // $sysTypes = $this->objConfig->getsiteRootPath()."installer/dbhandlers/systemtypes.xml";
            $doc = simplexml_load_file($sysTypes);
            $types = array();
            for ($i=1;$i<count($doc->systemtypes->category);$i++) {
                $types[] = (string)$doc->systemtypes->category[$i];
            }
            return $types;
        } catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }

    /**
     * This method returns a list of all the modules present in
     * a specified category
     *
     * @param  string $category The category in question.
     * @return array  List of modules
     * @access public
     */
    public function getCategoryList($category) {
        try {
            $path = $this->objConfig->getsiteRootPath()."config/catalogue.xml";
            $cat = simplexml_load_file($path);
            $types = array();
            if ($category == 'all') {
                $modules = $cat->xpath("//module");
                foreach($modules as $mod) {
                    $types[(string)$mod->module_id] = $this->objLanguage->abstractText((string)$mod->module_name);
                }
            } else {
                if(!file_exists($this->objConfig->getsiteRootPath()."config/systemtypes.xml"))
                {
                    copy($this->objConfig->getsiteRootPath()."installer/dbhandlers/systemtypes.xml", $this->objConfig->getsiteRootPath()."config/systemtypes.xml");
                    //unlink($this->objConfig->getsiteRootPath()."installer/dbhandlers/systemtypes.xml");
                }
                $sysTypes = $this->objConfig->getsiteRootPath()."config/systemtypes.xml";
                $doc = simplexml_load_file($sysTypes);
                $modules = $doc->xpath("//category[categoryname='$category']");
                if (isset($modules[0]->module)) {
                    if (count($modules[0]->module) > 0) {
                        foreach ($modules[0]->module as $mod) {
                            $moduleId = (string)$mod;
                            $mn = $cat->xpath("//module[module_id='$moduleId']/module_name");
                            if (!$mn && (file_exists($this->objConfig->getModulePath().$moduleId) || file_exists($this->objConfig->getsiteRootPath()."core_modules/$moduleId"))) {
                                log_debug("Could not find $moduleId in the catalogue. Rewriting catalogue.");
                                $this->writeCatalogue();
                                $cat = simplexml_load_file($path);
                                $mn = $cat->xpath("//module[module_id='$moduleId']/module_name");
                            }
                            if (isset($mn[0])) {
                                $types[$moduleId] = ucwords($this->objLanguage->abstractText((string)$mn[0]));
                            } else {
                                $types[$moduleId] = NULL;
                            }
                        }
                    }
                }
            }
            return $types;
        } catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }

    /**
     * The error callback function, defers to configured error handler
     *
     * @param  string $error
     * @return void
     */
    public function errorCallback($exception)
    {
        echo customException::cleanUp($exception);
        exit();
    }

    public function skinRemoter($skins)
    {
        $path = $this->objConfig->getskinRoot();
        chdir($path);
        $lSkins = NULL;
        foreach(glob('*') as $s)
        {
            if($s == NULL)
            {
                continue;
            }
            else {
                $lSkins .= $s."|";
            }
        }
        $lSkins = explode("|", $lSkins);
        $lSkins = array_filter($lSkins);
        foreach($lSkins as $lskin)
        {
            if($lskin == 'CVS' || $lskin == 'CVSROOT' || $lskin == '_common' || $lskin == 'cache.config' || $lskin == 'error_log' || $lskin == 'icons2')
            {
                unset($lskin);
            }
            if (!empty($lskin))
            {
                $skinner[] = $lskin;
            }
        }
        if(empty($skinner))
        {
            $skinner = array();
        }
        $lSkin = array_filter($skinner);

        $this->loadClass('checkbox','htmlelements');
        $this->loadClass('link','htmlelements');

        $objH = $this->getObject('htmlheading','htmlelements');
        $objH->type=2;
        $objH->str = $this->objLanguage->languageText('mod_modulecatalogue_heading','modulecatalogue');

        $objH2 = $this->newObject('htmlheading','htmlelements');
        $objH2->type=3;
        $objH2->str = $this->objLanguage->languageText('mod_modulecatalogue_remoteskinheading','modulecatalogue');

        $hTable = $this->getObject('htmltable','htmlelements');
        $hTable->cellpadding = 2;
        $hTable->id = 'unpadded';
        $hTable->width='100%';
        $hTable->startRow();
        $hTable->addCell($objH->show());
        $hTable->endRow();
        $hTable->startRow();
        $hTable->addCell($objH2->show());
        $hTable->endRow();
        $hTable->startRow();
        $hTable->addCell('&nbsp;');
        $hTable->endRow();

        sort($skins);

        $objTable = $this->newObject('htmltable','htmlelements');
        $objTable->cellpadding = 2;
        $objTable->id = 'unpadded1';
        $objTable->width='100%';

        $masterCheck = new checkbox('arrayList[]');
        //$masterCheck->extra = 'onclick="javascript:baseChecked(this);"';

        $head = array('&nbsp', '&nbsp;',$this->objLanguage->languageText('mod_modulecatalogue_skinname','modulecatalogue'),
        $this->objLanguage->languageText('mod_modulecatalogue_install','modulecatalogue'));
        $objTable->addHeader($head,'heading','align="left"');
        $newMods = array();
        $class = 'odd';

        $link = new link();
        $link->link = $this->objLanguage->languageText('mod_modulecatalogue_dlandinstall','modulecatalogue');
        $icon = '&nbsp;'; //$this->newObject('getIcon','htmlelements');
        foreach ($skins as $skin) {
            if (!in_array($skin,$lSkins)) {
                $link->link('javascript:;');
                $link->extra = "onclick = 'javascript:downloadSkin(\"{$skin}\");'";
                $class = ($class == 'even')? 'odd' : 'even';
                $newMods[] = $skin;
                //$icon->setModuleIcon($module['id']);
                //$modCheck = new checkbox('arrayList[]');
                //$modCheck->cssId = 'checkbox_'.$skin;
                //$modCheck->setValue($skin);
                //$modCheck->extra = 'onclick="javascript:toggleChecked(this);"';

                $objTable->startRow();
                $objTable->addCell('&nbsp;',20,null,null,$class);
                $objTable->addCell('&nbsp;',30,null,null,$class);
                $objTable->addCell("<div id='link_{$skin}'><b>{$skin}</b></div>",null,null,null,$class);
                $objTable->addCell("<div id='download_{$skin}'>".$link->show()."</div>",'40%',null,null,$class);
                $objTable->endRow();
                /*$objTable->startRow();
                $objTable->addCell('&nbsp;',20,null,'left',$class);
                $objTable->addCell('&nbsp;',30,null,'left',$class);
                $objTable->addCell('&nbsp;'.'<br />&nbsp;',null,null,'left',$class, 'colspan="2"');
                $objTable->endRow();*/
            }
        }

        if (empty($newMods)) {
            $objTable->startRow();
            $objTable->addCell("<span class='empty'>".$this->objLanguage->languageText('mod_modulecatalogue_noremoteskins','modulecatalogue').'</span>',null,null,'left',null, 'colspan="4"');
            $objTable->endRow();
        }

        return $hTable->show()."<br />".$objTable->show();
    }
    
    /**
     * Method to get engine version from the catalogue
     *
     * @author Paul Scott <pscott@uwc.ac.za>
     * @return  string $enginever engine version
     */
    public function getEngineVer() {
        try {
            $this->_path = $this->objConfig->getsiteRootPath()."config/catalogue.xml";
            $xml = simplexml_load_file($this->_path);
            $query = "//engine_version";
            $enginever = $xml->xpath($query);

            if (!isset($enginever)) {
                return FALSE;
            } else {
                return $enginever;
            }
        } catch (Exception $e){
            $this->errorCallback('Caught exception: '.$e->getMessage());
            exit();
        }
    }

}
?>
