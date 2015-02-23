<?php
// ######################################################################
// #                                                                    #
// # Latest News block by Mowaffak ( www.arabxoops.com )                #
// # based on Last Articles Block by Pete Glanz (www.glanz.ru)          #
// # Thanks to:                                                         #
// # Trabis ( www.xuups.com ) and Bandit-x ( www.bandit-x.net )         #
// #                                                                    #
// ######################################################################
// # Use of this program is goverened by the terms of the GNU General   #
// # Public License (GPL - version 1 or 2) as published by the          #
// # Free Software Foundation (http://www.gnu.org/)                     #
// ######################################################################
if (!defined('XOOPS_ROOT_PATH')) {
    die('XOOPS root path not defined');
}
$modversion['name'] = _MI_LATESTNEWS_BLOCK;
$modversion['version'] = "0.73";
$modversion['description'] = _MI_LATESTNEWS_BLOCKS_DESC;
$modversion['credits'] = "Original version Darren Poulton<br />paladin@intaleather.com.au<br />http://paladin.intaleather.com.au/<br />Ported by: Sylvain B. (sylvain@123rando.com)<br />Altered version SUIN<br />http://tms.s10.xrea.com:8080/";
$modversion['author'] = "Mowaffak, Trabis, Bandit-x";
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0 or later';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html";
$modversion['official'] = 0;
$modversion['image'] = "images/latestnews_logo.png";
$modversion['dirname'] = basename(dirname(__FILE__ ));

$modversion['icons16root']        = 'Frameworks/moduleclasses/icons/16';
$modversion['icons32root']        = 'Frameworks/moduleclasses/icons/32';
$modversion['dirmoduleadmin'] = '/Frameworks/moduleclasses/moduleadmin';
$modversion['icons16']        = '../../'.$modversion['icons16root'] ;
//$modversion['icons32']        = '../../Frameworks/moduleclasses/icons/32';
$modversion['icons32']        = '../../'.$modversion['icons32root'] ;

//about
$modversion['release_date']        = '2013/09/26';
$modversion["module_website_url"]  = "www.xoops.org";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"]       = "Beta 1";
$modversion['min_php']             = '5.2';
$modversion['min_xoops']           = "2.5.6";
$modversion['min_admin']           = '1.1';
$modversion['min_db']              = array(
    'mysql'  => '5.0.7',
    'mysqli' => '5.0.7'
);

$modversion['hasAdmin'] = 1;
$modversion['system_menu'] = 1;
$modversion['adminindex'] = "admin/index.php";
//$modversion['adminindex'] = "admin/myblocksadmin.php";
$modversion['adminmenu'] = "admin/menu.php";

// Blocks
$modversion['blocks'][1]['file'] = "latestnews.php";
$modversion['blocks'][1]['name'] = _MI_LATESTNEWS_BLOCK;
$modversion['blocks'][1]['show_func'] = "block_latestnews_show";
$modversion['blocks'][1]['edit_func'] = "b_latestnews_edit";
$modversion['blocks'][1]['template'] = 'block_latestnews.tpl';
$modversion['blocks'][1]['options'] = "6|2|200|100|100|2|dcdcdc|0|right|1|1|1|1|1|1|1|1|1|1|1|1|0|100|30|published|";
$modversion['blocks'][1]['can_clone'] = true;

// On Update
if ( ! empty( $_POST['fct'] ) && ! empty( $_POST['op'] ) && $_POST['fct'] == 'modulesadmin' && $_POST['op'] == 'update_ok' && $_POST['dirname'] == $modversion['dirname'] ) {
    include dirname( __FILE__ ) . "/include/onupdate.inc.php" ;
}
