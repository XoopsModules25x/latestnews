<?php
/**
 * Module: LatestNews
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * PHP version 5
 *
 * @category        Module
 * @package         LatestNews
 * @author          XOOPS Development Team
 * @copyright       2001-2013 The XOOPS Project
 * @license         GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @version         $Id: permissions.php 12106 2013-09-29 10:23:10Z beckmi $
 * @link            http://sourceforge.net/projects/xoops/
 * @since           0.73
 */

include_once dirname(__FILE__) . '/admin_header.php';

xoops_cp_header();

$permissions_admin = new ModuleAdmin();
echo $permissions_admin->addNavigation('permissions.php');

$moduleId = $xoopsModule->getVar('mid');

$formTitle             = _AM_LATESTNEWS_PERMISSIONS;
$permissionName        = basename(dirname(dirname(__FILE__)));
$permissionDescription = _AM_LATESTNEWS_PERMISSIONS_DSC;
$global_perms_array    = array(
    '1' => _AM_LATESTNEWS_ACTIVERIGHTS,
    '2' => _AM_LATESTNEWS_ACCESSRIGHTS
);

$permissionsForm
    = new XoopsGroupPermForm($formTitle, $moduleId, $permissionName, $permissionDescription, 'admin/permissions.php');
foreach ($global_perms_array as $perm_id => $permissionName) {
    $permissionsForm->addItem($perm_id, $permissionName);
}
echo $permissionsForm->render();
echo "<br /><br /><br /><br />\n";
unset ($permissionsForm);

include 'admin_footer.php';
