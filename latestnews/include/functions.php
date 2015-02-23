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

function latestnews_checkmodule($checkmodule='news')
{
    $retval = false;
    $module_handler =& xoops_gethandler('module');
    $module =& $module_handler->getByDirname($checkmodule);

    if (is_object($module) && $module->getVar('isactive')) {
        $retval = true;
    }

    return $retval;
}

function latestnews_mk_chkbox($options, $number)
{
    $chk   = "";
    if ($options[$number] == 1) {
        $chk = " checked='checked'";
    }
    $chkbox = "<input type='radio' name='options[$number]' value='1'".$chk." />&nbsp;"._YES."&nbsp;&nbsp;";
    $chk   = "";
    if ($options[$number] == 0) {
        $chk = " checked='checked'";
    }
    $chkbox .= "<input type='radio' name='options[$number]' value='0'".$chk." />&nbsp;"._NO."</td></tr>";

    return $chkbox;
}

function latestnews_mk_select($options, $number)
{
    $slc   = "";
    if ($options[$number] == 1) {
        $slc = " checked='checked'";
    }
    $select = "<input type='radio' name='options[$number]' value='1'".$slc." />&nbsp;"._LEFT."&nbsp;&nbsp;";
    $slc   = "";
    if ($options[$number] == 0) {
        $slc = " checked='checked'";
    }
    $select .= "<input type='radio' name='options[$number]' value='0'".$slc." />&nbsp;"._RIGHT."</td></tr>";

    return $select;
}
