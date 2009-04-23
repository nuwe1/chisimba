<?php

/**
 * Utilities
 *
 * Context Utilities
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
 * @package   context
 * @author    Wesley Nitsckie <wnitsckie@uwc.ac.za>
 * @copyright 2007 Wesley Nitsckie
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   $Id$
 * @link      http://avoir.uwc.ac.za
 * @see       core
 */
/* ----------- data class extends dbTable for tbl_context_usernotes------------*/
// security check - must be included in all scripts
if (! /**
 * Description for $GLOBALS
 * @global string $GLOBALS['kewl_entry_point_run']
 * @name   $kewl_entry_point_run
 */
$GLOBALS ['kewl_entry_point_run']) {
    die ( "You cannot view this page directly" );
}

/**
 * Utilities
 *
 * Context Utilities
 *
 * @category  Chisimba
 * @package   context
 * @author    Wesley Nitsckie <wnitsckie@uwc.ac.za>
 * @copyright 2007 Wesley Nitsckie
 * @license   http://www.gnu.org/licenses/gpl-2.0.txt The GNU General Public License
 * @version   Release: @package_version@
 * @link      http://avoir.uwc.ac.za
 * @see       core
 */
class utilities extends object {

    /**
     * @var object $objDBContext
     */
    public $objDBContext;

    /**
     * @var object $objConfig
     */
    public $objConfig;

    /**
     * @var object $objDBContext
     */
    public $contextCode;

    /**
     * Constructor method to define the table
     */
    public function init() {
        $this->objDBContext = $this->getObject ( 'dbcontext', 'context' );
        $this->objLink = $this->getObject ( 'link', 'htmlelements' );
        $this->objIcon = $this->getObject ( 'geticon', 'htmlelements' );
        $this->objConfig = $this->getObject ( 'config', 'config' );
        $this->objDBContextModules = $this->getObject ( 'dbcontextmodules', 'context' );
        $this->objDBContextParams = $this->getObject ( 'dbcontextparams', 'context' );
        $this->objLanguage = $this->getObject ( 'language', 'language' );
        $this->contextCode = $this->objDBContext->getContextCode ();
        $this->objUser = $this->getObject('user', 'security');
        $this->_objContextModules = $this->getObject ( 'dbcontextmodules', 'context' );

    }

    /**
     * Method to get the sliding context menu
     *
     * @return string
     */
    public function getHiddenContextMenu($selectedModule, $showOrHide = 'none', $showOrHideContent = 'none') {
        $str = '';
        $icon = $this->newObject ( 'geticon', 'htmlelements' );
        $icon->setModuleIcon ( 'toolbar' );
        $toolsIcon = $icon->show ();
        $icon->setModuleIcon ( 'context' );
        $contentIcon = $icon->show ();

        $str .= "<a href=\"#\" onclick=\"Effect.toggle('contextmenu','slide', adjustLayout());\">" . $toolsIcon . " Tools</a>";
        $str .= '<div id="contextmenu"  style="width:150px;overflow: hidden;display:' . $showOrHide . ';"> ';
        $str .= $this->getPluginNavigation ( $selectedModule );
        $str .= '</div>';

        $content = $this->getContextContentNavigation ();
        if ($content != '') {
            $str .= "<br/><a href=\"#\" onclick=\"Effect.toggle('contextmenucontent','slide', adjustLayout());\">" . $contentIcon . " Content</a>";
            $str .= '<div id="contextmenucontent"  style="width:150px;overflow: hidden;display:' . $showOrHideContent . ';"> ';
            $str .= $content;
            $str .= '</div>';
        }

        $objFeatureBox = $this->getObject ( 'featurebox', 'navigation' );

        return $objFeatureBox->show ( 'Toolbox', $str, 'contexttoolbox' );
    }

    /**
     * Method to get the left Navigation
     * with the context plugins
     *
     * @param  string $contextCode
     * @access public
     * @return string
     */
    public function getPluginNavigation($selectedModule = NULL) {
        $objSideBar = $this->newObject ( 'sidebar', 'navigation' );
        $objModule = $this->newObject ( 'modules', 'modulecatalogue' );
        //$objContentLinks = $this->getObject('dbcontextdesigner','contextdesigner');
        $objIcon = $this->getObject ( 'geticon', 'htmlelements' );

        $arr = $this->_objContextModules->getContextModules ( $this->objDBContext->getContextCode () );
        $isregistered = '';

        //create the nodes array
        $nodes = array ();
        $children = array ();
        $nodes [] = array ('text' => $this->objDBContext->getMenuText () . ' - Home', 'uri' => $this->uri ( NULL, 'context' ), 'nodeid' => 'context' );
        if (is_array ( $arr )) {
            foreach ( $arr as $contextModule ) {
                //$modInfo =$objModule->getModuleInfo($plugin['moduleid']);
                if ($contextModule ['moduleid'] == 'cms') {
                    $isregistered = TRUE;
                } else {
                    $modInfo = $objModule->getModuleInfo ( $contextModule ['moduleid'] );
                    $moduleLink = $this->uri ( NULL, $contextModule ['moduleid'] ); //$this->uri(array('action' => 'contenthome', 'moduleid' => $contextModule['moduleid']));
                    $nodes [] = array ('text' => ucwords ( $modInfo ['name'] ), 'uri' => $moduleLink, 'nodeid' => $contextModule ['moduleid'] );
                }
            }

            return $objSideBar->show ( $nodes, $selectedModule );
        } else {
            return '';
        }
    }

    /**
     * Method to get the navigation menu
     * for the content section of the context
     *
     * @access public
     * @param  string $selectedLink The link that you are currently on
     * @return string
     */
    public function getContextContentNavigation($selectedLink = NULL) {
        $objSideBar = $this->getObject ( 'sidebar', 'navigation' );
        $objModule = $this->getObject ( 'dbcontextmodules', 'context' );
        //create the nodes array
        $nodes = array ();

        return '';
    }

    /**
     * Method to check if a user can join a
     * context
     * @param  string  $contextCode The context Code
     * @return boolean
     * @access public
     * @author Wesley Nitsckie
     */
    public function canJoin($contextCode) {
        // TODO


        //check if the user is logged in to access an open context


        //check if the user is registered to the context and he is logged in


        //if the context is public then the user can access the context , but only limited access


        return TRUE;
    }

    /**
     * Method to create a link to the course home
     *
     * @return string
     */
    function getContextLinks() {
        $this->objIcon->setIcon ( "home" );
        $this->objIcon->alt = $this->objLanguage->languageText ( "mod_context_coursehome", 'context' );
        $this->objIcon->align = "absmiddle";

        $this->objLink->href = $this->URI ( NULL, 'context' );
        $this->objLink->link = $this->objIcon->show ();
        $str = $this->objLink->show ();

        return $str;
    }

    /**
     * Method to create links to the contents
     * and to the course
     *
     * @return string
     */
    function getContentLinks() {
        $this->objIcon->setModuleIcon ( "content" );
        $this->objIcon->alt = $this->objLanguage->languageText ( "mod_context_coursecontent", 'context' );
        $this->objIcon->align = "absmiddle";

        $params = array ('nodeid' => $this->getParam ( 'nodeid' ), 'action' => 'content' );
        $this->objLink->href = $this->URI ( $params, 'context' );
        $this->objLink->link = $this->objIcon->show ();
        $str = $this->objLink->show ();

        return $str;
    }

    /**
     * Method to create links to the course admin
     *
     * @return string
     */
    function getCourseAdminLink() {
        $this->objIcon->setModuleIcon ( "contextadmin" );
        $this->objIcon->alt = $this->objLanguage->languageText ( "mod_context_courseadmin", 'context' );
        $this->objIcon->align = "absmiddle";

        $params = array ('action' => 'courseadmin' );
        $this->objLink->href = $this->URI ( $params, 'contextadmin' );
        $this->objLink->link = $this->objIcon->show ();
        $str = $this->objLink->show ();

        return $str;
    }

    /**
     * Method used to get the path to the course folder
     *
     * @param  string $contextCode The context code
     * @return string
     */
    function getContextFolder($contextCode = NULL) {
        if ($contextCode == NULL) {
            $contextCode = $this->contextCode;
        }
        $str = $this->objConfig->siteRootPath () . 'usrfiles/content/' . $contextCode . '/';

        return $str;

    }

    /**
     * Method used to get the path to the images  folder
     * for a given course code
     *
     * @param  string $contextCode The context code
     * @return string
     */
    function getImagesFolder($contextCode = NULL) {
        return $this->getContextFolder ( $contextCode ) . 'images/';
    }

    /**
     * Method used to get the path to the maps  folder
     * for a given course code
     *
     * @param  string $contextCode The context code
     * @return string
     */
    function getMapsFolder($contextCode = NULL) {
        return $this->getContextFolder ( $contextCode ) . 'maps/';
    }

    /**
     * Method to get the context menu
     *
     * @return string
     * @param  void
     * @access public
     */
    public function getContextMenu() {
        try {
            //initiate the objects
            $objSideBar = $this->newObject ( 'sidebar', 'navigation' );
            $objModules = $this->newObject ( 'modules', 'modulecatalogue' );

            //get the contextCode
            $this->objDBContext->getContextCode ();

            //create the nodes array
            $nodes = array ();

            //get the section id
            $section = $this->getParam ( 'id' );

            //create the home for the context
            $nodes [] = array ('text' => $this->objDBContext->getMenuText () . ' -  ' . $this->objLanguage->languageText ( "word_home", 'system', 'Home' ), 'uri' => $this->uri ( NULL, "_default" ) );

            //get the registered modules for this context
            $arrContextModules = $this->objDBContextModules->getContextModules ( $this->contextCode );

            foreach ( $arrContextModules as $contextModule ) {
                $modInfo = $objModules->getModuleInfo ( $contextModule ['moduleid'] );

                $nodes [] = array ('text' => $modInfo ['name'], 'uri' => $this->uri ( array ('action' => 'contenthome', 'moduleid' => $contextModule ['moduleid'] ) ), 'sectionid' => $contextModule ['moduleid'] );
            }

            return $objSideBar->show ( $nodes, $this->getParam ( 'id' ) );

        } catch ( Exception $e ) {
            echo 'Caught exception: ', $e->getMessage ();
            exit ();
        }
    }
    
    
    /**
     * Block to searh for context
     */
    public function searchBlock_()
    {
    	
	//$script = $this->getJavaScriptFile('jquery/1.2.3/jquery-1.2.3.pack.js', 'htmlelements');
	
	$script = $this->getJavaScriptFile('jquery/jquery-ui-personalized-1.6rc6/jquery-1.3.1.js', 'htmlelements');
	$script .= $this->getJavaScriptFile('jquery/jquery-ui-personalized-1.6rc6/jquery-ui-personalized-1.6rc6.js', 'htmlelements');
	$script .= '<link type="text/css" href="'.$this->getResourceUri('jquery/jquery-ui-personalized-1.6rc6/theme/ui.all.css', 'htmlelements').'" rel="Stylesheet" />';
	$script .= $this->getJavaScriptFile('jquery/jquery.autocomplete.js', 'htmlelements');
	$this->appendArrayVar('headerParams', $script);
	$str = '<link rel="stylesheet" href="'.$this->getResourceUri('jquery/jquery.autocomplete.css', 'htmlelements').'" type="text/css" />';
	$this->appendArrayVar('headerParams', $str);
	
		$str = '<script type="text/javascript">
$().ready(function() {

	function findValueCallback(event, data, formatted) {
		$("<li>").html( !data ? "No match!" : "Selected: " + formatted).appendTo("#result");
	}

	function formatItem(row) {
		return row[0] + " (<strong>username: " + row[1] + "</strong>)";
	}
	
	function formatContextItem(row) {
		return row[0] + " (<strong>'.$this->objLanguage->code2Txt('phrase_othercourses', 'system', NULL, '[-context-]').' Code: " + row[1] + "</strong>)";
	}
	
	function formatResult(row) {
		//return row[0].replace(/(<.+?>)/gi, \'\');
		return row[0];
	}

$(":text, textarea").result(findValueCallback).next().click(function() {
		$(this).prev().search();
	});


	$("#usersearch").autocomplete(\'index.php?module=context&action=searchusers\', {
		width: 300,
		minChars: 2,
		multiple: false,
		matchContains: true,
		formatItem: formatItem,
		formatResult: formatResult,
		
	}).result(function (evt, data, formatted) {				
					$("#usersearch_selected").val(data[1]);
					});

$("#contextsearch").autocomplete(\'index.php?module=context&action=searchcontext\', {
		width: 300,
		multiple: false,
		matchContains: true,
		formatItem: formatContextItem,
		formatResult: formatResult,
		
	}).result(function (evt, data, formatted) {				
					$("#contextsearch_selected").val(data[1]);
					});
					
	$("#clear").click(function() {
		$(":input").unautocomplete();
	});
});

function submitSearch(data)
{

	alert(data[0]);
}


function changeOptions(){
	var max = parseInt(window.prompt(\'Please type number of items to display:\', jQuery.Autocompleter.defaults.max));
	if (max > 0) {
		$("#suggest1").setOptions({
			max: max
		});
	}
}

function submitSearchForm(frm)
{	
	username = frm.usersearch_selected.value;
	
	if(username)
	{
		getUserContext(username);
	}
	
	frm.usersearch_selected.value = "";
	frm.usersearch.value = "";
	
}

function submitContextSearchForm(frm)
{
	contextCode = frm.contextsearch_selected.value;
	if(contextCode)
	{
		getContext(contextCode);
		
	}
	
	frm.contextsearch_selected.value = "";
	frm.contextsearch.value = "";
}

function getContexts()
{
	listContexts();
}

	</script>';
	$this->appendArrayVar('headerParams', $str);
    	$input = '<div style="padding:10px;border:0px dashed black;" >
			<form id="searchform" name="searchform" autocomplete="off">
				<p>
					
					<table>
						<tr>
							<td>Search by user</td>
							<td><input type="text" id="usersearch"><input type="hidden" id="usersearch_selected">&nbsp;
					<input id="searchbutton" type="button" onclick="submitSearchForm(this.form)" value="Search" /></td>
						</tr>
						<tr>
							<td>'.$this->objLanguage->code2Txt('phrase_othercourses', 'system', NULL, 'Search by [-context-]').'</td>
							<td><input type="text" id="contextsearch"><input type="hidden" name="contextsearch_selected" id="contextsearch_selected">	
							&nbsp;
							<input id="searchbutton" type="button" onclick="submitContextSearchForm(this.form)" value="Search" /></td>
						</tr>
						<tr>
							<td><input type="button" value="'.$this->objLanguage->code2Txt('mod_context_viewallcontexts', 'context', NULL, 'View All [-contexts-]').'" onclick="listContexts()"></td>
					</table>
				</p>
			</form>
		</div>
		<div id="context_results"></div>';
    	
    	return $input;
    }
    
    public function searchBlock()
    {
    	$script  = $this->getJavaScriptFile('jquery/1.2.3/jquery-1.2.3.pack.js', 'htmlelements');
		$script .= $this->getJavaScriptFile('jquery/jquery.tablesorter.js', 'htmlelements');		
		$script .= $this->getJavaScriptFile('jquery/plugins/tablesorter/pager/jquery.tablesorter.pager.js', 'htmlelements');
		$script .= '<link rel="stylesheet" href="'.$this->getResourceUri('jquery/plugins/themes/blue/style.css', 'htmlelements').'" type="text/css" />';
		$script .= '<script type="text/javascript" id="js">
						$(function() {
								$("table")
									.tablesorter({widthFixed: true, widgets: [\'zebra\']})
									.tablesorterPager({container: $("#pager")});
							}); </script>';
		$this->appendArrayVar('headerParams', $script);
		
		$table = '<table cellspacing="1" class="tablesorter">
	<thead>
		<tr>
			<th>Name</th>
			<th>Major</th>
			<th>Sex</th>
			<th>English</th>
			<th>Japanese</th>
			<th>Calculus</th>
			<th>Geometry</th>

		</tr>
	</thead>
	<tfoot>
		<tr>
			<th>Name</th>
			<th>Major</th>
			<th>Sex</th>
			<th>English</th>
			<th>Japanese</th>
			<th>Calculus</th>
			<th>Geometry</th>

		</tr>
	</tfoot>
	<tbody>
		<tr>
			<td>Student01</td>
			<td>Languages</td>
			<td>male</td>

			<td>80</td>
			<td>70</td>
			<td>75</td>
			<td>80</td>
		</tr>
		<tr>
			<td>Student02</td>

			<td>Mathematics</td>
			<td>male</td>
			<td>90</td>
			<td>88</td>
			<td>100</td>
			<td>90</td>

		</tr>
		<tr>
			<td>Student03</td>
			<td>Languages</td>
			<td>female</td>
			<td>85</td>
			<td>95</td>

			<td>80</td>
			<td>85</td>
		</tr>
		<tr>
			<td>Student04</td>
			<td>Languages</td>
			<td>male</td>

			<td>60</td>
			<td>55</td>
			<td>100</td>
			<td>100</td>
		</tr>
		<tr>
			<td>Student05</td>

			<td>Languages</td>
			<td>female</td>
			<td>68</td>
			<td>80</td>
			<td>95</td>
			<td>80</td>

		</tr>
		<tr>
			<td>Student06</td>
			<td>Mathematics</td>
			<td>male</td>
			<td>100</td>
			<td>99</td>

			<td>100</td>
			<td>90</td>
		</tr>
		<tr>
			<td>Student07</td>
			<td>Mathematics</td>
			<td>male</td>

			<td>85</td>
			<td>68</td>
			<td>90</td>
			<td>90</td>
		</tr>
		<tr>
			<td>Student08</td>

			<td>Languages</td>
			<td>male</td>
			<td>100</td>
			<td>90</td>
			<td>90</td>
			<td>85</td>

		</tr>
		<tr>
			<td>Student09</td>
			<td>Mathematics</td>
			<td>male</td>
			<td>80</td>
			<td>50</td>

			<td>65</td>
			<td>75</td>
		</tr>
		<tr>
			<td>Student10</td>
			<td>Languages</td>
			<td>male</td>

			<td>85</td>
			<td>100</td>
			<td>100</td>
			<td>90</td>
		</tr>
		<tr>
			<td>Student11</td>

			<td>Languages</td>
			<td>male</td>
			<td>86</td>
			<td>85</td>
			<td>100</td>
			<td>100</td>

		</tr>
		<tr>
			<td>Student12</td>
			<td>Mathematics</td>
			<td>female</td>
			<td>100</td>
			<td>75</td>

			<td>70</td>
			<td>85</td>
		</tr>
		<tr>
			<td>Student13</td>
			<td>Languages</td>
			<td>female</td>

			<td>100</td>
			<td>80</td>
			<td>100</td>
			<td>90</td>
		</tr>
		<tr>
			<td>Student14</td>

			<td>Languages</td>
			<td>female</td>
			<td>50</td>
			<td>45</td>
			<td>55</td>
			<td>90</td>

		</tr>
		<tr>
			<td>Student15</td>
			<td>Languages</td>
			<td>male</td>
			<td>95</td>
			<td>35</td>

			<td>100</td>
			<td>90</td>
		</tr>
		<tr>
			<td>Student16</td>
			<td>Languages</td>
			<td>female</td>

			<td>100</td>
			<td>50</td>
			<td>30</td>
			<td>70</td>
		</tr>
		<tr>
			<td>Student17</td>

			<td>Languages</td>
			<td>female</td>
			<td>80</td>
			<td>100</td>
			<td>55</td>
			<td>65</td>

		</tr>
		<tr>
			<td>Student18</td>
			<td>Mathematics</td>
			<td>male</td>
			<td>30</td>
			<td>49</td>

			<td>55</td>
			<td>75</td>
		</tr>
		<tr>
			<td>Student19</td>
			<td>Languages</td>
			<td>male</td>

			<td>68</td>
			<td>90</td>
			<td>88</td>
			<td>70</td>
		</tr>
		<tr>
			<td>Student20</td>

			<td>Mathematics</td>
			<td>male</td>
			<td>40</td>
			<td>45</td>
			<td>40</td>
			<td>80</td>

		</tr>
		<tr>
			<td>Student21</td>
			<td>Languages</td>
			<td>male</td>
			<td>50</td>
			<td>45</td>

			<td>100</td>
			<td>100</td>
		</tr>
		<tr>
			<td>Student22</td>
			<td>Mathematics</td>
			<td>male</td>

			<td>100</td>
			<td>99</td>
			<td>100</td>
			<td>90</td>
		</tr>
		<tr>
			<td>Student23</td>

			<td>Languages</td>
			<td>female</td>
			<td>85</td>
			<td>80</td>
			<td>80</td>
			<td>80</td>

		</tr>
	<tr><td>student23</td><td>Mathematics</td><td>male</td><td>82</td><td>77</td><td>0</td><td>79</td></tr><tr><td>student24</td><td>Languages</td><td>female</td><td>100</td><td>91</td><td>13</td><td>82</td></tr><tr><td>student25</td><td>Mathematics</td><td>male</td><td>22</td><td>96</td><td>82</td><td>53</td></tr><tr><td>student26</td><td>Languages</td><td>female</td><td>37</td><td>29</td><td>56</td><td>59</td></tr><tr><td>student27</td><td>Mathematics</td><td>male</td><td>86</td><td>82</td><td>69</td><td>23</td></tr><tr><td>student28</td><td>Languages</td><td>female</td><td>44</td><td>25</td><td>43</td><td>1</td></tr><tr><td>student29</td><td>Mathematics</td><td>male</td><td>77</td><td>47</td><td>22</td><td>38</td></tr><tr><td>student30</td><td>Languages</td><td>female</td><td>19</td><td>35</td><td>23</td><td>10</td></tr><tr><td>student31</td><td>Mathematics</td><td>male</td><td>90</td><td>27</td><td>17</td><td>50</td></tr><tr><td>student32</td><td>Languages</td><td>female</td><td>60</td><td>75</td><td>33</td><td>38</td></tr><tr><td>student33</td><td>Mathematics</td><td>male</td><td>4</td><td>31</td><td>37</td><td>15</td></tr><tr><td>student34</td><td>Languages</td><td>female</td><td>77</td><td>97</td><td>81</td><td>44</td></tr><tr><td>student35</td><td>Mathematics</td><td>male</td><td>5</td><td>81</td><td>51</td><td>95</td></tr><tr><td>student36</td><td>Languages</td><td>female</td><td>70</td><td>61</td><td>70</td><td>94</td></tr><tr><td>student37</td><td>Mathematics</td><td>male</td><td>60</td><td>3</td><td>61</td><td>84</td></tr><tr><td>student38</td><td>Languages</td><td>female</td><td>63</td><td>39</td><td>0</td><td>11</td></tr><tr><td>student39</td><td>Mathematics</td><td>male</td><td>50</td><td>46</td><td>32</td><td>38</td></tr><tr><td>student40</td><td>Languages</td><td>female</td><td>51</td><td>75</td><td>25</td><td>3</td></tr><tr><td>student41</td><td>Mathematics</td><td>male</td><td>43</td><td>34</td><td>28</td><td>78</td></tr><tr><td>student42</td><td>Languages</td><td>female</td><td>11</td><td>89</td><td>60</td><td>95</td></tr><tr><td>student43</td><td>Mathematics</td><td>male</td><td>48</td><td>92</td><td>18</td><td>88</td></tr><tr><td>student44</td><td>Languages</td><td>female</td><td>82</td><td>2</td><td>59</td><td>73</td></tr><tr><td>student45</td><td>Mathematics</td><td>male</td><td>91</td><td>73</td><td>37</td><td>39</td></tr><tr><td>student46</td><td>Languages</td><td>female</td><td>4</td><td>8</td><td>12</td><td>10</td></tr><tr><td>student47</td><td>Mathematics</td><td>male</td><td>89</td><td>10</td><td>6</td><td>11</td></tr><tr><td>student48</td><td>Languages</td><td>female</td><td>90</td><td>32</td><td>21</td><td>18</td></tr><tr><td>student49</td><td>Mathematics</td><td>male</td><td>42</td><td>49</td><td>49</td><td>72</td></tr><tr><td>student50</td><td>Languages</td><td>female</td><td>56</td><td>37</td><td>67</td><td>54</td></tr><tr><td>student51</td><td>Mathematics</td><td>male</td><td>48</td><td>31</td><td>55</td><td>63</td></tr><tr><td>student52</td><td>Languages</td><td>female</td><td>38</td><td>91</td><td>71</td><td>74</td></tr><tr><td>student53</td><td>Mathematics</td><td>male</td><td>2</td><td>63</td><td>85</td><td>100</td></tr><tr><td>student54</td><td>Languages</td><td>female</td><td>75</td><td>81</td><td>16</td><td>23</td></tr><tr><td>student55</td><td>Mathematics</td><td>male</td><td>65</td><td>52</td><td>15</td><td>53</td></tr><tr><td>student56</td><td>Languages</td><td>female</td><td>23</td><td>52</td><td>79</td><td>94</td></tr><tr><td>student57</td><td>Mathematics</td><td>male</td><td>80</td><td>22</td><td>61</td><td>12</td></tr><tr><td>student58</td><td>Languages</td><td>female</td><td>53</td><td>5</td><td>79</td><td>79</td></tr><tr><td>student59</td><td>Mathematics</td><td>male</td><td>96</td><td>32</td><td>35</td><td>17</td></tr><tr><td>student60</td><td>Languages</td><td>female</td><td>16</td><td>76</td><td>65</td><td>27</td></tr><tr><td>student61</td><td>Mathematics</td><td>male</td><td>20</td><td>57</td><td>22</td><td>23</td></tr><tr><td>student62</td><td>Languages</td><td>female</td><td>19</td><td>83</td><td>87</td><td>78</td></tr><tr><td>student63</td><td>Mathematics</td><td>male</td><td>2</td><td>5</td><td>83</td><td>30</td></tr><tr><td>student64</td><td>Languages</td><td>female</td><td>0</td><td>21</td><td>9</td><td>93</td></tr><tr><td>student65</td><td>Mathematics</td><td>male</td><td>20</td><td>86</td><td>13</td><td>96</td></tr><tr><td>student66</td><td>Languages</td><td>female</td><td>28</td><td>35</td><td>87</td><td>57</td></tr><tr><td>student67</td><td>Mathematics</td><td>male</td><td>36</td><td>50</td><td>29</td><td>10</td></tr><tr><td>student68</td><td>Languages</td><td>female</td><td>60</td><td>90</td><td>96</td><td>6</td></tr><tr><td>student69</td><td>Mathematics</td><td>male</td><td>34</td><td>61</td><td>43</td><td>98</td></tr><tr><td>student70</td><td>Languages</td><td>female</td><td>13</td><td>37</td><td>91</td><td>83</td></tr><tr><td>student71</td><td>Mathematics</td><td>male</td><td>47</td><td>80</td><td>57</td><td>82</td></tr><tr><td>student72</td><td>Languages</td><td>female</td><td>69</td><td>43</td><td>37</td><td>37</td></tr><tr><td>student73</td><td>Mathematics</td><td>male</td><td>54</td><td>60</td><td>94</td><td>21</td></tr><tr><td>student74</td><td>Languages</td><td>female</td><td>71</td><td>14</td><td>34</td><td>46</td></tr><tr><td>student75</td><td>Mathematics</td><td>male</td><td>89</td><td>96</td><td>31</td><td>17</td></tr><tr><td>student76</td><td>Languages</td><td>female</td><td>28</td><td>48</td><td>29</td><td>94</td></tr><tr><td>student77</td><td>Mathematics</td><td>male</td><td>100</td><td>65</td><td>20</td><td>24</td></tr><tr><td>student78</td><td>Languages</td><td>female</td><td>11</td><td>96</td><td>90</td><td>33</td></tr><tr><td>student79</td><td>Mathematics</td><td>male</td><td>53</td><td>55</td><td>93</td><td>39</td></tr><tr><td>student80</td><td>Languages</td><td>female</td><td>1</td><td>100</td><td>84</td><td>44</td></tr><tr><td>student81</td><td>Mathematics</td><td>male</td><td>63</td><td>78</td><td>96</td><td>43</td></tr><tr><td>student82</td><td>Languages</td><td>female</td><td>41</td><td>69</td><td>82</td><td>35</td></tr><tr><td>student83</td><td>Mathematics</td><td>male</td><td>94</td><td>98</td><td>13</td><td>9</td></tr><tr><td>student84</td><td>Languages</td><td>female</td><td>94</td><td>72</td><td>91</td><td>77</td></tr><tr><td>student85</td><td>Mathematics</td><td>male</td><td>71</td><td>32</td><td>45</td><td>25</td></tr><tr><td>student86</td><td>Languages</td><td>female</td><td>9</td><td>89</td><td>64</td><td>37</td></tr><tr><td>student87</td><td>Mathematics</td><td>male</td><td>89</td><td>1</td><td>73</td><td>67</td></tr><tr><td>student88</td><td>Languages</td><td>female</td><td>43</td><td>41</td><td>68</td><td>79</td></tr><tr><td>student89</td><td>Mathematics</td><td>male</td><td>7</td><td>38</td><td>22</td><td>37</td></tr><tr><td>student90</td><td>Languages</td><td>female</td><td>94</td><td>83</td><td>93</td><td>37</td></tr><tr><td>student91</td><td>Mathematics</td><td>male</td><td>82</td><td>84</td><td>2</td><td>61</td></tr><tr><td>student92</td><td>Languages</td><td>female</td><td>46</td><td>41</td><td>30</td><td>69</td></tr><tr><td>student93</td><td>Mathematics</td><td>male</td><td>47</td><td>19</td><td>85</td><td>83</td></tr><tr><td>student94</td><td>Languages</td><td>female</td><td>39</td><td>14</td><td>64</td><td>62</td></tr><tr><td>student95</td><td>Mathematics</td><td>male</td><td>71</td><td>31</td><td>46</td><td>28</td></tr><tr><td>student96</td><td>Languages</td><td>female</td><td>90</td><td>94</td><td>45</td><td>40</td></tr><tr><td>student97</td><td>Mathematics</td><td>male</td><td>46</td><td>89</td><td>2</td><td>5</td></tr><tr><td>student98</td><td>Languages</td><td>female</td><td>41</td><td>43</td><td>47</td><td>99</td></tr><tr><td>student99</td><td>Mathematics</td><td>male</td><td>71</td><td>90</td><td>89</td><td>73</td></tr><tr><td>student100</td><td>Languages</td><td>female</td><td>31</td><td>64</td><td>18</td><td>56</td></tr><tr><td>student101</td><td>Mathematics</td><td>male</td><td>52</td><td>13</td><td>69</td><td>99</td></tr><tr><td>student102</td><td>Languages</td><td>female</td><td>86</td><td>39</td><td>83</td><td>18</td></tr><tr><td>student103</td><td>Mathematics</td><td>male</td><td>23</td><td>65</td><td>98</td><td>80</td></tr><tr><td>student104</td><td>Languages</td><td>female</td><td>78</td><td>100</td><td>57</td><td>66</td></tr><tr><td>student105</td><td>Mathematics</td><td>male</td><td>69</td><td>21</td><td>43</td><td>97</td></tr><tr><td>student106</td><td>Languages</td><td>female</td><td>27</td><td>2</td><td>78</td><td>38</td></tr><tr><td>student107</td><td>Mathematics</td><td>male</td><td>86</td><td>96</td><td>46</td><td>34</td></tr><tr><td>student108</td><td>Languages</td><td>female</td><td>13</td><td>84</td><td>66</td><td>64</td></tr><tr><td>student109</td><td>Mathematics</td><td>male</td><td>35</td><td>95</td><td>98</td><td>81</td></tr><tr><td>student110</td><td>Languages</td><td>female</td><td>30</td><td>28</td><td>62</td><td>54</td></tr><tr><td>student111</td><td>Mathematics</td><td>male</td><td>60</td><td>31</td><td>35</td><td>85</td></tr><tr><td>student112</td><td>Languages</td><td>female</td><td>19</td><td>81</td><td>19</td><td>69</td></tr><tr><td>student113</td><td>Mathematics</td><td>male</td><td>66</td><td>5</td><td>98</td><td>54</td></tr><tr><td>student114</td><td>Languages</td><td>female</td><td>38</td><td>80</td><td>40</td><td>16</td></tr><tr><td>student115</td><td>Mathematics</td><td>male</td><td>5</td><td>84</td><td>96</td><td>97</td></tr><tr><td>student116</td><td>Languages</td><td>female</td><td>59</td><td>97</td><td>69</td><td>54</td></tr><tr><td>student117</td><td>Mathematics</td><td>male</td><td>0</td><td>34</td><td>79</td><td>49</td></tr><tr><td>student118</td><td>Languages</td><td>female</td><td>18</td><td>7</td><td>12</td><td>85</td></tr><tr><td>student119</td><td>Mathematics</td><td>male</td><td>93</td><td>87</td><td>7</td><td>59</td></tr><tr><td>student120</td><td>Languages</td><td>female</td><td>42</td><td>23</td><td>26</td><td>90</td></tr><tr><td>student121</td><td>Mathematics</td><td>male</td><td>17</td><td>39</td><td>66</td><td>89</td></tr><tr><td>student122</td><td>Languages</td><td>female</td><td>26</td><td>75</td><td>90</td><td>18</td></tr><tr><td>student123</td><td>Mathematics</td><td>male</td><td>34</td><td>23</td><td>77</td><td>80</td></tr><tr><td>student124</td><td>Languages</td><td>female</td><td>52</td><td>6</td><td>77</td><td>42</td></tr><tr><td>student125</td><td>Mathematics</td><td>male</td><td>56</td><td>2</td><td>85</td><td>81</td></tr><tr><td>student126</td><td>Languages</td><td>female</td><td>51</td><td>35</td><td>67</td><td>44</td></tr><tr><td>student127</td><td>Mathematics</td><td>male</td><td>64</td><td>64</td><td>44</td><td>34</td></tr><tr><td>student128</td><td>Languages</td><td>female</td><td>67</td><td>91</td><td>79</td><td>82</td></tr><tr><td>student129</td><td>Mathematics</td><td>male</td><td>4</td><td>26</td><td>15</td><td>79</td></tr><tr><td>student130</td><td>Languages</td><td>female</td><td>72</td><td>10</td><td>3</td><td>69</td></tr><tr><td>student131</td><td>Mathematics</td><td>male</td><td>94</td><td>77</td><td>51</td><td>1</td></tr><tr><td>student132</td><td>Languages</td><td>female</td><td>27</td><td>95</td><td>85</td><td>48</td></tr><tr><td>student133</td><td>Mathematics</td><td>male</td><td>92</td><td>11</td><td>40</td><td>61</td></tr><tr><td>student134</td><td>Languages</td><td>female</td><td>4</td><td>18</td><td>56</td><td>60</td></tr><tr><td>student135</td><td>Mathematics</td><td>male</td><td>8</td><td>42</td><td>26</td><td>52</td></tr><tr><td>student136</td><td>Languages</td><td>female</td><td>7</td><td>60</td><td>47</td><td>21</td></tr><tr><td>student137</td><td>Mathematics</td><td>male</td><td>51</td><td>81</td><td>30</td><td>90</td></tr><tr><td>student138</td><td>Languages</td><td>female</td><td>58</td><td>6</td><td>16</td><td>73</td></tr><tr><td>student139</td><td>Mathematics</td><td>male</td><td>48</td><td>38</td><td>37</td><td>31</td></tr><tr><td>student140</td><td>Languages</td><td>female</td><td>33</td><td>26</td><td>56</td><td>60</td></tr><tr><td>student141</td><td>Mathematics</td><td>male</td><td>84</td><td>84</td><td>29</td><td>75</td></tr><tr><td>student142</td><td>Languages</td><td>female</td><td>72</td><td>35</td><td>6</td><td>54</td></tr><tr><td>student143</td><td>Mathematics</td><td>male</td><td>31</td><td>42</td><td>70</td><td>82</td></tr><tr><td>student144</td><td>Languages</td><td>female</td><td>94</td><td>87</td><td>50</td><td>35</td></tr><tr><td>student145</td><td>Mathematics</td><td>male</td><td>91</td><td>52</td><td>80</td><td>26</td></tr><tr><td>student146</td><td>Languages</td><td>female</td><td>78</td><td>65</td><td>79</td><td>79</td></tr><tr><td>student147</td><td>Mathematics</td><td>male</td><td>50</td><td>90</td><td>59</td><td>71</td></tr><tr><td>student148</td><td>Languages</td><td>female</td><td>15</td><td>68</td><td>66</td><td>33</td></tr><tr><td>student149</td><td>Mathematics</td><td>male</td><td>17</td><td>36</td><td>34</td><td>13</td></tr><tr><td>student150</td><td>Languages</td><td>female</td><td>30</td><td>95</td><td>69</td><td>73</td></tr><tr><td>student151</td><td>Mathematics</td><td>male</td><td>20</td><td>53</td><td>49</td><td>58</td></tr><tr><td>student152</td><td>Languages</td><td>female</td><td>19</td><td>89</td><td>60</td><td>60</td></tr><tr><td>student153</td><td>Mathematics</td><td>male</td><td>52</td><td>82</td><td>20</td><td>3</td></tr><tr><td>student154</td><td>Languages</td><td>female</td><td>66</td><td>98</td><td>53</td><td>66</td></tr><tr><td>student155</td><td>Mathematics</td><td>male</td><td>5</td><td>85</td><td>22</td><td>58</td></tr><tr><td>student156</td><td>Languages</td><td>female</td><td>34</td><td>43</td><td>68</td><td>8</td></tr><tr><td>student157</td><td>Mathematics</td><td>male</td><td>43</td><td>0</td><td>91</td><td>14</td></tr><tr><td>student158</td><td>Languages</td><td>female</td><td>34</td><td>18</td><td>67</td><td>31</td></tr><tr><td>student159</td><td>Mathematics</td><td>male</td><td>79</td><td>73</td><td>34</td><td>52</td></tr><tr><td>student160</td><td>Languages</td><td>female</td><td>15</td><td>61</td><td>37</td><td>27</td></tr><tr><td>student161</td><td>Mathematics</td><td>male</td><td>74</td><td>77</td><td>15</td><td>45</td></tr><tr><td>student162</td><td>Languages</td><td>female</td><td>52</td><td>62</td><td>19</td><td>58</td></tr><tr><td>student163</td><td>Mathematics</td><td>male</td><td>77</td><td>60</td><td>27</td><td>95</td></tr><tr><td>student164</td><td>Languages</td><td>female</td><td>96</td><td>1</td><td>93</td><td>57</td></tr><tr><td>student165</td><td>Mathematics</td><td>male</td><td>51</td><td>63</td><td>75</td><td>19</td></tr><tr><td>student166</td><td>Languages</td><td>female</td><td>32</td><td>44</td><td>72</td><td>99</td></tr><tr><td>student167</td><td>Mathematics</td><td>male</td><td>82</td><td>84</td><td>57</td><td>63</td></tr><tr><td>student168</td><td>Languages</td><td>female</td><td>53</td><td>12</td><td>85</td><td>67</td></tr><tr><td>student169</td><td>Mathematics</td><td>male</td><td>49</td><td>16</td><td>84</td><td>6</td></tr><tr><td>student170</td><td>Languages</td><td>female</td><td>39</td><td>34</td><td>16</td><td>65</td></tr><tr><td>student171</td><td>Mathematics</td><td>male</td><td>100</td><td>68</td><td>88</td><td>4</td></tr><tr><td>student172</td><td>Languages</td><td>female</td><td>14</td><td>25</td><td>63</td><td>52</td></tr><tr><td>student173</td><td>Mathematics</td><td>male</td><td>74</td><td>26</td><td>15</td><td>60</td></tr><tr><td>student174</td><td>Languages</td><td>female</td><td>11</td><td>58</td><td>8</td><td>92</td></tr><tr><td>student175</td><td>Mathematics</td><td>male</td><td>62</td><td>47</td><td>2</td><td>31</td></tr><tr><td>student176</td><td>Languages</td><td>female</td><td>65</td><td>26</td><td>32</td><td>42</td></tr><tr><td>student177</td><td>Mathematics</td><td>male</td><td>83</td><td>78</td><td>69</td><td>24</td></tr><tr><td>student178</td><td>Languages</td><td>female</td><td>14</td><td>100</td><td>7</td><td>43</td></tr><tr><td>student179</td><td>Mathematics</td><td>male</td><td>28</td><td>35</td><td>89</td><td>7</td></tr><tr><td>student180</td><td>Languages</td><td>female</td><td>1</td><td>48</td><td>39</td><td>62</td></tr><tr><td>student181</td><td>Mathematics</td><td>male</td><td>14</td><td>4</td><td>24</td><td>69</td></tr><tr><td>student182</td><td>Languages</td><td>female</td><td>64</td><td>52</td><td>72</td><td>2</td></tr><tr><td>student183</td><td>Mathematics</td><td>male</td><td>15</td><td>26</td><td>27</td><td>85</td></tr><tr><td>student184</td><td>Languages</td><td>female</td><td>91</td><td>49</td><td>40</td><td>7</td></tr><tr><td>student185</td><td>Mathematics</td><td>male</td><td>87</td><td>89</td><td>42</td><td>87</td></tr><tr><td>student186</td><td>Languages</td><td>female</td><td>75</td><td>76</td><td>61</td><td>88</td></tr><tr><td>student187</td><td>Mathematics</td><td>male</td><td>11</td><td>48</td><td>66</td><td>30</td></tr><tr><td>student188</td><td>Languages</td><td>female</td><td>73</td><td>7</td><td>92</td><td>72</td></tr><tr><td>student189</td><td>Mathematics</td><td>male</td><td>98</td><td>36</td><td>58</td><td>15</td></tr><tr><td>student190</td><td>Languages</td><td>female</td><td>80</td><td>2</td><td>86</td><td>56</td></tr><tr><td>student191</td><td>Mathematics</td><td>male</td><td>36</td><td>33</td><td>97</td><td>4</td></tr><tr><td>student192</td><td>Languages</td><td>female</td><td>59</td><td>2</td><td>33</td><td>90</td></tr><tr><td>student193</td><td>Mathematics</td><td>male</td><td>94</td><td>6</td><td>19</td><td>33</td></tr><tr><td>student194</td><td>Languages</td><td>female</td><td>82</td><td>49</td><td>72</td><td>42</td></tr><tr><td>student195</td><td>Mathematics</td><td>male</td><td>80</td><td>59</td><td>8</td><td>30</td></tr><tr><td>student196</td><td>Languages</td><td>female</td><td>89</td><td>17</td><td>90</td><td>27</td></tr><tr><td>student197</td><td>Mathematics</td><td>male</td><td>46</td><td>22</td><td>6</td><td>67</td></tr><tr><td>student198</td><td>Languages</td><td>female</td><td>65</td><td>75</td><td>73</td><td>77</td></tr><tr><td>student199</td><td>Mathematics</td><td>male</td><td>77</td><td>97</td><td>54</td><td>13</td></tr><tr><td>student200</td><td>Languages</td><td>female</td><td>78</td><td>19</td><td>57</td><td>96</td></tr><tr><td>student201</td><td>Mathematics</td><td>male</td><td>92</td><td>21</td><td>11</td><td>80</td></tr><tr><td>student202</td><td>Languages</td><td>female</td><td>45</td><td>49</td><td>93</td><td>40</td></tr><tr><td>student203</td><td>Mathematics</td><td>male</td><td>74</td><td>25</td><td>87</td><td>53</td></tr><tr><td>student204</td><td>Languages</td><td>female</td><td>15</td><td>71</td><td>23</td><td>4</td></tr><tr><td>student205</td><td>Mathematics</td><td>male</td><td>82</td><td>97</td><td>95</td><td>73</td></tr><tr><td>student206</td><td>Languages</td><td>female</td><td>82</td><td>60</td><td>58</td><td>98</td></tr><tr><td>student207</td><td>Mathematics</td><td>male</td><td>26</td><td>64</td><td>11</td><td>100</td></tr><tr><td>student208</td><td>Languages</td><td>female</td><td>64</td><td>9</td><td>60</td><td>45</td></tr><tr><td>student209</td><td>Mathematics</td><td>male</td><td>96</td><td>81</td><td>96</td><td>63</td></tr><tr><td>student210</td><td>Languages</td><td>female</td><td>24</td><td>39</td><td>0</td><td>69</td></tr><tr><td>student211</td><td>Mathematics</td><td>male</td><td>86</td><td>64</td><td>7</td><td>10</td></tr><tr><td>student212</td><td>Languages</td><td>female</td><td>7</td><td>64</td><td>50</td><td>7</td></tr><tr><td>student213</td><td>Mathematics</td><td>male</td><td>59</td><td>12</td><td>26</td><td>77</td></tr><tr><td>student214</td><td>Languages</td><td>female</td><td>21</td><td>25</td><td>93</td><td>82</td></tr><tr><td>student215</td><td>Mathematics</td><td>male</td><td>22</td><td>18</td><td>64</td><td>51</td></tr><tr><td>student216</td><td>Languages</td><td>female</td><td>92</td><td>41</td><td>98</td><td>28</td></tr><tr><td>student217</td><td>Mathematics</td><td>male</td><td>32</td><td>48</td><td>14</td><td>17</td></tr><tr><td>student218</td><td>Languages</td><td>female</td><td>62</td><td>36</td><td>85</td><td>56</td></tr><tr><td>student219</td><td>Mathematics</td><td>male</td><td>33</td><td>37</td><td>90</td><td>87</td></tr><tr><td>student220</td><td>Languages</td><td>female</td><td>24</td><td>43</td><td>60</td><td>84</td></tr><tr><td>student221</td><td>Mathematics</td><td>male</td><td>6</td><td>59</td><td>37</td><td>51</td></tr><tr><td>student222</td><td>Languages</td><td>female</td><td>91</td><td>97</td><td>5</td><td>76</td></tr><tr><td>student223</td><td>Mathematics</td><td>male</td><td>86</td><td>29</td><td>32</td><td>27</td></tr><tr><td>student224</td><td>Languages</td><td>female</td><td>63</td><td>59</td><td>68</td><td>91</td></tr><tr><td>student225</td><td>Mathematics</td><td>male</td><td>57</td><td>73</td><td>95</td><td>68</td></tr><tr><td>student226</td><td>Languages</td><td>female</td><td>38</td><td>54</td><td>59</td><td>87</td></tr><tr><td>student227</td><td>Mathematics</td><td>male</td><td>53</td><td>62</td><td>72</td><td>64</td></tr><tr><td>student228</td><td>Languages</td><td>female</td><td>62</td><td>84</td><td>72</td><td>73</td></tr><tr><td>student229</td><td>Mathematics</td><td>male</td><td>13</td><td>0</td><td>83</td><td>58</td></tr><tr><td>student230</td><td>Languages</td><td>female</td><td>35</td><td>65</td><td>80</td><td>87</td></tr><tr><td>student231</td><td>Mathematics</td><td>male</td><td>76</td><td>20</td><td>28</td><td>50</td></tr><tr><td>student232</td><td>Languages</td><td>female</td><td>9</td><td>17</td><td>66</td><td>33</td></tr><tr><td>student233</td><td>Mathematics</td><td>male</td><td>92</td><td>2</td><td>99</td><td>61</td></tr><tr><td>student234</td><td>Languages</td><td>female</td><td>47</td><td>69</td><td>98</td><td>39</td></tr><tr><td>student235</td><td>Mathematics</td><td>male</td><td>21</td><td>44</td><td>38</td><td>82</td></tr><tr><td>student236</td><td>Languages</td><td>female</td><td>19</td><td>86</td><td>51</td><td>78</td></tr><tr><td>student237</td><td>Mathematics</td><td>male</td><td>28</td><td>45</td><td>49</td><td>36</td></tr><tr><td>student238</td><td>Languages</td><td>female</td><td>78</td><td>19</td><td>49</td><td>81</td></tr><tr><td>student239</td><td>Mathematics</td><td>male</td><td>72</td><td>69</td><td>47</td><td>20</td></tr><tr><td>student240</td><td>Languages</td><td>female</td><td>17</td><td>43</td><td>66</td><td>56</td></tr><tr><td>student241</td><td>Mathematics</td><td>male</td><td>90</td><td>1</td><td>94</td><td>4</td></tr><tr><td>student242</td><td>Languages</td><td>female</td><td>6</td><td>18</td><td>2</td><td>51</td></tr><tr><td>student243</td><td>Mathematics</td><td>male</td><td>1</td><td>37</td><td>72</td><td>13</td></tr><tr><td>student244</td><td>Languages</td><td>female</td><td>80</td><td>0</td><td>58</td><td>54</td></tr><tr><td>student245</td><td>Mathematics</td><td>male</td><td>83</td><td>31</td><td>85</td><td>9</td></tr><tr><td>student246</td><td>Languages</td><td>female</td><td>90</td><td>99</td><td>29</td><td>12</td></tr><tr><td>student247</td><td>Mathematics</td><td>male</td><td>89</td><td>23</td><td>81</td><td>59</td></tr><tr><td>student248</td><td>Languages</td><td>female</td><td>72</td><td>26</td><td>28</td><td>3</td></tr><tr><td>student249</td><td>Mathematics</td><td>male</td><td>28</td><td>10</td><td>50</td><td>47</td></tr><tr><td>student250</td><td>Languages</td><td>female</td><td>89</td><td>14</td><td>89</td><td>4</td></tr><tr><td>student251</td><td>Mathematics</td><td>male</td><td>15</td><td>23</td><td>37</td><td>69</td></tr><tr><td>student252</td><td>Languages</td><td>female</td><td>27</td><td>82</td><td>10</td><td>36</td></tr><tr><td>student253</td><td>Mathematics</td><td>male</td><td>49</td><td>45</td><td>64</td><td>23</td></tr><tr><td>student254</td><td>Languages</td><td>female</td><td>79</td><td>75</td><td>63</td><td>74</td></tr><tr><td>student255</td><td>Mathematics</td><td>male</td><td>2</td><td>56</td><td>64</td><td>75</td></tr><tr><td>student256</td><td>Languages</td><td>female</td><td>36</td><td>26</td><td>29</td><td>58</td></tr><tr><td>student257</td><td>Mathematics</td><td>male</td><td>17</td><td>22</td><td>66</td><td>73</td></tr><tr><td>student258</td><td>Languages</td><td>female</td><td>70</td><td>91</td><td>97</td><td>45</td></tr><tr><td>student259</td><td>Mathematics</td><td>male</td><td>34</td><td>30</td><td>78</td><td>30</td></tr><tr><td>student260</td><td>Languages</td><td>female</td><td>77</td><td>57</td><td>86</td><td>77</td></tr><tr><td>student261</td><td>Mathematics</td><td>male</td><td>12</td><td>59</td><td>68</td><td>7</td></tr><tr><td>student262</td><td>Languages</td><td>female</td><td>11</td><td>60</td><td>97</td><td>71</td></tr><tr><td>student263</td><td>Mathematics</td><td>male</td><td>12</td><td>30</td><td>35</td><td>58</td></tr><tr><td>student264</td><td>Languages</td><td>female</td><td>46</td><td>15</td><td>23</td><td>40</td></tr><tr><td>student265</td><td>Mathematics</td><td>male</td><td>44</td><td>81</td><td>9</td><td>26</td></tr><tr><td>student266</td><td>Languages</td><td>female</td><td>15</td><td>68</td><td>32</td><td>15</td></tr><tr><td>student267</td><td>Mathematics</td><td>male</td><td>5</td><td>58</td><td>50</td><td>98</td></tr><tr><td>student268</td><td>Languages</td><td>female</td><td>42</td><td>30</td><td>32</td><td>24</td></tr><tr><td>student269</td><td>Mathematics</td><td>male</td><td>78</td><td>100</td><td>99</td><td>57</td></tr><tr><td>student270</td><td>Languages</td><td>female</td><td>55</td><td>33</td><td>87</td><td>25</td></tr><tr><td>student271</td><td>Mathematics</td><td>male</td><td>25</td><td>97</td><td>29</td><td>93</td></tr><tr><td>student272</td><td>Languages</td><td>female</td><td>39</td><td>35</td><td>18</td><td>43</td></tr><tr><td>student273</td><td>Mathematics</td><td>male</td><td>35</td><td>17</td><td>99</td><td>58</td></tr><tr><td>student274</td><td>Languages</td><td>female</td><td>86</td><td>52</td><td>27</td><td>24</td></tr><tr><td>student275</td><td>Mathematics</td><td>male</td><td>97</td><td>38</td><td>73</td><td>76</td></tr><tr><td>student276</td><td>Languages</td><td>female</td><td>20</td><td>6</td><td>19</td><td>8</td></tr><tr><td>student277</td><td>Mathematics</td><td>male</td><td>93</td><td>36</td><td>9</td><td>47</td></tr><tr><td>student278</td><td>Languages</td><td>female</td><td>42</td><td>3</td><td>15</td><td>2</td></tr><tr><td>student279</td><td>Mathematics</td><td>male</td><td>61</td><td>18</td><td>96</td><td>2</td></tr><tr><td>student280</td><td>Languages</td><td>female</td><td>99</td><td>89</td><td>87</td><td>94</td></tr><tr><td>student281</td><td>Mathematics</td><td>male</td><td>48</td><td>95</td><td>90</td><td>0</td></tr><tr><td>student282</td><td>Languages</td><td>female</td><td>60</td><td>47</td><td>31</td><td>30</td></tr><tr><td>student283</td><td>Mathematics</td><td>male</td><td>64</td><td>24</td><td>10</td><td>76</td></tr><tr><td>student284</td><td>Languages</td><td>female</td><td>99</td><td>37</td><td>4</td><td>68</td></tr><tr><td>student285</td><td>Mathematics</td><td>male</td><td>0</td><td>98</td><td>68</td><td>69</td></tr><tr><td>student286</td><td>Languages</td><td>female</td><td>66</td><td>82</td><td>49</td><td>59</td></tr><tr><td>student287</td><td>Mathematics</td><td>male</td><td>86</td><td>14</td><td>37</td><td>17</td></tr><tr><td>student288</td><td>Languages</td><td>female</td><td>27</td><td>48</td><td>93</td><td>27</td></tr><tr><td>student289</td><td>Mathematics</td><td>male</td><td>84</td><td>89</td><td>6</td><td>68</td></tr><tr><td>student290</td><td>Languages</td><td>female</td><td>99</td><td>0</td><td>20</td><td>57</td></tr><tr><td>student291</td><td>Mathematics</td><td>male</td><td>50</td><td>96</td><td>72</td><td>42</td></tr><tr><td>student292</td><td>Languages</td><td>female</td><td>98</td><td>2</td><td>27</td><td>92</td></tr><tr><td>student293</td><td>Mathematics</td><td>male</td><td>19</td><td>9</td><td>42</td><td>87</td></tr><tr><td>student294</td><td>Languages</td><td>female</td><td>98</td><td>97</td><td>9</td><td>22</td></tr><tr><td>student295</td><td>Mathematics</td><td>male</td><td>75</td><td>30</td><td>77</td><td>64</td></tr><tr><td>student296</td><td>Languages</td><td>female</td><td>51</td><td>98</td><td>55</td><td>3</td></tr><tr><td>student297</td><td>Mathematics</td><td>male</td><td>25</td><td>95</td><td>86</td><td>72</td></tr><tr><td>student298</td><td>Languages</td><td>female</td><td>20</td><td>75</td><td>37</td><td>35</td></tr><tr><td>student299</td><td>Mathematics</td><td>male</td><td>4</td><td>92</td><td>41</td><td>11</td></tr><tr><td>student300</td><td>Languages</td><td>female</td><td>28</td><td>3</td><td>28</td><td>91</td></tr><tr><td>student301</td><td>Mathematics</td><td>male</td><td>41</td><td>63</td><td>4</td><td>25</td></tr><tr><td>student302</td><td>Languages</td><td>female</td><td>29</td><td>16</td><td>77</td><td>90</td></tr><tr><td>student303</td><td>Mathematics</td><td>male</td><td>89</td><td>41</td><td>51</td><td>82</td></tr><tr><td>student304</td><td>Languages</td><td>female</td><td>40</td><td>91</td><td>24</td><td>34</td></tr><tr><td>student305</td><td>Mathematics</td><td>male</td><td>7</td><td>47</td><td>49</td><td>78</td></tr><tr><td>student306</td><td>Languages</td><td>female</td><td>6</td><td>37</td><td>55</td><td>62</td></tr><tr><td>student307</td><td>Mathematics</td><td>male</td><td>30</td><td>73</td><td>34</td><td>90</td></tr><tr><td>student308</td><td>Languages</td><td>female</td><td>82</td><td>91</td><td>95</td><td>93</td></tr><tr><td>student309</td><td>Mathematics</td><td>male</td><td>62</td><td>4</td><td>73</td><td>82</td></tr><tr><td>student310</td><td>Languages</td><td>female</td><td>39</td><td>10</td><td>12</td><td>57</td></tr><tr><td>student311</td><td>Mathematics</td><td>male</td><td>89</td><td>64</td><td>20</td><td>67</td></tr><tr><td>student312</td><td>Languages</td><td>female</td><td>56</td><td>36</td><td>92</td><td>41</td></tr><tr><td>student313</td><td>Mathematics</td><td>male</td><td>99</td><td>80</td><td>99</td><td>74</td></tr><tr><td>student314</td><td>Languages</td><td>female</td><td>31</td><td>79</td><td>64</td><td>93</td></tr><tr><td>student315</td><td>Mathematics</td><td>male</td><td>53</td><td>2</td><td>70</td><td>55</td></tr><tr><td>student316</td><td>Languages</td><td>female</td><td>35</td><td>15</td><td>29</td><td>60</td></tr><tr><td>student317</td><td>Mathematics</td><td>male</td><td>31</td><td>47</td><td>69</td><td>60</td></tr><tr><td>student318</td><td>Languages</td><td>female</td><td>88</td><td>28</td><td>13</td><td>66</td></tr><tr><td>student319</td><td>Mathematics</td><td>male</td><td>65</td><td>12</td><td>16</td><td>40</td></tr><tr><td>student320</td><td>Languages</td><td>female</td><td>28</td><td>17</td><td>19</td><td>40</td></tr><tr><td>student321</td><td>Mathematics</td><td>male</td><td>24</td><td>100</td><td>44</td><td>70</td></tr><tr><td>student322</td><td>Languages</td><td>female</td><td>20</td><td>59</td><td>83</td><td>52</td></tr><tr><td>student323</td><td>Mathematics</td><td>male</td><td>17</td><td>60</td><td>82</td><td>91</td></tr><tr><td>student324</td><td>Languages</td><td>female</td><td>95</td><td>99</td><td>43</td><td>37</td></tr><tr><td>student325</td><td>Mathematics</td><td>male</td><td>30</td><td>18</td><td>99</td><td>31</td></tr><tr><td>student326</td><td>Languages</td><td>female</td><td>34</td><td>7</td><td>83</td><td>86</td></tr><tr><td>student327</td><td>Mathematics</td><td>male</td><td>98</td><td>63</td><td>4</td><td>35</td></tr><tr><td>student328</td><td>Languages</td><td>female</td><td>54</td><td>23</td><td>98</td><td>46</td></tr><tr><td>student329</td><td>Mathematics</td><td>male</td><td>97</td><td>93</td><td>45</td><td>18</td></tr><tr><td>student330</td><td>Languages</td><td>female</td><td>27</td><td>74</td><td>0</td><td>77</td></tr><tr><td>student331</td><td>Mathematics</td><td>male</td><td>9</td><td>70</td><td>41</td><td>37</td></tr><tr><td>student332</td><td>Languages</td><td>female</td><td>52</td><td>37</td><td>76</td><td>20</td></tr><tr><td>student333</td><td>Mathematics</td><td>male</td><td>74</td><td>18</td><td>68</td><td>19</td></tr><tr><td>student334</td><td>Languages</td><td>female</td><td>77</td><td>100</td><td>33</td><td>9</td></tr><tr><td>student335</td><td>Mathematics</td><td>male</td><td>38</td><td>53</td><td>77</td><td>18</td></tr><tr><td>student336</td><td>Languages</td><td>female</td><td>18</td><td>13</td><td>26</td><td>10</td></tr><tr><td>student337</td><td>Mathematics</td><td>male</td><td>90</td><td>47</td><td>87</td><td>70</td></tr><tr><td>student338</td><td>Languages</td><td>female</td><td>38</td><td>49</td><td>36</td><td>74</td></tr><tr><td>student339</td><td>Mathematics</td><td>male</td><td>100</td><td>64</td><td>13</td><td>72</td></tr><tr><td>student340</td><td>Languages</td><td>female</td><td>74</td><td>25</td><td>41</td><td>52</td></tr><tr><td>student341</td><td>Mathematics</td><td>male</td><td>37</td><td>13</td><td>16</td><td>13</td></tr><tr><td>student342</td><td>Languages</td><td>female</td><td>24</td><td>34</td><td>15</td><td>83</td></tr><tr><td>student343</td><td>Mathematics</td><td>male</td><td>20</td><td>5</td><td>67</td><td>28</td></tr><tr><td>student344</td><td>Languages</td><td>female</td><td>45</td><td>2</td><td>25</td><td>72</td></tr><tr><td>student345</td><td>Mathematics</td><td>male</td><td>19</td><td>11</td><td>75</td><td>35</td></tr><tr><td>student346</td><td>Languages</td><td>female</td><td>6</td><td>58</td><td>31</td><td>15</td></tr><tr><td>student347</td><td>Mathematics</td><td>male</td><td>16</td><td>66</td><td>36</td><td>11</td></tr><tr><td>student348</td><td>Languages</td><td>female</td><td>12</td><td>3</td><td>95</td><td>40</td></tr><tr><td>student349</td><td>Mathematics</td><td>male</td><td>7</td><td>52</td><td>74</td><td>2</td></tr><tr><td>student350</td><td>Languages</td><td>female</td><td>88</td><td>92</td><td>60</td><td>55</td></tr><tr><td>student351</td><td>Mathematics</td><td>male</td><td>92</td><td>70</td><td>91</td><td>45</td></tr><tr><td>student352</td><td>Languages</td><td>female</td><td>74</td><td>76</td><td>59</td><td>44</td></tr><tr><td>student353</td><td>Mathematics</td><td>male</td><td>63</td><td>69</td><td>60</td><td>94</td></tr><tr><td>student354</td><td>Languages</td><td>female</td><td>3</td><td>68</td><td>55</td><td>48</td></tr><tr><td>student355</td><td>Mathematics</td><td>male</td><td>39</td><td>96</td><td>21</td><td>48</td></tr><tr><td>student356</td><td>Languages</td><td>female</td><td>41</td><td>34</td><td>27</td><td>5</td></tr><tr><td>student357</td><td>Mathematics</td><td>male</td><td>64</td><td>3</td><td>47</td><td>33</td></tr><tr><td>student358</td><td>Languages</td><td>female</td><td>95</td><td>14</td><td>63</td><td>55</td></tr><tr><td>student359</td><td>Mathematics</td><td>male</td><td>70</td><td>100</td><td>13</td><td>82</td></tr><tr><td>student360</td><td>Languages</td><td>female</td><td>52</td><td>24</td><td>100</td><td>21</td></tr><tr><td>student361</td><td>Mathematics</td><td>male</td><td>0</td><td>40</td><td>86</td><td>9</td></tr><tr><td>student362</td><td>Languages</td><td>female</td><td>0</td><td>2</td><td>49</td><td>32</td></tr><tr><td>student363</td><td>Mathematics</td><td>male</td><td>23</td><td>10</td><td>86</td><td>94</td></tr><tr><td>student364</td><td>Languages</td><td>female</td><td>15</td><td>3</td><td>86</td><td>49</td></tr><tr><td>student365</td><td>Mathematics</td><td>male</td><td>76</td><td>23</td><td>31</td><td>0</td></tr><tr><td>student366</td><td>Languages</td><td>female</td><td>35</td><td>35</td><td>78</td><td>94</td></tr><tr><td>student367</td><td>Mathematics</td><td>male</td><td>29</td><td>42</td><td>43</td><td>100</td></tr><tr><td>student368</td><td>Languages</td><td>female</td><td>66</td><td>8</td><td>5</td><td>10</td></tr><tr><td>student369</td><td>Mathematics</td><td>male</td><td>74</td><td>15</td><td>56</td><td>83</td></tr><tr><td>student370</td><td>Languages</td><td>female</td><td>75</td><td>43</td><td>90</td><td>8</td></tr><tr><td>student371</td><td>Mathematics</td><td>male</td><td>40</td><td>60</td><td>4</td><td>70</td></tr><tr><td>student372</td><td>Languages</td><td>female</td><td>62</td><td>42</td><td>17</td><td>49</td></tr><tr><td>student373</td><td>Mathematics</td><td>male</td><td>31</td><td>46</td><td>44</td><td>54</td></tr><tr><td>student374</td><td>Languages</td><td>female</td><td>30</td><td>34</td><td>47</td><td>87</td></tr><tr><td>student375</td><td>Mathematics</td><td>male</td><td>9</td><td>69</td><td>41</td><td>52</td></tr><tr><td>student376</td><td>Languages</td><td>female</td><td>85</td><td>43</td><td>29</td><td>92</td></tr><tr><td>student377</td><td>Mathematics</td><td>male</td><td>79</td><td>0</td><td>40</td><td>25</td></tr><tr><td>student378</td><td>Languages</td><td>female</td><td>36</td><td>40</td><td>72</td><td>85</td></tr><tr><td>student379</td><td>Mathematics</td><td>male</td><td>53</td><td>68</td><td>88</td><td>2</td></tr><tr><td>student380</td><td>Languages</td><td>female</td><td>87</td><td>78</td><td>38</td><td>79</td></tr><tr><td>student381</td><td>Mathematics</td><td>male</td><td>89</td><td>97</td><td>83</td><td>38</td></tr><tr><td>student382</td><td>Languages</td><td>female</td><td>21</td><td>19</td><td>49</td><td>10</td></tr><tr><td>student383</td><td>Mathematics</td><td>male</td><td>47</td><td>12</td><td>68</td><td>50</td></tr><tr><td>student384</td><td>Languages</td><td>female</td><td>37</td><td>12</td><td>49</td><td>95</td></tr><tr><td>student385</td><td>Mathematics</td><td>male</td><td>84</td><td>0</td><td>88</td><td>51</td></tr><tr><td>student386</td><td>Languages</td><td>female</td><td>89</td><td>61</td><td>27</td><td>48</td></tr><tr><td>student387</td><td>Mathematics</td><td>male</td><td>10</td><td>47</td><td>87</td><td>61</td></tr><tr><td>student388</td><td>Languages</td><td>female</td><td>16</td><td>9</td><td>26</td><td>56</td></tr><tr><td>student389</td><td>Mathematics</td><td>male</td><td>57</td><td>33</td><td>13</td><td>47</td></tr><tr><td>student390</td><td>Languages</td><td>female</td><td>90</td><td>35</td><td>77</td><td>75</td></tr><tr><td>student391</td><td>Mathematics</td><td>male</td><td>31</td><td>47</td><td>47</td><td>53</td></tr><tr><td>student392</td><td>Languages</td><td>female</td><td>9</td><td>4</td><td>24</td><td>12</td></tr><tr><td>student393</td><td>Mathematics</td><td>male</td><td>61</td><td>19</td><td>81</td><td>7</td></tr><tr><td>student394</td><td>Languages</td><td>female</td><td>4</td><td>57</td><td>57</td><td>7</td></tr><tr><td>student395</td><td>Mathematics</td><td>male</td><td>67</td><td>29</td><td>21</td><td>2</td></tr><tr><td>student396</td><td>Languages</td><td>female</td><td>51</td><td>6</td><td>45</td><td>6</td></tr><tr><td>student397</td><td>Mathematics</td><td>male</td><td>93</td><td>14</td><td>77</td><td>14</td></tr><tr><td>student398</td><td>Languages</td><td>female</td><td>1</td><td>89</td><td>34</td><td>27</td></tr><tr><td>student399</td><td>Mathematics</td><td>male</td><td>93</td><td>77</td><td>57</td><td>91</td></tr><tr><td>student400</td><td>Languages</td><td>female</td><td>67</td><td>77</td><td>80</td><td>32</td></tr><tr><td>student401</td><td>Mathematics</td><td>male</td><td>58</td><td>89</td><td>4</td><td>17</td></tr><tr><td>student402</td><td>Languages</td><td>female</td><td>30</td><td>56</td><td>0</td><td>53</td></tr><tr><td>student403</td><td>Mathematics</td><td>male</td><td>28</td><td>25</td><td>32</td><td>59</td></tr><tr><td>student404</td><td>Languages</td><td>female</td><td>62</td><td>34</td><td>81</td><td>64</td></tr><tr><td>student405</td><td>Mathematics</td><td>male</td><td>29</td><td>84</td><td>26</td><td>23</td></tr><tr><td>student406</td><td>Languages</td><td>female</td><td>70</td><td>8</td><td>63</td><td>77</td></tr><tr><td>student407</td><td>Mathematics</td><td>male</td><td>8</td><td>65</td><td>47</td><td>99</td></tr><tr><td>student408</td><td>Languages</td><td>female</td><td>9</td><td>38</td><td>10</td><td>89</td></tr><tr><td>student409</td><td>Mathematics</td><td>male</td><td>84</td><td>21</td><td>46</td><td>58</td></tr><tr><td>student410</td><td>Languages</td><td>female</td><td>21</td><td>84</td><td>18</td><td>49</td></tr><tr><td>student411</td><td>Mathematics</td><td>male</td><td>27</td><td>9</td><td>63</td><td>40</td></tr><tr><td>student412</td><td>Languages</td><td>female</td><td>93</td><td>0</td><td>19</td><td>91</td></tr><tr><td>student413</td><td>Mathematics</td><td>male</td><td>31</td><td>92</td><td>87</td><td>43</td></tr><tr><td>student414</td><td>Languages</td><td>female</td><td>53</td><td>25</td><td>98</td><td>43</td></tr><tr><td>student415</td><td>Mathematics</td><td>male</td><td>36</td><td>75</td><td>80</td><td>89</td></tr><tr><td>student416</td><td>Languages</td><td>female</td><td>37</td><td>68</td><td>12</td><td>54</td></tr><tr><td>student417</td><td>Mathematics</td><td>male</td><td>25</td><td>89</td><td>12</td><td>53</td></tr><tr><td>student418</td><td>Languages</td><td>female</td><td>92</td><td>2</td><td>8</td><td>46</td></tr><tr><td>student419</td><td>Mathematics</td><td>male</td><td>11</td><td>28</td><td>60</td><td>58</td></tr><tr><td>student420</td><td>Languages</td><td>female</td><td>1</td><td>37</td><td>35</td><td>17</td></tr><tr><td>student421</td><td>Mathematics</td><td>male</td><td>67</td><td>30</td><td>38</td><td>85</td></tr><tr><td>student422</td><td>Languages</td><td>female</td><td>68</td><td>79</td><td>34</td><td>41</td></tr><tr><td>student423</td><td>Mathematics</td><td>male</td><td>72</td><td>45</td><td>93</td><td>41</td></tr><tr><td>student424</td><td>Languages</td><td>female</td><td>56</td><td>46</td><td>45</td><td>38</td></tr><tr><td>student425</td><td>Mathematics</td><td>male</td><td>86</td><td>21</td><td>84</td><td>0</td></tr><tr><td>student426</td><td>Languages</td><td>female</td><td>99</td><td>85</td><td>41</td><td>19</td></tr><tr><td>student427</td><td>Mathematics</td><td>male</td><td>71</td><td>35</td><td>3</td><td>89</td></tr><tr><td>student428</td><td>Languages</td><td>female</td><td>22</td><td>91</td><td>12</td><td>16</td></tr><tr><td>student429</td><td>Mathematics</td><td>male</td><td>15</td><td>3</td><td>26</td><td>93</td></tr><tr><td>student430</td><td>Languages</td><td>female</td><td>35</td><td>46</td><td>34</td><td>74</td></tr><tr><td>student431</td><td>Mathematics</td><td>male</td><td>33</td><td>83</td><td>97</td><td>20</td></tr><tr><td>student432</td><td>Languages</td><td>female</td><td>99</td><td>20</td><td>3</td><td>26</td></tr><tr><td>student433</td><td>Mathematics</td><td>male</td><td>48</td><td>42</td><td>83</td><td>18</td></tr><tr><td>student434</td><td>Languages</td><td>female</td><td>44</td><td>4</td><td>25</td><td>30</td></tr><tr><td>student435</td><td>Mathematics</td><td>male</td><td>78</td><td>48</td><td>60</td><td>45</td></tr><tr><td>student436</td><td>Languages</td><td>female</td><td>47</td><td>57</td><td>89</td><td>0</td></tr><tr><td>student437</td><td>Mathematics</td><td>male</td><td>88</td><td>12</td><td>100</td><td>53</td></tr><tr><td>student438</td><td>Languages</td><td>female</td><td>48</td><td>0</td><td>51</td><td>60</td></tr><tr><td>student439</td><td>Mathematics</td><td>male</td><td>70</td><td>89</td><td>85</td><td>16</td></tr><tr><td>student440</td><td>Languages</td><td>female</td><td>71</td><td>94</td><td>34</td><td>33</td></tr><tr><td>student441</td><td>Mathematics</td><td>male</td><td>68</td><td>13</td><td>72</td><td>18</td></tr><tr><td>student442</td><td>Languages</td><td>female</td><td>7</td><td>53</td><td>97</td><td>21</td></tr><tr><td>student443</td><td>Mathematics</td><td>male</td><td>65</td><td>36</td><td>60</td><td>87</td></tr><tr><td>student444</td><td>Languages</td><td>female</td><td>43</td><td>21</td><td>24</td><td>34</td></tr><tr><td>student445</td><td>Mathematics</td><td>male</td><td>85</td><td>77</td><td>65</td><td>28</td></tr><tr><td>student446</td><td>Languages</td><td>female</td><td>61</td><td>90</td><td>78</td><td>91</td></tr><tr><td>student447</td><td>Mathematics</td><td>male</td><td>92</td><td>0</td><td>78</td><td>12</td></tr><tr><td>student448</td><td>Languages</td><td>female</td><td>33</td><td>30</td><td>62</td><td>90</td></tr><tr><td>student449</td><td>Mathematics</td><td>male</td><td>86</td><td>16</td><td>74</td><td>5</td></tr><tr><td>student450</td><td>Languages</td><td>female</td><td>100</td><td>86</td><td>24</td><td>23</td></tr><tr><td>student451</td><td>Mathematics</td><td>male</td><td>14</td><td>25</td><td>6</td><td>45</td></tr><tr><td>student452</td><td>Languages</td><td>female</td><td>86</td><td>39</td><td>98</td><td>88</td></tr><tr><td>student453</td><td>Mathematics</td><td>male</td><td>72</td><td>68</td><td>77</td><td>19</td></tr><tr><td>student454</td><td>Languages</td><td>female</td><td>9</td><td>45</td><td>23</td><td>100</td></tr><tr><td>student455</td><td>Mathematics</td><td>male</td><td>34</td><td>67</td><td>89</td><td>79</td></tr><tr><td>student456</td><td>Languages</td><td>female</td><td>92</td><td>0</td><td>47</td><td>45</td></tr><tr><td>student457</td><td>Mathematics</td><td>male</td><td>64</td><td>58</td><td>26</td><td>98</td></tr><tr><td>student458</td><td>Languages</td><td>female</td><td>43</td><td>93</td><td>59</td><td>100</td></tr><tr><td>student459</td><td>Mathematics</td><td>male</td><td>82</td><td>35</td><td>97</td><td>81</td></tr><tr><td>student460</td><td>Languages</td><td>female</td><td>18</td><td>35</td><td>24</td><td>100</td></tr><tr><td>student461</td><td>Mathematics</td><td>male</td><td>79</td><td>80</td><td>43</td><td>51</td></tr><tr><td>student462</td><td>Languages</td><td>female</td><td>56</td><td>10</td><td>17</td><td>67</td></tr><tr><td>student463</td><td>Mathematics</td><td>male</td><td>36</td><td>44</td><td>14</td><td>85</td></tr><tr><td>student464</td><td>Languages</td><td>female</td><td>26</td><td>40</td><td>69</td><td>2</td></tr><tr><td>student465</td><td>Mathematics</td><td>male</td><td>59</td><td>93</td><td>43</td><td>78</td></tr><tr><td>student466</td><td>Languages</td><td>female</td><td>78</td><td>84</td><td>88</td><td>3</td></tr><tr><td>student467</td><td>Mathematics</td><td>male</td><td>41</td><td>37</td><td>80</td><td>60</td></tr><tr><td>student468</td><td>Languages</td><td>female</td><td>44</td><td>27</td><td>97</td><td>77</td></tr><tr><td>student469</td><td>Mathematics</td><td>male</td><td>29</td><td>19</td><td>64</td><td>82</td></tr><tr><td>student470</td><td>Languages</td><td>female</td><td>50</td><td>96</td><td>27</td><td>46</td></tr><tr><td>student471</td><td>Mathematics</td><td>male</td><td>49</td><td>15</td><td>51</td><td>45</td></tr><tr><td>student472</td><td>Languages</td><td>female</td><td>38</td><td>35</td><td>31</td><td>78</td></tr><tr><td>student473</td><td>Mathematics</td><td>male</td><td>1</td><td>80</td><td>23</td><td>65</td></tr><tr><td>student474</td><td>Languages</td><td>female</td><td>91</td><td>17</td><td>23</td><td>76</td></tr><tr><td>student475</td><td>Mathematics</td><td>male</td><td>57</td><td>39</td><td>35</td><td>63</td></tr><tr><td>student476</td><td>Languages</td><td>female</td><td>33</td><td>73</td><td>62</td><td>14</td></tr><tr><td>student477</td><td>Mathematics</td><td>male</td><td>96</td><td>16</td><td>88</td><td>40</td></tr><tr><td>student478</td><td>Languages</td><td>female</td><td>30</td><td>63</td><td>16</td><td>13</td></tr><tr><td>student479</td><td>Mathematics</td><td>male</td><td>74</td><td>39</td><td>37</td><td>87</td></tr><tr><td>student480</td><td>Languages</td><td>female</td><td>26</td><td>36</td><td>94</td><td>79</td></tr><tr><td>student481</td><td>Mathematics</td><td>male</td><td>19</td><td>58</td><td>65</td><td>12</td></tr><tr><td>student482</td><td>Languages</td><td>female</td><td>73</td><td>36</td><td>22</td><td>48</td></tr><tr><td>student483</td><td>Mathematics</td><td>male</td><td>78</td><td>94</td><td>75</td><td>7</td></tr><tr><td>student484</td><td>Languages</td><td>female</td><td>59</td><td>51</td><td>9</td><td>35</td></tr><tr><td>student485</td><td>Mathematics</td><td>male</td><td>67</td><td>71</td><td>100</td><td>85</td></tr><tr><td>student486</td><td>Languages</td><td>female</td><td>33</td><td>30</td><td>15</td><td>46</td></tr><tr><td>student487</td><td>Mathematics</td><td>male</td><td>12</td><td>19</td><td>16</td><td>37</td></tr><tr><td>student488</td><td>Languages</td><td>female</td><td>80</td><td>98</td><td>29</td><td>14</td></tr><tr><td>student489</td><td>Mathematics</td><td>male</td><td>70</td><td>51</td><td>14</td><td>31</td></tr><tr><td>student490</td><td>Languages</td><td>female</td><td>95</td><td>38</td><td>15</td><td>92</td></tr><tr><td>student491</td><td>Mathematics</td><td>male</td><td>60</td><td>31</td><td>74</td><td>12</td></tr><tr><td>student492</td><td>Languages</td><td>female</td><td>62</td><td>56</td><td>90</td><td>68</td></tr><tr><td>student493</td><td>Mathematics</td><td>male</td><td>63</td><td>11</td><td>29</td><td>91</td></tr><tr><td>student494</td><td>Languages</td><td>female</td><td>41</td><td>1</td><td>25</td><td>20</td></tr><tr><td>student495</td><td>Mathematics</td><td>male</td><td>60</td><td>5</td><td>31</td><td>44</td></tr><tr><td>student496</td><td>Languages</td><td>female</td><td>11</td><td>35</td><td>5</td><td>28</td></tr><tr><td>student497</td><td>Mathematics</td><td>male</td><td>11</td><td>96</td><td>42</td><td>37</td></tr><tr><td>student498</td><td>Languages</td><td>female</td><td>16</td><td>72</td><td>79</td><td>74</td></tr><tr><td>student499</td><td>Mathematics</td><td>male</td><td>9</td><td>21</td><td>22</td><td>66</td></tr><tr><td>student500</td><td>Languages</td><td>female</td><td>34</td><td>22</td><td>64</td><td>34</td></tr><tr><td>student501</td><td>Mathematics</td><td>male</td><td>50</td><td>93</td><td>86</td><td>61</td></tr><tr><td>student502</td><td>Languages</td><td>female</td><td>50</td><td>22</td><td>40</td><td>44</td></tr><tr><td>student503</td><td>Mathematics</td><td>male</td><td>3</td><td>8</td><td>39</td><td>17</td></tr><tr><td>student504</td><td>Languages</td><td>female</td><td>98</td><td>16</td><td>93</td><td>55</td></tr><tr><td>student505</td><td>Mathematics</td><td>male</td><td>86</td><td>89</td><td>36</td><td>28</td></tr><tr><td>student506</td><td>Languages</td><td>female</td><td>16</td><td>53</td><td>13</td><td>50</td></tr><tr><td>student507</td><td>Mathematics</td><td>male</td><td>57</td><td>57</td><td>3</td><td>38</td></tr><tr><td>student508</td><td>Languages</td><td>female</td><td>34</td><td>79</td><td>69</td><td>77</td></tr><tr><td>student509</td><td>Mathematics</td><td>male</td><td>2</td><td>4</td><td>16</td><td>59</td></tr><tr><td>student510</td><td>Languages</td><td>female</td><td>60</td><td>62</td><td>99</td><td>100</td></tr><tr><td>student511</td><td>Mathematics</td><td>male</td><td>65</td><td>52</td><td>52</td><td>95</td></tr><tr><td>student512</td><td>Languages</td><td>female</td><td>58</td><td>73</td><td>94</td><td>1</td></tr><tr><td>student513</td><td>Mathematics</td><td>male</td><td>39</td><td>75</td><td>28</td><td>76</td></tr><tr><td>student514</td><td>Languages</td><td>female</td><td>46</td><td>6</td><td>64</td><td>78</td></tr><tr><td>student515</td><td>Mathematics</td><td>male</td><td>51</td><td>60</td><td>99</td><td>8</td></tr><tr><td>student516</td><td>Languages</td><td>female</td><td>17</td><td>20</td><td>12</td><td>97</td></tr><tr><td>student517</td><td>Mathematics</td><td>male</td><td>72</td><td>17</td><td>96</td><td>73</td></tr><tr><td>student518</td><td>Languages</td><td>female</td><td>92</td><td>21</td><td>62</td><td>27</td></tr><tr><td>student519</td><td>Mathematics</td><td>male</td><td>50</td><td>42</td><td>4</td><td>33</td></tr><tr><td>student520</td><td>Languages</td><td>female</td><td>52</td><td>37</td><td>1</td><td>57</td></tr><tr><td>student521</td><td>Mathematics</td><td>male</td><td>58</td><td>40</td><td>35</td><td>54</td></tr><tr><td>student522</td><td>Languages</td><td>female</td><td>9</td><td>38</td><td>57</td><td>53</td></tr><tr><td>student523</td><td>Mathematics</td><td>male</td><td>79</td><td>20</td><td>18</td><td>18</td></tr><tr><td>student524</td><td>Languages</td><td>female</td><td>1</td><td>4</td><td>94</td><td>27</td></tr><tr><td>student525</td><td>Mathematics</td><td>male</td><td>95</td><td>41</td><td>29</td><td>98</td></tr><tr><td>student526</td><td>Languages</td><td>female</td><td>34</td><td>59</td><td>9</td><td>21</td></tr><tr><td>student527</td><td>Mathematics</td><td>male</td><td>39</td><td>66</td><td>41</td><td>29</td></tr><tr><td>student528</td><td>Languages</td><td>female</td><td>3</td><td>2</td><td>81</td><td>25</td></tr><tr><td>student529</td><td>Mathematics</td><td>male</td><td>33</td><td>44</td><td>37</td><td>85</td></tr><tr><td>student530</td><td>Languages</td><td>female</td><td>69</td><td>25</td><td>59</td><td>79</td></tr><tr><td>student531</td><td>Mathematics</td><td>male</td><td>13</td><td>50</td><td>49</td><td>52</td></tr><tr><td>student532</td><td>Languages</td><td>female</td><td>54</td><td>83</td><td>45</td><td>31</td></tr><tr><td>student533</td><td>Mathematics</td><td>male</td><td>15</td><td>24</td><td>97</td><td>51</td></tr><tr><td>student534</td><td>Languages</td><td>female</td><td>7</td><td>51</td><td>69</td><td>63</td></tr><tr><td>student535</td><td>Mathematics</td><td>male</td><td>91</td><td>8</td><td>38</td><td>56</td></tr><tr><td>student536</td><td>Languages</td><td>female</td><td>50</td><td>13</td><td>74</td><td>80</td></tr><tr><td>student537</td><td>Mathematics</td><td>male</td><td>54</td><td>75</td><td>74</td><td>10</td></tr><tr><td>student538</td><td>Languages</td><td>female</td><td>76</td><td>39</td><td>70</td><td>46</td></tr><tr><td>student539</td><td>Mathematics</td><td>male</td><td>84</td><td>72</td><td>39</td><td>40</td></tr><tr><td>student540</td><td>Languages</td><td>female</td><td>100</td><td>47</td><td>2</td><td>14</td></tr><tr><td>student541</td><td>Mathematics</td><td>male</td><td>42</td><td>61</td><td>1</td><td>1</td></tr><tr><td>student542</td><td>Languages</td><td>female</td><td>57</td><td>71</td><td>65</td><td>61</td></tr><tr><td>student543</td><td>Mathematics</td><td>male</td><td>78</td><td>5</td><td>41</td><td>34</td></tr><tr><td>student544</td><td>Languages</td><td>female</td><td>14</td><td>76</td><td>36</td><td>47</td></tr><tr><td>student545</td><td>Mathematics</td><td>male</td><td>15</td><td>19</td><td>63</td><td>96</td></tr><tr><td>student546</td><td>Languages</td><td>female</td><td>27</td><td>82</td><td>33</td><td>56</td></tr><tr><td>student547</td><td>Mathematics</td><td>male</td><td>70</td><td>23</td><td>96</td><td>90</td></tr><tr><td>student548</td><td>Languages</td><td>female</td><td>61</td><td>2</td><td>2</td><td>78</td></tr><tr><td>student549</td><td>Mathematics</td><td>male</td><td>22</td><td>37</td><td>64</td><td>36</td></tr><tr><td>student550</td><td>Languages</td><td>female</td><td>75</td><td>96</td><td>94</td><td>40</td></tr><tr><td>student551</td><td>Mathematics</td><td>male</td><td>43</td><td>8</td><td>29</td><td>21</td></tr><tr><td>student552</td><td>Languages</td><td>female</td><td>7</td><td>96</td><td>87</td><td>18</td></tr><tr><td>student553</td><td>Mathematics</td><td>male</td><td>65</td><td>76</td><td>52</td><td>44</td></tr><tr><td>student554</td><td>Languages</td><td>female</td><td>41</td><td>62</td><td>73</td><td>54</td></tr><tr><td>student555</td><td>Mathematics</td><td>male</td><td>25</td><td>98</td><td>21</td><td>40</td></tr><tr><td>student556</td><td>Languages</td><td>female</td><td>17</td><td>70</td><td>96</td><td>82</td></tr><tr><td>student557</td><td>Mathematics</td><td>male</td><td>43</td><td>91</td><td>27</td><td>43</td></tr><tr><td>student558</td><td>Languages</td><td>female</td><td>33</td><td>37</td><td>24</td><td>33</td></tr><tr><td>student559</td><td>Mathematics</td><td>male</td><td>87</td><td>87</td><td>10</td><td>31</td></tr><tr><td>student560</td><td>Languages</td><td>female</td><td>48</td><td>40</td><td>97</td><td>74</td></tr><tr><td>student561</td><td>Mathematics</td><td>male</td><td>63</td><td>75</td><td>91</td><td>55</td></tr><tr><td>student562</td><td>Languages</td><td>female</td><td>66</td><td>82</td><td>59</td><td>95</td></tr><tr><td>student563</td><td>Mathematics</td><td>male</td><td>21</td><td>95</td><td>58</td><td>38</td></tr><tr><td>student564</td><td>Languages</td><td>female</td><td>92</td><td>9</td><td>97</td><td>45</td></tr><tr><td>student565</td><td>Mathematics</td><td>male</td><td>59</td><td>7</td><td>94</td><td>20</td></tr><tr><td>student566</td><td>Languages</td><td>female</td><td>64</td><td>95</td><td>24</td><td>12</td></tr><tr><td>student567</td><td>Mathematics</td><td>male</td><td>70</td><td>46</td><td>36</td><td>74</td></tr><tr><td>student568</td><td>Languages</td><td>female</td><td>16</td><td>25</td><td>91</td><td>49</td></tr><tr><td>student569</td><td>Mathematics</td><td>male</td><td>73</td><td>33</td><td>24</td><td>88</td></tr><tr><td>student570</td><td>Languages</td><td>female</td><td>9</td><td>61</td><td>95</td><td>27</td></tr><tr><td>student571</td><td>Mathematics</td><td>male</td><td>18</td><td>12</td><td>76</td><td>46</td></tr><tr><td>student572</td><td>Languages</td><td>female</td><td>61</td><td>71</td><td>49</td><td>63</td></tr><tr><td>student573</td><td>Mathematics</td><td>male</td><td>46</td><td>32</td><td>85</td><td>17</td></tr><tr><td>student574</td><td>Languages</td><td>female</td><td>42</td><td>42</td><td>11</td><td>37</td></tr><tr><td>student575</td><td>Mathematics</td><td>male</td><td>49</td><td>76</td><td>41</td><td>20</td></tr><tr><td>student576</td><td>Languages</td><td>female</td><td>22</td><td>27</td><td>80</td><td>12</td></tr><tr><td>student577</td><td>Mathematics</td><td>male</td><td>76</td><td>34</td><td>18</td><td>66</td></tr><tr><td>student578</td><td>Languages</td><td>female</td><td>96</td><td>77</td><td>29</td><td>17</td></tr><tr><td>student579</td><td>Mathematics</td><td>male</td><td>62</td><td>51</td><td>67</td><td>72</td></tr><tr><td>student580</td><td>Languages</td><td>female</td><td>96</td><td>67</td><td>22</td><td>54</td></tr><tr><td>student581</td><td>Mathematics</td><td>male</td><td>77</td><td>11</td><td>23</td><td>88</td></tr><tr><td>student582</td><td>Languages</td><td>female</td><td>6</td><td>28</td><td>24</td><td>33</td></tr><tr><td>student583</td><td>Mathematics</td><td>male</td><td>39</td><td>23</td><td>12</td><td>100</td></tr><tr><td>student584</td><td>Languages</td><td>female</td><td>10</td><td>21</td><td>20</td><td>71</td></tr><tr><td>student585</td><td>Mathematics</td><td>male</td><td>11</td><td>27</td><td>7</td><td>100</td></tr><tr><td>student586</td><td>Languages</td><td>female</td><td>40</td><td>34</td><td>97</td><td>78</td></tr><tr><td>student587</td><td>Mathematics</td><td>male</td><td>2</td><td>51</td><td>83</td><td>19</td></tr><tr><td>student588</td><td>Languages</td><td>female</td><td>18</td><td>76</td><td>30</td><td>25</td></tr><tr><td>student589</td><td>Mathematics</td><td>male</td><td>24</td><td>57</td><td>46</td><td>81</td></tr><tr><td>student590</td><td>Languages</td><td>female</td><td>2</td><td>10</td><td>31</td><td>94</td></tr><tr><td>student591</td><td>Mathematics</td><td>male</td><td>91</td><td>84</td><td>75</td><td>13</td></tr><tr><td>student592</td><td>Languages</td><td>female</td><td>79</td><td>44</td><td>97</td><td>10</td></tr><tr><td>student593</td><td>Mathematics</td><td>male</td><td>42</td><td>60</td><td>67</td><td>30</td></tr><tr><td>student594</td><td>Languages</td><td>female</td><td>61</td><td>57</td><td>75</td><td>35</td></tr><tr><td>student595</td><td>Mathematics</td><td>male</td><td>42</td><td>46</td><td>81</td><td>71</td></tr><tr><td>student596</td><td>Languages</td><td>female</td><td>92</td><td>63</td><td>75</td><td>74</td></tr><tr><td>student597</td><td>Mathematics</td><td>male</td><td>86</td><td>37</td><td>40</td><td>51</td></tr><tr><td>student598</td><td>Languages</td><td>female</td><td>52</td><td>10</td><td>47</td><td>3</td></tr><tr><td>student599</td><td>Mathematics</td><td>male</td><td>100</td><td>28</td><td>14</td><td>76</td></tr><tr><td>student600</td><td>Languages</td><td>female</td><td>31</td><td>76</td><td>20</td><td>43</td></tr><tr><td>student601</td><td>Mathematics</td><td>male</td><td>40</td><td>27</td><td>6</td><td>6</td></tr><tr><td>student602</td><td>Languages</td><td>female</td><td>5</td><td>8</td><td>79</td><td>21</td></tr><tr><td>student603</td><td>Mathematics</td><td>male</td><td>7</td><td>54</td><td>6</td><td>91</td></tr><tr><td>student604</td><td>Languages</td><td>female</td><td>28</td><td>30</td><td>15</td><td>3</td></tr><tr><td>student605</td><td>Mathematics</td><td>male</td><td>38</td><td>93</td><td>98</td><td>92</td></tr><tr><td>student606</td><td>Languages</td><td>female</td><td>43</td><td>96</td><td>89</td><td>91</td></tr><tr><td>student607</td><td>Mathematics</td><td>male</td><td>43</td><td>49</td><td>14</td><td>83</td></tr><tr><td>student608</td><td>Languages</td><td>female</td><td>50</td><td>61</td><td>72</td><td>98</td></tr><tr><td>student609</td><td>Mathematics</td><td>male</td><td>4</td><td>49</td><td>99</td><td>83</td></tr><tr><td>student610</td><td>Languages</td><td>female</td><td>5</td><td>36</td><td>73</td><td>82</td></tr><tr><td>student611</td><td>Mathematics</td><td>male</td><td>40</td><td>84</td><td>99</td><td>54</td></tr><tr><td>student612</td><td>Languages</td><td>female</td><td>29</td><td>96</td><td>65</td><td>69</td></tr><tr><td>student613</td><td>Mathematics</td><td>male</td><td>12</td><td>76</td><td>5</td><td>99</td></tr><tr><td>student614</td><td>Languages</td><td>female</td><td>47</td><td>83</td><td>49</td><td>4</td></tr><tr><td>student615</td><td>Mathematics</td><td>male</td><td>37</td><td>27</td><td>22</td><td>4</td></tr><tr><td>student616</td><td>Languages</td><td>female</td><td>94</td><td>39</td><td>49</td><td>24</td></tr><tr><td>student617</td><td>Mathematics</td><td>male</td><td>0</td><td>75</td><td>21</td><td>41</td></tr><tr><td>student618</td><td>Languages</td><td>female</td><td>59</td><td>36</td><td>4</td><td>18</td></tr><tr><td>student619</td><td>Mathematics</td><td>male</td><td>22</td><td>66</td><td>13</td><td>3</td></tr><tr><td>student620</td><td>Languages</td><td>female</td><td>43</td><td>87</td><td>4</td><td>48</td></tr><tr><td>student621</td><td>Mathematics</td><td>male</td><td>100</td><td>15</td><td>51</td><td>52</td></tr><tr><td>student622</td><td>Languages</td><td>female</td><td>63</td><td>71</td><td>99</td><td>17</td></tr><tr><td>student623</td><td>Mathematics</td><td>male</td><td>14</td><td>34</td><td>44</td><td>100</td></tr><tr><td>student624</td><td>Languages</td><td>female</td><td>23</td><td>8</td><td>57</td><td>27</td></tr><tr><td>student625</td><td>Mathematics</td><td>male</td><td>23</td><td>14</td><td>32</td><td>40</td></tr><tr><td>student626</td><td>Languages</td><td>female</td><td>34</td><td>49</td><td>72</td><td>54</td></tr><tr><td>student627</td><td>Mathematics</td><td>male</td><td>21</td><td>16</td><td>81</td><td>26</td></tr><tr><td>student628</td><td>Languages</td><td>female</td><td>54</td><td>69</td><td>34</td><td>34</td></tr><tr><td>student629</td><td>Mathematics</td><td>male</td><td>72</td><td>11</td><td>63</td><td>31</td></tr><tr><td>student630</td><td>Languages</td><td>female</td><td>87</td><td>98</td><td>9</td><td>47</td></tr><tr><td>student631</td><td>Mathematics</td><td>male</td><td>43</td><td>52</td><td>53</td><td>58</td></tr><tr><td>student632</td><td>Languages</td><td>female</td><td>50</td><td>14</td><td>4</td><td>20</td></tr><tr><td>student633</td><td>Mathematics</td><td>male</td><td>89</td><td>83</td><td>67</td><td>87</td></tr><tr><td>student634</td><td>Languages</td><td>female</td><td>0</td><td>79</td><td>9</td><td>16</td></tr><tr><td>student635</td><td>Mathematics</td><td>male</td><td>59</td><td>17</td><td>84</td><td>58</td></tr><tr><td>student636</td><td>Languages</td><td>female</td><td>94</td><td>95</td><td>36</td><td>60</td></tr><tr><td>student637</td><td>Mathematics</td><td>male</td><td>39</td><td>42</td><td>63</td><td>46</td></tr><tr><td>student638</td><td>Languages</td><td>female</td><td>0</td><td>19</td><td>6</td><td>10</td></tr><tr><td>student639</td><td>Mathematics</td><td>male</td><td>50</td><td>16</td><td>41</td><td>71</td></tr><tr><td>student640</td><td>Languages</td><td>female</td><td>8</td><td>60</td><td>46</td><td>13</td></tr><tr><td>student641</td><td>Mathematics</td><td>male</td><td>45</td><td>85</td><td>59</td><td>36</td></tr><tr><td>student642</td><td>Languages</td><td>female</td><td>83</td><td>35</td><td>0</td><td>57</td></tr><tr><td>student643</td><td>Mathematics</td><td>male</td><td>8</td><td>30</td><td>60</td><td>14</td></tr><tr><td>student644</td><td>Languages</td><td>female</td><td>76</td><td>80</td><td>73</td><td>38</td></tr><tr><td>student645</td><td>Mathematics</td><td>male</td><td>26</td><td>14</td><td>58</td><td>2</td></tr><tr><td>student646</td><td>Languages</td><td>female</td><td>93</td><td>16</td><td>42</td><td>2</td></tr><tr><td>student647</td><td>Mathematics</td><td>male</td><td>85</td><td>94</td><td>76</td><td>16</td></tr><tr><td>student648</td><td>Languages</td><td>female</td><td>57</td><td>45</td><td>32</td><td>16</td></tr><tr><td>student649</td><td>Mathematics</td><td>male</td><td>16</td><td>16</td><td>90</td><td>13</td></tr><tr><td>student650</td><td>Languages</td><td>female</td><td>43</td><td>3</td><td>18</td><td>87</td></tr><tr><td>student651</td><td>Mathematics</td><td>male</td><td>16</td><td>24</td><td>32</td><td>44</td></tr><tr><td>student652</td><td>Languages</td><td>female</td><td>59</td><td>98</td><td>3</td><td>34</td></tr><tr><td>student653</td><td>Mathematics</td><td>male</td><td>73</td><td>18</td><td>47</td><td>83</td></tr><tr><td>student654</td><td>Languages</td><td>female</td><td>99</td><td>25</td><td>100</td><td>93</td></tr><tr><td>student655</td><td>Mathematics</td><td>male</td><td>0</td><td>73</td><td>97</td><td>84</td></tr><tr><td>student656</td><td>Languages</td><td>female</td><td>0</td><td>28</td><td>94</td><td>75</td></tr><tr><td>student657</td><td>Mathematics</td><td>male</td><td>65</td><td>90</td><td>58</td><td>63</td></tr><tr><td>student658</td><td>Languages</td><td>female</td><td>84</td><td>35</td><td>86</td><td>41</td></tr><tr><td>student659</td><td>Mathematics</td><td>male</td><td>45</td><td>39</td><td>59</td><td>9</td></tr><tr><td>student660</td><td>Languages</td><td>female</td><td>32</td><td>10</td><td>31</td><td>62</td></tr><tr><td>student661</td><td>Mathematics</td><td>male</td><td>61</td><td>28</td><td>54</td><td>61</td></tr><tr><td>student662</td><td>Languages</td><td>female</td><td>70</td><td>96</td><td>14</td><td>54</td></tr><tr><td>student663</td><td>Mathematics</td><td>male</td><td>63</td><td>92</td><td>29</td><td>8</td></tr><tr><td>student664</td><td>Languages</td><td>female</td><td>41</td><td>10</td><td>46</td><td>23</td></tr><tr><td>student665</td><td>Mathematics</td><td>male</td><td>81</td><td>91</td><td>80</td><td>21</td></tr><tr><td>student666</td><td>Languages</td><td>female</td><td>79</td><td>71</td><td>65</td><td>68</td></tr><tr><td>student667</td><td>Mathematics</td><td>male</td><td>47</td><td>69</td><td>18</td><td>90</td></tr><tr><td>student668</td><td>Languages</td><td>female</td><td>26</td><td>16</td><td>70</td><td>0</td></tr><tr><td>student669</td><td>Mathematics</td><td>male</td><td>66</td><td>10</td><td>93</td><td>35</td></tr><tr><td>student670</td><td>Languages</td><td>female</td><td>66</td><td>68</td><td>27</td><td>13</td></tr><tr><td>student671</td><td>Mathematics</td><td>male</td><td>86</td><td>79</td><td>26</td><td>45</td></tr><tr><td>student672</td><td>Languages</td><td>female</td><td>50</td><td>53</td><td>25</td><td>74</td></tr><tr><td>student673</td><td>Mathematics</td><td>male</td><td>97</td><td>53</td><td>9</td><td>14</td></tr><tr><td>student674</td><td>Languages</td><td>female</td><td>28</td><td>79</td><td>69</td><td>42</td></tr><tr><td>student675</td><td>Mathematics</td><td>male</td><td>60</td><td>72</td><td>5</td><td>9</td></tr><tr><td>student676</td><td>Languages</td><td>female</td><td>53</td><td>21</td><td>39</td><td>43</td></tr><tr><td>student677</td><td>Mathematics</td><td>male</td><td>37</td><td>65</td><td>45</td><td>91</td></tr><tr><td>student678</td><td>Languages</td><td>female</td><td>76</td><td>80</td><td>60</td><td>27</td></tr><tr><td>student679</td><td>Mathematics</td><td>male</td><td>85</td><td>27</td><td>34</td><td>55</td></tr><tr><td>student680</td><td>Languages</td><td>female</td><td>66</td><td>11</td><td>41</td><td>17</td></tr><tr><td>student681</td><td>Mathematics</td><td>male</td><td>27</td><td>61</td><td>89</td><td>82</td></tr><tr><td>student682</td><td>Languages</td><td>female</td><td>40</td><td>26</td><td>1</td><td>3</td></tr><tr><td>student683</td><td>Mathematics</td><td>male</td><td>25</td><td>1</td><td>66</td><td>95</td></tr><tr><td>student684</td><td>Languages</td><td>female</td><td>63</td><td>44</td><td>85</td><td>63</td></tr><tr><td>student685</td><td>Mathematics</td><td>male</td><td>97</td><td>95</td><td>78</td><td>83</td></tr><tr><td>student686</td><td>Languages</td><td>female</td><td>51</td><td>2</td><td>13</td><td>87</td></tr><tr><td>student687</td><td>Mathematics</td><td>male</td><td>63</td><td>92</td><td>87</td><td>23</td></tr><tr><td>student688</td><td>Languages</td><td>female</td><td>22</td><td>96</td><td>59</td><td>59</td></tr><tr><td>student689</td><td>Mathematics</td><td>male</td><td>33</td><td>80</td><td>15</td><td>23</td></tr><tr><td>student690</td><td>Languages</td><td>female</td><td>34</td><td>75</td><td>19</td><td>24</td></tr><tr><td>student691</td><td>Mathematics</td><td>male</td><td>36</td><td>68</td><td>48</td><td>54</td></tr><tr><td>student692</td><td>Languages</td><td>female</td><td>32</td><td>36</td><td>20</td><td>12</td></tr><tr><td>student693</td><td>Mathematics</td><td>male</td><td>68</td><td>91</td><td>74</td><td>50</td></tr><tr><td>student694</td><td>Languages</td><td>female</td><td>87</td><td>91</td><td>96</td><td>37</td></tr><tr><td>student695</td><td>Mathematics</td><td>male</td><td>23</td><td>9</td><td>14</td><td>4</td></tr><tr><td>student696</td><td>Languages</td><td>female</td><td>94</td><td>62</td><td>9</td><td>77</td></tr><tr><td>student697</td><td>Mathematics</td><td>male</td><td>14</td><td>7</td><td>45</td><td>75</td></tr><tr><td>student698</td><td>Languages</td><td>female</td><td>73</td><td>92</td><td>19</td><td>90</td></tr><tr><td>student699</td><td>Mathematics</td><td>male</td><td>8</td><td>20</td><td>79</td><td>78</td></tr><tr><td>student700</td><td>Languages</td><td>female</td><td>76</td><td>35</td><td>100</td><td>39</td></tr><tr><td>student701</td><td>Mathematics</td><td>male</td><td>27</td><td>51</td><td>89</td><td>49</td></tr><tr><td>student702</td><td>Languages</td><td>female</td><td>0</td><td>64</td><td>72</td><td>37</td></tr><tr><td>student703</td><td>Mathematics</td><td>male</td><td>93</td><td>46</td><td>94</td><td>87</td></tr><tr><td>student704</td><td>Languages</td><td>female</td><td>69</td><td>22</td><td>17</td><td>2</td></tr><tr><td>student705</td><td>Mathematics</td><td>male</td><td>17</td><td>52</td><td>11</td><td>3</td></tr><tr><td>student706</td><td>Languages</td><td>female</td><td>13</td><td>2</td><td>52</td><td>19</td></tr><tr><td>student707</td><td>Mathematics</td><td>male</td><td>75</td><td>61</td><td>72</td><td>73</td></tr><tr><td>student708</td><td>Languages</td><td>female</td><td>84</td><td>37</td><td>7</td><td>36</td></tr><tr><td>student709</td><td>Mathematics</td><td>male</td><td>81</td><td>19</td><td>45</td><td>14</td></tr><tr><td>student710</td><td>Languages</td><td>female</td><td>62</td><td>17</td><td>39</td><td>27</td></tr><tr><td>student711</td><td>Mathematics</td><td>male</td><td>88</td><td>69</td><td>6</td><td>81</td></tr><tr><td>student712</td><td>Languages</td><td>female</td><td>53</td><td>82</td><td>59</td><td>29</td></tr><tr><td>student713</td><td>Mathematics</td><td>male</td><td>83</td><td>34</td><td>71</td><td>34</td></tr><tr><td>student714</td><td>Languages</td><td>female</td><td>95</td><td>52</td><td>61</td><td>4</td></tr><tr><td>student715</td><td>Mathematics</td><td>male</td><td>6</td><td>71</td><td>53</td><td>13</td></tr><tr><td>student716</td><td>Languages</td><td>female</td><td>82</td><td>97</td><td>82</td><td>5</td></tr><tr><td>student717</td><td>Mathematics</td><td>male</td><td>65</td><td>50</td><td>31</td><td>46</td></tr><tr><td>student718</td><td>Languages</td><td>female</td><td>27</td><td>46</td><td>25</td><td>37</td></tr><tr><td>student719</td><td>Mathematics</td><td>male</td><td>98</td><td>42</td><td>35</td><td>44</td></tr><tr><td>student720</td><td>Languages</td><td>female</td><td>90</td><td>1</td><td>44</td><td>44</td></tr><tr><td>student721</td><td>Mathematics</td><td>male</td><td>3</td><td>16</td><td>82</td><td>93</td></tr><tr><td>student722</td><td>Languages</td><td>female</td><td>34</td><td>3</td><td>43</td><td>70</td></tr><tr><td>student723</td><td>Mathematics</td><td>male</td><td>59</td><td>77</td><td>14</td><td>21</td></tr><tr><td>student724</td><td>Languages</td><td>female</td><td>16</td><td>53</td><td>57</td><td>59</td></tr><tr><td>student725</td><td>Mathematics</td><td>male</td><td>79</td><td>1</td><td>44</td><td>16</td></tr><tr><td>student726</td><td>Languages</td><td>female</td><td>10</td><td>8</td><td>19</td><td>9</td></tr><tr><td>student727</td><td>Mathematics</td><td>male</td><td>89</td><td>48</td><td>79</td><td>16</td></tr><tr><td>student728</td><td>Languages</td><td>female</td><td>8</td><td>87</td><td>23</td><td>87</td></tr><tr><td>student729</td><td>Mathematics</td><td>male</td><td>17</td><td>53</td><td>95</td><td>84</td></tr><tr><td>student730</td><td>Languages</td><td>female</td><td>65</td><td>52</td><td>39</td><td>61</td></tr><tr><td>student731</td><td>Mathematics</td><td>male</td><td>44</td><td>30</td><td>96</td><td>72</td></tr><tr><td>student732</td><td>Languages</td><td>female</td><td>70</td><td>79</td><td>32</td><td>33</td></tr><tr><td>student733</td><td>Mathematics</td><td>male</td><td>30</td><td>47</td><td>46</td><td>11</td></tr><tr><td>student734</td><td>Languages</td><td>female</td><td>76</td><td>100</td><td>16</td><td>49</td></tr><tr><td>student735</td><td>Mathematics</td><td>male</td><td>39</td><td>36</td><td>90</td><td>89</td></tr><tr><td>student736</td><td>Languages</td><td>female</td><td>1</td><td>94</td><td>19</td><td>29</td></tr><tr><td>student737</td><td>Mathematics</td><td>male</td><td>23</td><td>73</td><td>78</td><td>87</td></tr><tr><td>student738</td><td>Languages</td><td>female</td><td>87</td><td>71</td><td>44</td><td>64</td></tr><tr><td>student739</td><td>Mathematics</td><td>male</td><td>22</td><td>19</td><td>82</td><td>20</td></tr><tr><td>student740</td><td>Languages</td><td>female</td><td>94</td><td>52</td><td>67</td><td>39</td></tr><tr><td>student741</td><td>Mathematics</td><td>male</td><td>14</td><td>17</td><td>51</td><td>87</td></tr><tr><td>student742</td><td>Languages</td><td>female</td><td>56</td><td>63</td><td>98</td><td>3</td></tr><tr><td>student743</td><td>Mathematics</td><td>male</td><td>99</td><td>92</td><td>46</td><td>98</td></tr><tr><td>student744</td><td>Languages</td><td>female</td><td>19</td><td>76</td><td>83</td><td>88</td></tr><tr><td>student745</td><td>Mathematics</td><td>male</td><td>15</td><td>77</td><td>68</td><td>81</td></tr><tr><td>student746</td><td>Languages</td><td>female</td><td>48</td><td>81</td><td>48</td><td>38</td></tr><tr><td>student747</td><td>Mathematics</td><td>male</td><td>29</td><td>1</td><td>38</td><td>61</td></tr><tr><td>student748</td><td>Languages</td><td>female</td><td>71</td><td>63</td><td>0</td><td>30</td></tr><tr><td>student749</td><td>Mathematics</td><td>male</td><td>19</td><td>68</td><td>30</td><td>53</td></tr><tr><td>student750</td><td>Languages</td><td>female</td><td>91</td><td>18</td><td>27</td><td>62</td></tr><tr><td>student751</td><td>Mathematics</td><td>male</td><td>73</td><td>33</td><td>38</td><td>36</td></tr><tr><td>student752</td><td>Languages</td><td>female</td><td>99</td><td>38</td><td>75</td><td>50</td></tr><tr><td>student753</td><td>Mathematics</td><td>male</td><td>55</td><td>71</td><td>34</td><td>90</td></tr><tr><td>student754</td><td>Languages</td><td>female</td><td>52</td><td>40</td><td>98</td><td>83</td></tr><tr><td>student755</td><td>Mathematics</td><td>male</td><td>14</td><td>63</td><td>61</td><td>1</td></tr><tr><td>student756</td><td>Languages</td><td>female</td><td>1</td><td>31</td><td>94</td><td>96</td></tr><tr><td>student757</td><td>Mathematics</td><td>male</td><td>49</td><td>66</td><td>55</td><td>92</td></tr><tr><td>student758</td><td>Languages</td><td>female</td><td>0</td><td>19</td><td>80</td><td>82</td></tr><tr><td>student759</td><td>Mathematics</td><td>male</td><td>26</td><td>35</td><td>87</td><td>3</td></tr><tr><td>student760</td><td>Languages</td><td>female</td><td>8</td><td>28</td><td>76</td><td>39</td></tr><tr><td>student761</td><td>Mathematics</td><td>male</td><td>52</td><td>11</td><td>83</td><td>57</td></tr><tr><td>student762</td><td>Languages</td><td>female</td><td>83</td><td>68</td><td>84</td><td>25</td></tr><tr><td>student763</td><td>Mathematics</td><td>male</td><td>17</td><td>2</td><td>56</td><td>70</td></tr><tr><td>student764</td><td>Languages</td><td>female</td><td>17</td><td>58</td><td>0</td><td>84</td></tr><tr><td>student765</td><td>Mathematics</td><td>male</td><td>75</td><td>6</td><td>47</td><td>85</td></tr><tr><td>student766</td><td>Languages</td><td>female</td><td>76</td><td>32</td><td>93</td><td>39</td></tr><tr><td>student767</td><td>Mathematics</td><td>male</td><td>20</td><td>75</td><td>84</td><td>65</td></tr><tr><td>student768</td><td>Languages</td><td>female</td><td>25</td><td>47</td><td>12</td><td>89</td></tr><tr><td>student769</td><td>Mathematics</td><td>male</td><td>86</td><td>94</td><td>79</td><td>45</td></tr><tr><td>student770</td><td>Languages</td><td>female</td><td>65</td><td>81</td><td>55</td><td>35</td></tr><tr><td>student771</td><td>Mathematics</td><td>male</td><td>62</td><td>41</td><td>41</td><td>43</td></tr><tr><td>student772</td><td>Languages</td><td>female</td><td>14</td><td>4</td><td>62</td><td>43</td></tr><tr><td>student773</td><td>Mathematics</td><td>male</td><td>17</td><td>55</td><td>72</td><td>78</td></tr><tr><td>student774</td><td>Languages</td><td>female</td><td>95</td><td>46</td><td>35</td><td>6</td></tr><tr><td>student775</td><td>Mathematics</td><td>male</td><td>72</td><td>0</td><td>56</td><td>48</td></tr><tr><td>student776</td><td>Languages</td><td>female</td><td>30</td><td>88</td><td>19</td><td>56</td></tr><tr><td>student777</td><td>Mathematics</td><td>male</td><td>42</td><td>44</td><td>88</td><td>56</td></tr><tr><td>student778</td><td>Languages</td><td>female</td><td>42</td><td>69</td><td>56</td><td>63</td></tr><tr><td>student779</td><td>Mathematics</td><td>male</td><td>78</td><td>57</td><td>78</td><td>3</td></tr><tr><td>student780</td><td>Languages</td><td>female</td><td>15</td><td>86</td><td>24</td><td>98</td></tr><tr><td>student781</td><td>Mathematics</td><td>male</td><td>46</td><td>8</td><td>43</td><td>69</td></tr><tr><td>student782</td><td>Languages</td><td>female</td><td>67</td><td>98</td><td>15</td><td>52</td></tr><tr><td>student783</td><td>Mathematics</td><td>male</td><td>33</td><td>32</td><td>63</td><td>57</td></tr><tr><td>student784</td><td>Languages</td><td>female</td><td>35</td><td>95</td><td>16</td><td>53</td></tr><tr><td>student785</td><td>Mathematics</td><td>male</td><td>78</td><td>54</td><td>54</td><td>82</td></tr><tr><td>student786</td><td>Languages</td><td>female</td><td>81</td><td>85</td><td>91</td><td>4</td></tr><tr><td>student787</td><td>Mathematics</td><td>male</td><td>42</td><td>41</td><td>23</td><td>14</td></tr><tr><td>student788</td><td>Languages</td><td>female</td><td>59</td><td>100</td><td>86</td><td>36</td></tr><tr><td>student789</td><td>Mathematics</td><td>male</td><td>1</td><td>92</td><td>60</td><td>12</td></tr><tr><td>student790</td><td>Languages</td><td>female</td><td>100</td><td>34</td><td>5</td><td>70</td></tr><tr><td>student791</td><td>Mathematics</td><td>male</td><td>3</td><td>81</td><td>2</td><td>17</td></tr><tr><td>student792</td><td>Languages</td><td>female</td><td>31</td><td>55</td><td>19</td><td>3</td></tr><tr><td>student793</td><td>Mathematics</td><td>male</td><td>11</td><td>33</td><td>98</td><td>77</td></tr><tr><td>student794</td><td>Languages</td><td>female</td><td>4</td><td>61</td><td>7</td><td>86</td></tr><tr><td>student795</td><td>Mathematics</td><td>male</td><td>57</td><td>86</td><td>7</td><td>27</td></tr><tr><td>student796</td><td>Languages</td><td>female</td><td>5</td><td>74</td><td>62</td><td>36</td></tr><tr><td>student797</td><td>Mathematics</td><td>male</td><td>57</td><td>67</td><td>66</td><td>61</td></tr><tr><td>student798</td><td>Languages</td><td>female</td><td>93</td><td>88</td><td>87</td><td>25</td></tr><tr><td>student799</td><td>Mathematics</td><td>male</td><td>59</td><td>96</td><td>64</td><td>41</td></tr><tr><td>student800</td><td>Languages</td><td>female</td><td>62</td><td>7</td><td>69</td><td>23</td></tr><tr><td>student801</td><td>Mathematics</td><td>male</td><td>35</td><td>83</td><td>32</td><td>55</td></tr><tr><td>student802</td><td>Languages</td><td>female</td><td>42</td><td>58</td><td>15</td><td>83</td></tr><tr><td>student803</td><td>Mathematics</td><td>male</td><td>41</td><td>90</td><td>40</td><td>12</td></tr><tr><td>student804</td><td>Languages</td><td>female</td><td>81</td><td>43</td><td>83</td><td>7</td></tr><tr><td>student805</td><td>Mathematics</td><td>male</td><td>87</td><td>77</td><td>33</td><td>20</td></tr><tr><td>student806</td><td>Languages</td><td>female</td><td>53</td><td>87</td><td>30</td><td>37</td></tr><tr><td>student807</td><td>Mathematics</td><td>male</td><td>13</td><td>35</td><td>85</td><td>16</td></tr><tr><td>student808</td><td>Languages</td><td>female</td><td>20</td><td>82</td><td>90</td><td>34</td></tr><tr><td>student809</td><td>Mathematics</td><td>male</td><td>58</td><td>2</td><td>16</td><td>14</td></tr><tr><td>student810</td><td>Languages</td><td>female</td><td>14</td><td>28</td><td>23</td><td>56</td></tr><tr><td>student811</td><td>Mathematics</td><td>male</td><td>49</td><td>97</td><td>36</td><td>8</td></tr><tr><td>student812</td><td>Languages</td><td>female</td><td>31</td><td>46</td><td>11</td><td>63</td></tr><tr><td>student813</td><td>Mathematics</td><td>male</td><td>74</td><td>9</td><td>76</td><td>43</td></tr><tr><td>student814</td><td>Languages</td><td>female</td><td>42</td><td>83</td><td>95</td><td>75</td></tr><tr><td>student815</td><td>Mathematics</td><td>male</td><td>2</td><td>65</td><td>45</td><td>29</td></tr><tr><td>student816</td><td>Languages</td><td>female</td><td>79</td><td>59</td><td>69</td><td>88</td></tr><tr><td>student817</td><td>Mathematics</td><td>male</td><td>68</td><td>18</td><td>26</td><td>84</td></tr><tr><td>student818</td><td>Languages</td><td>female</td><td>39</td><td>13</td><td>99</td><td>15</td></tr><tr><td>student819</td><td>Mathematics</td><td>male</td><td>22</td><td>48</td><td>71</td><td>6</td></tr><tr><td>student820</td><td>Languages</td><td>female</td><td>12</td><td>53</td><td>88</td><td>11</td></tr><tr><td>student821</td><td>Mathematics</td><td>male</td><td>33</td><td>90</td><td>80</td><td>29</td></tr><tr><td>student822</td><td>Languages</td><td>female</td><td>37</td><td>9</td><td>54</td><td>86</td></tr><tr><td>student823</td><td>Mathematics</td><td>male</td><td>91</td><td>78</td><td>85</td><td>1</td></tr><tr><td>student824</td><td>Languages</td><td>female</td><td>31</td><td>58</td><td>67</td><td>31</td></tr><tr><td>student825</td><td>Mathematics</td><td>male</td><td>22</td><td>30</td><td>50</td><td>98</td></tr><tr><td>student826</td><td>Languages</td><td>female</td><td>55</td><td>58</td><td>56</td><td>10</td></tr><tr><td>student827</td><td>Mathematics</td><td>male</td><td>56</td><td>76</td><td>57</td><td>53</td></tr><tr><td>student828</td><td>Languages</td><td>female</td><td>1</td><td>12</td><td>98</td><td>81</td></tr><tr><td>student829</td><td>Mathematics</td><td>male</td><td>67</td><td>92</td><td>66</td><td>71</td></tr><tr><td>student830</td><td>Languages</td><td>female</td><td>30</td><td>61</td><td>44</td><td>49</td></tr><tr><td>student831</td><td>Mathematics</td><td>male</td><td>0</td><td>41</td><td>44</td><td>61</td></tr><tr><td>student832</td><td>Languages</td><td>female</td><td>72</td><td>52</td><td>45</td><td>85</td></tr><tr><td>student833</td><td>Mathematics</td><td>male</td><td>60</td><td>99</td><td>12</td><td>94</td></tr><tr><td>student834</td><td>Languages</td><td>female</td><td>83</td><td>58</td><td>75</td><td>42</td></tr><tr><td>student835</td><td>Mathematics</td><td>male</td><td>95</td><td>0</td><td>53</td><td>77</td></tr><tr><td>student836</td><td>Languages</td><td>female</td><td>33</td><td>28</td><td>70</td><td>62</td></tr><tr><td>student837</td><td>Mathematics</td><td>male</td><td>39</td><td>82</td><td>75</td><td>5</td></tr><tr><td>student838</td><td>Languages</td><td>female</td><td>41</td><td>100</td><td>45</td><td>47</td></tr><tr><td>student839</td><td>Mathematics</td><td>male</td><td>81</td><td>69</td><td>27</td><td>29</td></tr><tr><td>student840</td><td>Languages</td><td>female</td><td>90</td><td>1</td><td>26</td><td>49</td></tr><tr><td>student841</td><td>Mathematics</td><td>male</td><td>45</td><td>38</td><td>20</td><td>34</td></tr><tr><td>student842</td><td>Languages</td><td>female</td><td>3</td><td>25</td><td>31</td><td>1</td></tr><tr><td>student843</td><td>Mathematics</td><td>male</td><td>55</td><td>77</td><td>86</td><td>49</td></tr><tr><td>student844</td><td>Languages</td><td>female</td><td>61</td><td>60</td><td>91</td><td>76</td></tr><tr><td>student845</td><td>Mathematics</td><td>male</td><td>80</td><td>85</td><td>74</td><td>9</td></tr><tr><td>student846</td><td>Languages</td><td>female</td><td>63</td><td>89</td><td>73</td><td>71</td></tr><tr><td>student847</td><td>Mathematics</td><td>male</td><td>79</td><td>15</td><td>97</td><td>42</td></tr><tr><td>student848</td><td>Languages</td><td>female</td><td>99</td><td>18</td><td>73</td><td>43</td></tr><tr><td>student849</td><td>Mathematics</td><td>male</td><td>30</td><td>52</td><td>38</td><td>56</td></tr><tr><td>student850</td><td>Languages</td><td>female</td><td>65</td><td>86</td><td>67</td><td>34</td></tr><tr><td>student851</td><td>Mathematics</td><td>male</td><td>73</td><td>43</td><td>6</td><td>55</td></tr><tr><td>student852</td><td>Languages</td><td>female</td><td>42</td><td>43</td><td>51</td><td>73</td></tr><tr><td>student853</td><td>Mathematics</td><td>male</td><td>8</td><td>70</td><td>98</td><td>0</td></tr><tr><td>student854</td><td>Languages</td><td>female</td><td>29</td><td>41</td><td>12</td><td>45</td></tr><tr><td>student855</td><td>Mathematics</td><td>male</td><td>57</td><td>3</td><td>90</td><td>90</td></tr><tr><td>student856</td><td>Languages</td><td>female</td><td>80</td><td>52</td><td>96</td><td>54</td></tr><tr><td>student857</td><td>Mathematics</td><td>male</td><td>43</td><td>83</td><td>82</td><td>46</td></tr><tr><td>student858</td><td>Languages</td><td>female</td><td>7</td><td>91</td><td>71</td><td>31</td></tr><tr><td>student859</td><td>Mathematics</td><td>male</td><td>68</td><td>13</td><td>70</td><td>7</td></tr><tr><td>student860</td><td>Languages</td><td>female</td><td>51</td><td>44</td><td>15</td><td>52</td></tr><tr><td>student861</td><td>Mathematics</td><td>male</td><td>91</td><td>70</td><td>1</td><td>78</td></tr><tr><td>student862</td><td>Languages</td><td>female</td><td>4</td><td>11</td><td>65</td><td>78</td></tr><tr><td>student863</td><td>Mathematics</td><td>male</td><td>20</td><td>63</td><td>55</td><td>85</td></tr><tr><td>student864</td><td>Languages</td><td>female</td><td>59</td><td>3</td><td>87</td><td>26</td></tr><tr><td>student865</td><td>Mathematics</td><td>male</td><td>4</td><td>89</td><td>44</td><td>32</td></tr><tr><td>student866</td><td>Languages</td><td>female</td><td>26</td><td>67</td><td>98</td><td>39</td></tr><tr><td>student867</td><td>Mathematics</td><td>male</td><td>48</td><td>79</td><td>38</td><td>66</td></tr><tr><td>student868</td><td>Languages</td><td>female</td><td>16</td><td>32</td><td>15</td><td>3</td></tr><tr><td>student869</td><td>Mathematics</td><td>male</td><td>13</td><td>20</td><td>50</td><td>85</td></tr><tr><td>student870</td><td>Languages</td><td>female</td><td>4</td><td>92</td><td>20</td><td>39</td></tr><tr><td>student871</td><td>Mathematics</td><td>male</td><td>82</td><td>6</td><td>23</td><td>53</td></tr><tr><td>student872</td><td>Languages</td><td>female</td><td>6</td><td>60</td><td>74</td><td>64</td></tr><tr><td>student873</td><td>Mathematics</td><td>male</td><td>66</td><td>48</td><td>39</td><td>14</td></tr><tr><td>student874</td><td>Languages</td><td>female</td><td>43</td><td>83</td><td>3</td><td>100</td></tr><tr><td>student875</td><td>Mathematics</td><td>male</td><td>21</td><td>49</td><td>9</td><td>0</td></tr><tr><td>student876</td><td>Languages</td><td>female</td><td>79</td><td>80</td><td>71</td><td>80</td></tr><tr><td>student877</td><td>Mathematics</td><td>male</td><td>84</td><td>25</td><td>26</td><td>88</td></tr><tr><td>student878</td><td>Languages</td><td>female</td><td>38</td><td>46</td><td>66</td><td>60</td></tr><tr><td>student879</td><td>Mathematics</td><td>male</td><td>35</td><td>27</td><td>98</td><td>51</td></tr><tr><td>student880</td><td>Languages</td><td>female</td><td>57</td><td>59</td><td>2</td><td>67</td></tr><tr><td>student881</td><td>Mathematics</td><td>male</td><td>76</td><td>87</td><td>78</td><td>8</td></tr><tr><td>student882</td><td>Languages</td><td>female</td><td>21</td><td>40</td><td>8</td><td>17</td></tr><tr><td>student883</td><td>Mathematics</td><td>male</td><td>50</td><td>4</td><td>68</td><td>66</td></tr><tr><td>student884</td><td>Languages</td><td>female</td><td>83</td><td>86</td><td>30</td><td>92</td></tr><tr><td>student885</td><td>Mathematics</td><td>male</td><td>63</td><td>46</td><td>66</td><td>94</td></tr><tr><td>student886</td><td>Languages</td><td>female</td><td>76</td><td>71</td><td>2</td><td>62</td></tr><tr><td>student887</td><td>Mathematics</td><td>male</td><td>74</td><td>18</td><td>68</td><td>6</td></tr><tr><td>student888</td><td>Languages</td><td>female</td><td>65</td><td>77</td><td>44</td><td>88</td></tr><tr><td>student889</td><td>Mathematics</td><td>male</td><td>67</td><td>32</td><td>61</td><td>19</td></tr><tr><td>student890</td><td>Languages</td><td>female</td><td>85</td><td>96</td><td>85</td><td>41</td></tr><tr><td>student891</td><td>Mathematics</td><td>male</td><td>14</td><td>87</td><td>70</td><td>5</td></tr><tr><td>student892</td><td>Languages</td><td>female</td><td>81</td><td>28</td><td>45</td><td>28</td></tr><tr><td>student893</td><td>Mathematics</td><td>male</td><td>9</td><td>19</td><td>18</td><td>83</td></tr><tr><td>student894</td><td>Languages</td><td>female</td><td>40</td><td>70</td><td>2</td><td>4</td></tr><tr><td>student895</td><td>Mathematics</td><td>male</td><td>18</td><td>19</td><td>51</td><td>89</td></tr><tr><td>student896</td><td>Languages</td><td>female</td><td>70</td><td>35</td><td>25</td><td>12</td></tr><tr><td>student897</td><td>Mathematics</td><td>male</td><td>72</td><td>90</td><td>7</td><td>41</td></tr><tr><td>student898</td><td>Languages</td><td>female</td><td>84</td><td>1</td><td>71</td><td>86</td></tr><tr><td>student899</td><td>Mathematics</td><td>male</td><td>14</td><td>2</td><td>38</td><td>86</td></tr><tr><td>student900</td><td>Languages</td><td>female</td><td>78</td><td>37</td><td>60</td><td>1</td></tr><tr><td>student901</td><td>Mathematics</td><td>male</td><td>66</td><td>95</td><td>31</td><td>68</td></tr><tr><td>student902</td><td>Languages</td><td>female</td><td>23</td><td>60</td><td>80</td><td>65</td></tr><tr><td>student903</td><td>Mathematics</td><td>male</td><td>76</td><td>89</td><td>63</td><td>96</td></tr><tr><td>student904</td><td>Languages</td><td>female</td><td>3</td><td>46</td><td>90</td><td>70</td></tr><tr><td>student905</td><td>Mathematics</td><td>male</td><td>65</td><td>44</td><td>96</td><td>79</td></tr><tr><td>student906</td><td>Languages</td><td>female</td><td>68</td><td>77</td><td>8</td><td>65</td></tr><tr><td>student907</td><td>Mathematics</td><td>male</td><td>86</td><td>61</td><td>99</td><td>43</td></tr><tr><td>student908</td><td>Languages</td><td>female</td><td>88</td><td>95</td><td>32</td><td>13</td></tr><tr><td>student909</td><td>Mathematics</td><td>male</td><td>53</td><td>100</td><td>59</td><td>82</td></tr><tr><td>student910</td><td>Languages</td><td>female</td><td>35</td><td>7</td><td>95</td><td>35</td></tr><tr><td>student911</td><td>Mathematics</td><td>male</td><td>23</td><td>0</td><td>1</td><td>77</td></tr><tr><td>student912</td><td>Languages</td><td>female</td><td>9</td><td>68</td><td>72</td><td>63</td></tr><tr><td>student913</td><td>Mathematics</td><td>male</td><td>23</td><td>92</td><td>39</td><td>96</td></tr><tr><td>student914</td><td>Languages</td><td>female</td><td>94</td><td>97</td><td>6</td><td>58</td></tr><tr><td>student915</td><td>Mathematics</td><td>male</td><td>49</td><td>31</td><td>29</td><td>71</td></tr><tr><td>student916</td><td>Languages</td><td>female</td><td>21</td><td>57</td><td>79</td><td>57</td></tr><tr><td>student917</td><td>Mathematics</td><td>male</td><td>0</td><td>35</td><td>100</td><td>89</td></tr><tr><td>student918</td><td>Languages</td><td>female</td><td>64</td><td>82</td><td>75</td><td>52</td></tr><tr><td>student919</td><td>Mathematics</td><td>male</td><td>16</td><td>66</td><td>69</td><td>68</td></tr><tr><td>student920</td><td>Languages</td><td>female</td><td>92</td><td>95</td><td>11</td><td>27</td></tr><tr><td>student921</td><td>Mathematics</td><td>male</td><td>16</td><td>88</td><td>85</td><td>90</td></tr><tr><td>student922</td><td>Languages</td><td>female</td><td>56</td><td>15</td><td>26</td><td>98</td></tr><tr><td>student923</td><td>Mathematics</td><td>male</td><td>78</td><td>27</td><td>40</td><td>17</td></tr><tr><td>student924</td><td>Languages</td><td>female</td><td>95</td><td>10</td><td>44</td><td>32</td></tr><tr><td>student925</td><td>Mathematics</td><td>male</td><td>99</td><td>85</td><td>52</td><td>18</td></tr><tr><td>student926</td><td>Languages</td><td>female</td><td>73</td><td>31</td><td>71</td><td>49</td></tr><tr><td>student927</td><td>Mathematics</td><td>male</td><td>21</td><td>79</td><td>10</td><td>63</td></tr><tr><td>student928</td><td>Languages</td><td>female</td><td>92</td><td>71</td><td>80</td><td>12</td></tr><tr><td>student929</td><td>Mathematics</td><td>male</td><td>23</td><td>29</td><td>33</td><td>88</td></tr><tr><td>student930</td><td>Languages</td><td>female</td><td>41</td><td>8</td><td>98</td><td>84</td></tr><tr><td>student931</td><td>Mathematics</td><td>male</td><td>97</td><td>17</td><td>79</td><td>21</td></tr><tr><td>student932</td><td>Languages</td><td>female</td><td>72</td><td>40</td><td>93</td><td>92</td></tr><tr><td>student933</td><td>Mathematics</td><td>male</td><td>75</td><td>58</td><td>3</td><td>26</td></tr><tr><td>student934</td><td>Languages</td><td>female</td><td>15</td><td>98</td><td>27</td><td>28</td></tr><tr><td>student935</td><td>Mathematics</td><td>male</td><td>76</td><td>88</td><td>80</td><td>6</td></tr><tr><td>student936</td><td>Languages</td><td>female</td><td>84</td><td>23</td><td>42</td><td>92</td></tr><tr><td>student937</td><td>Mathematics</td><td>male</td><td>71</td><td>56</td><td>86</td><td>71</td></tr><tr><td>student938</td><td>Languages</td><td>female</td><td>7</td><td>39</td><td>58</td><td>22</td></tr><tr><td>student939</td><td>Mathematics</td><td>male</td><td>1</td><td>55</td><td>54</td><td>60</td></tr><tr><td>student940</td><td>Languages</td><td>female</td><td>20</td><td>31</td><td>30</td><td>8</td></tr><tr><td>student941</td><td>Mathematics</td><td>male</td><td>97</td><td>54</td><td>41</td><td>81</td></tr><tr><td>student942</td><td>Languages</td><td>female</td><td>83</td><td>41</td><td>86</td><td>64</td></tr><tr><td>student943</td><td>Mathematics</td><td>male</td><td>71</td><td>95</td><td>32</td><td>7</td></tr><tr><td>student944</td><td>Languages</td><td>female</td><td>0</td><td>27</td><td>30</td><td>91</td></tr><tr><td>student945</td><td>Mathematics</td><td>male</td><td>99</td><td>75</td><td>17</td><td>22</td></tr><tr><td>student946</td><td>Languages</td><td>female</td><td>92</td><td>53</td><td>10</td><td>90</td></tr><tr><td>student947</td><td>Mathematics</td><td>male</td><td>4</td><td>44</td><td>94</td><td>32</td></tr><tr><td>student948</td><td>Languages</td><td>female</td><td>0</td><td>97</td><td>48</td><td>79</td></tr><tr><td>student949</td><td>Mathematics</td><td>male</td><td>97</td><td>55</td><td>79</td><td>74</td></tr><tr><td>student950</td><td>Languages</td><td>female</td><td>65</td><td>98</td><td>9</td><td>32</td></tr><tr><td>student951</td><td>Mathematics</td><td>male</td><td>56</td><td>73</td><td>38</td><td>81</td></tr><tr><td>student952</td><td>Languages</td><td>female</td><td>84</td><td>94</td><td>61</td><td>50</td></tr><tr><td>student953</td><td>Mathematics</td><td>male</td><td>48</td><td>20</td><td>77</td><td>0</td></tr><tr><td>student954</td><td>Languages</td><td>female</td><td>39</td><td>98</td><td>14</td><td>20</td></tr><tr><td>student955</td><td>Mathematics</td><td>male</td><td>4</td><td>15</td><td>24</td><td>65</td></tr><tr><td>student956</td><td>Languages</td><td>female</td><td>78</td><td>22</td><td>92</td><td>31</td></tr><tr><td>student957</td><td>Mathematics</td><td>male</td><td>28</td><td>38</td><td>26</td><td>54</td></tr><tr><td>student958</td><td>Languages</td><td>female</td><td>49</td><td>61</td><td>35</td><td>54</td></tr><tr><td>student959</td><td>Mathematics</td><td>male</td><td>81</td><td>15</td><td>28</td><td>17</td></tr><tr><td>student960</td><td>Languages</td><td>female</td><td>54</td><td>80</td><td>58</td><td>2</td></tr><tr><td>student961</td><td>Mathematics</td><td>male</td><td>75</td><td>23</td><td>5</td><td>37</td></tr><tr><td>student962</td><td>Languages</td><td>female</td><td>55</td><td>65</td><td>1</td><td>20</td></tr><tr><td>student963</td><td>Mathematics</td><td>male</td><td>86</td><td>42</td><td>70</td><td>36</td></tr><tr><td>student964</td><td>Languages</td><td>female</td><td>54</td><td>45</td><td>54</td><td>80</td></tr><tr><td>student965</td><td>Mathematics</td><td>male</td><td>38</td><td>18</td><td>69</td><td>92</td></tr><tr><td>student966</td><td>Languages</td><td>female</td><td>33</td><td>89</td><td>46</td><td>83</td></tr><tr><td>student967</td><td>Mathematics</td><td>male</td><td>43</td><td>9</td><td>55</td><td>76</td></tr><tr><td>student968</td><td>Languages</td><td>female</td><td>13</td><td>26</td><td>12</td><td>86</td></tr><tr><td>student969</td><td>Mathematics</td><td>male</td><td>94</td><td>22</td><td>85</td><td>59</td></tr><tr><td>student970</td><td>Languages</td><td>female</td><td>93</td><td>58</td><td>6</td><td>10</td></tr><tr><td>student971</td><td>Mathematics</td><td>male</td><td>35</td><td>72</td><td>85</td><td>36</td></tr><tr><td>student972</td><td>Languages</td><td>female</td><td>37</td><td>51</td><td>96</td><td>93</td></tr><tr><td>student973</td><td>Mathematics</td><td>male</td><td>71</td><td>10</td><td>79</td><td>59</td></tr><tr><td>student974</td><td>Languages</td><td>female</td><td>71</td><td>31</td><td>73</td><td>93</td></tr><tr><td>student975</td><td>Mathematics</td><td>male</td><td>80</td><td>26</td><td>86</td><td>97</td></tr><tr><td>student976</td><td>Languages</td><td>female</td><td>69</td><td>21</td><td>67</td><td>69</td></tr><tr><td>student977</td><td>Mathematics</td><td>male</td><td>38</td><td>86</td><td>10</td><td>39</td></tr><tr><td>student978</td><td>Languages</td><td>female</td><td>48</td><td>90</td><td>39</td><td>81</td></tr><tr><td>student979</td><td>Mathematics</td><td>male</td><td>90</td><td>83</td><td>3</td><td>42</td></tr><tr><td>student980</td><td>Languages</td><td>female</td><td>19</td><td>1</td><td>91</td><td>84</td></tr><tr><td>student981</td><td>Mathematics</td><td>male</td><td>98</td><td>25</td><td>50</td><td>46</td></tr><tr><td>student982</td><td>Languages</td><td>female</td><td>38</td><td>88</td><td>21</td><td>16</td></tr><tr><td>student983</td><td>Mathematics</td><td>male</td><td>71</td><td>48</td><td>18</td><td>43</td></tr><tr><td>student984</td><td>Languages</td><td>female</td><td>79</td><td>85</td><td>18</td><td>16</td></tr><tr><td>student985</td><td>Mathematics</td><td>male</td><td>51</td><td>66</td><td>90</td><td>68</td></tr><tr><td>student986</td><td>Languages</td><td>female</td><td>100</td><td>95</td><td>65</td><td>91</td></tr><tr><td>student987</td><td>Mathematics</td><td>male</td><td>6</td><td>74</td><td>24</td><td>24</td></tr><tr><td>student988</td><td>Languages</td><td>female</td><td>93</td><td>80</td><td>94</td><td>35</td></tr><tr><td>student989</td><td>Mathematics</td><td>male</td><td>65</td><td>78</td><td>57</td><td>94</td></tr><tr><td>student990</td><td>Languages</td><td>female</td><td>27</td><td>92</td><td>21</td><td>91</td></tr><tr><td>student991</td><td>Mathematics</td><td>male</td><td>77</td><td>15</td><td>26</td><td>76</td></tr><tr><td>student992</td><td>Languages</td><td>female</td><td>28</td><td>84</td><td>51</td><td>67</td></tr><tr><td>student993</td><td>Mathematics</td><td>male</td><td>3</td><td>78</td><td>62</td><td>50</td></tr><tr><td>student994</td><td>Languages</td><td>female</td><td>59</td><td>77</td><td>20</td><td>74</td></tr><tr><td>student995</td><td>Mathematics</td><td>male</td><td>62</td><td>66</td><td>8</td><td>75</td></tr><tr><td>student996</td><td>Languages</td><td>female</td><td>88</td><td>70</td><td>33</td><td>43</td></tr><tr><td>student997</td><td>Mathematics</td><td>male</td><td>73</td><td>33</td><td>42</td><td>53</td></tr><tr><td>student998</td><td>Languages</td><td>female</td><td>64</td><td>10</td><td>2</td><td>31</td></tr><tr><td>student999</td><td>Mathematics</td><td>male</td><td>91</td><td>93</td><td>16</td><td>35</td></tr><tr><td>student1000</td><td>Languages</td><td>female</td><td>30</td><td>68</td><td>95</td><td>40</td></tr><tr><td>student1001</td><td>Mathematics</td><td>male</td><td>25</td><td>2</td><td>48</td><td>32</td></tr><tr><td>student1002</td><td>Languages</td><td>female</td><td>50</td><td>77</td><td>53</td><td>81</td></tr><tr><td>student1003</td><td>Mathematics</td><td>male</td><td>67</td><td>44</td><td>10</td><td>65</td></tr><tr><td>student1004</td><td>Languages</td><td>female</td><td>29</td><td>53</td><td>34</td><td>86</td></tr><tr><td>student1005</td><td>Mathematics</td><td>male</td><td>77</td><td>69</td><td>22</td><td>75</td></tr><tr><td>student1006</td><td>Languages</td><td>female</td><td>48</td><td>82</td><td>95</td><td>40</td></tr><tr><td>student1007</td><td>Mathematics</td><td>male</td><td>30</td><td>71</td><td>29</td><td>63</td></tr><tr><td>student1008</td><td>Languages</td><td>female</td><td>45</td><td>31</td><td>4</td><td>71</td></tr><tr><td>student1009</td><td>Mathematics</td><td>male</td><td>81</td><td>12</td><td>20</td><td>44</td></tr><tr><td>student1010</td><td>Languages</td><td>female</td><td>17</td><td>66</td><td>82</td><td>42</td></tr><tr><td>student1011</td><td>Mathematics</td><td>male</td><td>15</td><td>11</td><td>32</td><td>18</td></tr><tr><td>student1012</td><td>Languages</td><td>female</td><td>27</td><td>34</td><td>59</td><td>19</td></tr><tr><td>student1013</td><td>Mathematics</td><td>male</td><td>18</td><td>67</td><td>25</td><td>14</td></tr><tr><td>student1014</td><td>Languages</td><td>female</td><td>24</td><td>64</td><td>52</td><td>24</td></tr><tr><td>student1015</td><td>Mathematics</td><td>male</td><td>36</td><td>87</td><td>48</td><td>46</td></tr><tr><td>student1016</td><td>Languages</td><td>female</td><td>33</td><td>1</td><td>70</td><td>68</td></tr><tr><td>student1017</td><td>Mathematics</td><td>male</td><td>48</td><td>26</td><td>3</td><td>80</td></tr><tr><td>student1018</td><td>Languages</td><td>female</td><td>53</td><td>63</td><td>85</td><td>57</td></tr><tr><td>student1019</td><td>Mathematics</td><td>male</td><td>58</td><td>73</td><td>0</td><td>24</td></tr><tr><td>student1020</td><td>Languages</td><td>female</td><td>85</td><td>90</td><td>81</td><td>0</td></tr><tr><td>student1021</td><td>Mathematics</td><td>male</td><td>69</td><td>28</td><td>52</td><td>76</td></tr><tr><td>student1022</td><td>Languages</td><td>female</td><td>75</td><td>22</td><td>7</td><td>52</td></tr></tbody>
</table>';
    	
		    $pagerDiv ='	<div id="pager" class="pager">
			<form>
				<img src="core_modules/htmlelements/resources/jquery/plugins/tablesorter/pager/icons/first.png" class="first"/>
				<img src="core_modules/htmlelements/resources/jquery/plugins/tablesorter/pager/icons/prev.png" class="prev"/>
				<input type="text" class="pagedisplay"/>
				<img src="core_modules/htmlelements/resources/jquery/plugins/tablesorter/pager/icons/next.png" class="next"/>
				<img src="core_modules/htmlelements/resources/jquery/plugins/tablesorter/pager/icons/last.png" class="last"/>
				<select class="pagesize">
					<option selected="selected"  value="10">10</option>
					<option value="20">20</option>
					<option value="30">30</option>
					<option  value="40">40</option>
				</select>
			</form>
		</div>';
		    
		return $this->listContexts().$pagerDiv;
    }
    
    /**
     * Method to get all the context by a filter
     * @param string $filter
     */
    public function getContextList()
    {
    	$contexts = $this->objDBContext->getAll();

		$arr = array();
		foreach($contexts as $context)
		{
			$arr[ $this->objDBContext->getTitle($context['contextcode'])] = $context['contextcode'];//$user['userid'];
		}

		return $arr;
    }
    
     /**
     * Method to get all the context by a filter
     * @param string $filter
     */
    public function getUserList()
    {
    	$users = $this->objUser->getAll();

		$arr = array();
		foreach($users as $user)
		{
			$arr[ $this->objUser->fullname($user['userid'])] = $user['username'];//$user['userid'];
		}

		return $arr;
    }
    
    
    /**
     * Method to format a users context
     * @oaram string username
     */
    public function formatUserContext($username)
    {
    	$this->objUserContext = $this->getObject('usercontext', 'context');
    	$contexts = $this->objUserContext->getUserContext($this->objUser->getUserId($username));
    	if(count($contexts) > 0)
    	{
    		$str = "";
    		$objDisplayContext = $this->getObject ( 'displaycontext', 'context' );
    		foreach($contexts as $contextCode)
    		{
    			$context = $this->objDBContext->getContext($contextCode);
    			$str .= $objDisplayContext->formatContextDisplayBlock ( $context, FALSE, FALSE ) . '<br />';
    		}
    		
    		return $str;
    	} else {
    		return '<span class="subdued>'.$this->objLanguage->code2Txt('phrase_othercourses', 'system', NULL, 'No [-contexts-] found for this user') .'</span>';
    	}
    	
    }
    
    /**
     * Method to format a context
     * @oaram string username
     */
    public function formatSelectedContext($contextCode)
    {
    	$context = $this->objDBContext->getContext($contextCode);
    	$objDisplayContext = $this->getObject ( 'displaycontext', 'context' );
    	return $objDisplayContext->formatContextDisplayBlock ( $context, FALSE, FALSE ) . '<br />';
    }
    
    /**
     * Method to show all the context
     */
    public function listContexts()
    {
    	$objIcon = $this->getObject('geticon','htmlelements');
    	$objLink = $this->getObject('link','htmlelements');
    	$objTable = $this->getObject('htmltable','htmlelements');
    	
    	$contexts = $this->objDBContext->getAll("ORDER BY updated DESC");
    	if(count($contexts) > 1)
    	{
    		$str = '<table><tr class="header"><td>Title</td><td>Code</td><td>Creator</td><<td>Lat Updated</td>/t>&nbsp;</td></tr>';
    		$str = '<table cellspacing="1" class="tablesorter">
						<thead>
							<tr>
								<th>Title</th>
								<th>Code</th>
								<th>Creator</th>
								<th>Date Created</th>
								<th>Last Updated</th>
								<th>&nbsp;</th>					
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>Title</th>
								<th>Code</th>
								<th>Creator</th>
								<th>Date Created</th>
								<th>Last Updated</th>
								<th>&nbsp;</th>	

							</tr>
						</tfoot><tbody>';
    		$objTable->addHeaderCell('Code');
    		$objTable->addHeaderCell('Title', '40%');    		
    		$objTable->addHeaderCell('Creator');
    		$objTable->addHeaderCell('Last Updated');
    		$objTable->addHeaderCell('&nbsp');
    		
    		foreach($contexts as $context)
    		{
    			$arr = array();
    			$arr[] = $context['contextcode'];
    			$arr[]= $context['title'];
    			
    			$arr[] =$this->objUser->fullname($context['userid']);
    			$arr[]=$context['updated'];
    			
    			$str .='<tr>';    			
    			$str .='<td>'.$context['contextcode'].'</td>';
    			$str .='<td>'.$context['title'].'</td>';
    			$str .='<td>'.$this->objUser->fullname($context['userid']).'</td>';
    			$str .='<td>'.$context['datecreated'].'</td>';
    			$str .='<td>'.$context['updated'].'</td>';
    			$objIcon->setIcon('entercourse');
    			$objLink->href = $this->uri(array('action' => 'joincontext', 'contextcode' => $context['contextcode']), 'context');
    			$objLink->link = $objIcon->show();
    			$enter = $objLink->show();
    			
    			$objIcon->setIcon('delete');
    			$objLink->href = $this->uri(array('action' => 'delete', 'contextcode' => $context['contextcode']), 'contextadmin');
    			$objLink->link = $objIcon->show();
    			$delete = $objLink->show();    			
    			
    			$str .='<td>'.$enter.$delete.'</td>';
    			
    			$str .= '</tr>';
    			
    			$arr[] = $enter.$delete;
    			$objTable->addRow($arr);
    		}
    		$str .= '</tbody></table>';
    		
    		return $str;
    	}
    	
    	
    }
}
?>