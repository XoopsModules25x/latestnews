<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package
 * @since
 * @author     XOOPS Development Team
 * @version    $Id $
 */

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';
include_once $path . '/include/cp_functions.php';
require_once $path . '/include/cp_header.php';
include_once $path . '/class/xoopsformloader.php';
include_once $path . '/class/xoopsform/grouppermform.php';

global $xoopsModule;

$thisModuleDir = $GLOBALS['xoopsModule']->getVar('dirname');

//if functions.php file exist
//require_once dirname(dirname(__FILE__)) . '/include/functions.php';

// Load language files
xoops_loadLanguage('admin', $thisModuleDir);
xoops_loadLanguage('modinfo', $thisModuleDir);
xoops_loadLanguage('main', $thisModuleDir);

//$pathIcon16 = '../'.$xoopsModule->getInfo('icons16');
$pathIcon16 = XOOPS_URL.'/'.$xoopsModule->getInfo('icons16root');
$pathIcon32 = XOOPS_URL.'/'.$xoopsModule->getInfo('icons32root');

//$pathIcon32 = '../'.$xoopsModule->getInfo('icons32');

$pathModuleAdmin = $xoopsModule->getInfo('dirmoduleadmin');

include_once $GLOBALS['xoops']->path($pathModuleAdmin.'/moduleadmin.php');
