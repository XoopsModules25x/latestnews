<?php
defined("XOOPS_ROOT_PATH") or die("XOOPS root path not defined");

//$path = dirname(dirname(dirname(dirname(__FILE__))));
//include_once $path . '/mainfile.php';

$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname(basename(dirname(dirname(__FILE__))));
$pathIcon32      = $module->getInfo('icons32');
//$pathIcon32      = XOOPS_ROOT_PATH.$module->getInfo('icons32');

xoops_loadLanguage('admin', $module->dirname());

$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = dirname(dirname(dirname(dirname(__FILE__)))) . $pathModuleAdmin;
if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}
include_once $fileinc;

$adminmenu = array();
$i=0;
$adminmenu[$i]["title"] = _AM_MODULEADMIN_HOME;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/home.png';
$i++;
$adminmenu[$i]['title'] = _MI_LATESTNEWS_ADMIN_MENU1;
$adminmenu[$i]['link'] = "admin/blocksadmin.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/block.png';
$i++;
$adminmenu[$i]['title'] = _MI_LATESTNEWS_ADMIN_MENU2;
$adminmenu[$i]['link'] = "admin/permissions.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/permissions.png';
$i++;
$adminmenu[$i]['title'] = _AM_MODULEADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["icon"]  = $pathIcon32 . '/about.png';
