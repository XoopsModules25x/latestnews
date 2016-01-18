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

function block_latestnews_show($options)
{
    global $xoopsTpl, $xoopsUser, $xoopsConfig, $pathIcon16, $xoopsModule;
    include_once XOOPS_ROOT_PATH . '/modules/latestnews/include/functions.php';

    $module_handler = & xoops_gethandler('module');
    $mydir          = basename(dirname(dirname(__FILE__)));

    $mymodule   = & $module_handler->getByDirname($mydir);
    $pathIcon16 = XOOPS_URL . '/' . $mymodule->getInfo('icons16root');
//    $pathIcon16 = $mymodule->getInfo('icons16');

    $block = array();

    if (!latestnews_checkmodule('news')) {
        return $block;
    }

    include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newsstory.php';
    include_once XOOPS_ROOT_PATH . '/modules/news/class/class.sfiles.php';
    include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
    include_once XOOPS_ROOT_PATH . '/modules/news/include/functions.php';
    include_once XOOPS_ROOT_PATH . '/class/tree.php';
    include_once XOOPS_ROOT_PATH . '/modules/latestnews/class/class.latestnews.php'; //Bandit-X

    if (file_exists(XOOPS_ROOT_PATH . '/modules/news/language/' . $xoopsConfig['language'] . '/main.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/news/language/' . $xoopsConfig['language'] . '/main.php';
    } else {
        include_once XOOPS_ROOT_PATH . '/modules/news/language/english/main.php';
    }

    $myts   =& MyTextSanitizer::getInstance();
    $sfiles = new sFiles();

    $dateformat = news_getmoduleoption('dateformat');
    if ($dateformat == '') {
        $dateformat = 's';
    }

    $limit            = $options[0];
    $column_count     = $options[1];
    $letters          = $options[2];
    $imgwidth         = $options[3];
    $imgheight        = $options[4];
    $border           = $options[5];
    $bordercolor      = $options[6];
    $selected_stories = $options[7];

    $block['spec']['columnwidth'] = intval(1 / $column_count * 100);
    if ($options[8] == 1) {
        $imgposition = 'right';
    } else {
        $imgposition = 'left';
    }

    $xoopsTpl->assign(
        'xoops_module_header',
        '<style type="text/css">
           .itemText {text-align: justify;}
           .latestnews {border-bottom: 1px solid #cccccc; padding: 5px;}
           .latestnews img { vertical-align:baseline; padding: 2px; margin: 5px}</style>' . $xoopsTpl->get_template_vars(
            "xoops_module_header"
        )
    );

    if (!isset($options[25])) {
        $sarray = LatestNewsStory::getAllPublished($limit, $selected_stories, 0, true, 0, 0, true, $options[24], false);
    } else {
        $topics = array_slice($options, 25);
        $sarray = LatestNewsStory::getAllPublished(
            $limit,
            $selected_stories,
            0,
            true,
            $topics,
            0,
            true,
            $options[24],
            false
        );
    }

    $scount  = count($sarray);
    $k       = 0;
    $columns = array();
    if ($scount > 0) {
        $storieslist = array();
        $height      = 0;
        $width       = 0;
        foreach ($sarray as $storyid => $thisstory) {
            $storieslist[] = $thisstory->storyid();
        }
        $filesperstory = $sfiles->getCountbyStories($storieslist);

        foreach ($sarray as $key => $thisstory) {
            $storyid    = $thisstory->storyid();
            $filescount = array_key_exists($thisstory->storyid(), $filesperstory) ? $filesperstory[$thisstory->storyid()] : 0;
            $published  = formatTimestamp($thisstory->published(), $dateformat);
            $bodytext   = $thisstory->bodytext;
            $news       = $thisstory->prepare2show($filescount);

            $len = strlen($thisstory->hometext());
            if ($letters < $len && $letters > 0) {

                $patterns     = array();
                $replacements = array();

                if ($options[4] != 0) {
                    $height = 'height="' . $imgheight . '"';
                } // set height = 0 in block option for auto height

                $startdiv = '<div style="float:' . $imgposition . '"><a href="' . XOOPS_URL . '/modules/news/article.php?storyid=' . $storyid . '">';
                $style    = 'style="border: ' . $border . 'px solid #' . $bordercolor . '"';
                $enddiv   = 'alt="' . $thisstory->title . '" width="' . $imgwidth . '" ' . $height . ' /></a></div>';

                $patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 width=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
                $patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
                $patterns[] = "/\[img]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
                $patterns[] = "/<img src=\"(.*)\" \/>/sU";
                $patterns[] = "/<img src=(.*) \/>/sU";

                $replacements[] = $startdiv . '<img ' . $style . ' src="\\3" ' . $enddiv;
                $replacements[] = $startdiv . '<img ' . $style . ' src="\\3" ' . $enddiv;
                $replacements[] = $startdiv . '<img ' . $style . ' src="\\1" ' . $enddiv;
                $replacements[] = $startdiv . '<img ' . $style . ' src="\\1" ' . $enddiv;
                $replacements[] = $startdiv . '<img ' . $style . ' src="\\1" ' . $enddiv;

                $letters      = strrpos(substr($thisstory->hometext, 0, $letters), ' ');
                $news['text'] = preg_replace($patterns, $replacements,xoops_substr($thisstory->hometext, 0, $letters + 3));
            }

            if (is_object($xoopsUser) && $xoopsUser->isAdmin(-1)) {
                $news['admin']
                    = '<a href="' . XOOPS_URL . '/modules/news/admin/index.php?op=edit&amp;storyid=' . $storyid . '"><img src="' . $pathIcon16
                    . '/edit.png" title="' . _EDIT . '" alt="' . _EDIT . '"  /></a> <a href="' . XOOPS_URL
                    . '/modules/news/admin/index.php?op=delete&amp;storyid=' . $storyid . '"><img src="' . $pathIcon16 . '/delete.png" title="'
                    . _DELETE . '" alt="' . _DELETE . '"  /></a>';
            } else {
                $news['admin'] = '';
            }
            if ($options[9] == 1) {
                $block['topiclink']
                    = '| <a href="' . XOOPS_URL . '/modules/news/topics_directory.php">' . _AM_NEWS_TOPICS_DIRECTORY . '</a> ';
            }
            if ($options[10] == 1) {
                $block['archivelink']
                    = '| <a href="' . XOOPS_URL . '/modules/news/archive.php">' . _NW_NEWSARCHIVES . '</a> ';
            }
            if ($options[11] == 1) {
                if (empty($xoopsUser)) {
                    $block['submitlink'] = '';
                } else {
                    $block['submitlink']
                        = '| <a href="' . XOOPS_URL . '/modules/news/submit.php">' . _NW_SUBMITNEWS . '</a> ';
                }
            }

            $news['poster'] = '';
            if ($options[12] == 1) {
                $news['poster'] = '' . _MB_LATESTNEWS_POSTER . ' ' . $thisstory->uname() . '';
            }
            $news['posttime'] = '';
            if ($options[13] == 1) {
                $news['posttime'] = '' . _ON . ' ' . $published . '';
            }
            $news['topic_title'] = '';
            if ($options[14] == 1) {
                $news['topic_title'] = '' . $thisstory->textlink() . '' . _MB_SP . '';
            }
            $news['read'] = '';
            if ($options[15] == 1) {
                $news['read'] = '&nbsp;(' . $thisstory->counter . ' ' . _READS . ')';
            }

            $comments = $thisstory->comments();
            if (!empty($bodytext) || $comments > 0) {
                $news['more']
                    = '<a href="' . XOOPS_URL . '/modules/news/article.php?storyid=' . $storyid . '">' . _NW_READMORE . '</a>';
            } else {
                $news['more'] = '';
            }
            if ($options[16] == 1) {
                if ($comments > 0) {
                    //shows 1 comment instead of 1 comm. if comments ==1
                    //langugage file modified accordingly
                    if ($comments == 1) {
                        $news['comment'] = '&nbsp;' . _NW_ONECOMMENT . '</a>&nbsp;';
                    } else {
                        $news['comment'] = '&nbsp;' . $comments . '&nbsp;' . _NW_COMMENTS . '</a>&nbsp;';
                    }
                } else {
                    $news['comment'] = '&nbsp;' . _MB_NO_COMMENT . '</a>&nbsp;';
                }
            }

            $news['print'] = '';
            if ($options[17] == 1) {
                $news['print'] = '<a href="' . XOOPS_URL . '/modules/news/print.php?storyid=' . $storyid . '" rel="nofollow"><img src=' . $pathIcon16
                    . '/printer.png title="' . _NW_PRINTERFRIENDLY . '" alt="' . _NW_PRINTERFRIENDLY . '" /></a>';
            }

            $news['pdf'] = '';
            if ($options[18] == 1) {
                $news['pdf']
                    = '&nbsp;<a href="' . XOOPS_URL . '/modules/news/makepdf.php?storyid=' . $storyid . '" rel="nofollow"><img src="' . $pathIcon16
                    . '/pdf.png" title="' . _NW_MAKEPDF . '" alt="' . _NW_MAKEPDF . '" /></a>&nbsp;';
            }

            $news['email'] = '';
            if ($options[19] == 1) {
                $news['email']
                    = '<a href="mailto:?subject=' . sprintf(_NW_INTARTICLE, $xoopsConfig['sitename']) . '&amp;body=' . sprintf(
                        _NW_INTARTFOUND,
                        $xoopsConfig['sitename']
                    ) . ':  ' . XOOPS_URL . '/modules/news/article.php?storyid=' . $storyid . '" rel="nofollow"><img src="' . $pathIcon16
                    . '/mail_forward.png" title="' . _NW_SENDSTORY . '" alt="' . _NW_SENDSTORY . '" /></a>&nbsp;';
            }

            if ($options[20] == 1) {
                $block['morelink']
                    = '&nbsp;<a href="' . XOOPS_URL . '/modules/news/index.php?storytopic=0&start=' . $limit . '">' . _MB_MORE_STORIES . '</A> ';
            }

            if ($options[21] == 1) {
                $block['latestnews_scroll'] = true;
            } else {
                $block['latestnews_scroll'] = false;
            }

            $block['scrollheight'] = $options[22];
            $block['scrollspeed']  = $options[23];

            $columns[$k][] = $news;
            $k++;
            if ($k == $column_count) {
                $k = 0;
            }
        }
    }
    unset($news);
    $block['columns'] = $columns;

    return $block;
}

function b_latestnews_edit($options)
{
    global $xoopsDB;
    include_once XOOPS_ROOT_PATH . '/modules/latestnews/include/functions.php';
    include_once XOOPS_ROOT_PATH . '/modules/news/class/class.newstopic.php';
    include_once XOOPS_ROOT_PATH . '/modules/latestnews/class/xoopstree.php';
    if (!latestnews_checkmodule('news')) {
        return _MB_LATESTNEWS_NEWSNOTINST;
    }
    $tabletag1 = '<tr><td>';
    $tabletag2 = '</td><td>';

    $form = "<table border='0'>";
    $form .= $tabletag1 . _MB_LATESTNEWS_DISPLAY . $tabletag2;
    $form .= "<input type='text' name='options[]' value='" . $options[0] . "' size='4'>&nbsp;" . _MB_LATESTNEWS . "</td></tr>";
    $form .= $tabletag1 . _MB_LATESTNEWS_COLUMNS . $tabletag2;
    $form .= "<input type='text' name='options[]' value='" . $options[1] . "' size='4'>&nbsp;" . _MB_LATESTNEWS_COLUMN . "</td></tr>";
    $form .= $tabletag1 . _MB_LATESTNEWS_TEXTLENGTH . $tabletag2;
    $form .= "<input type='text' name='options[]' value='" . $options[2] . "' size='4'>&nbsp;" . _MB_LATESTNEWS_LETTER . "</td></tr>";
    $form .= $tabletag1 . _MB_LATESTNEWS_IMGWIDTH . $tabletag2;
    $form .= "<input type='text' name='options[]' value='" . $options[3] . "' size='4'>&nbsp;" . _MB_LATESTNEWS_PIXEL . "</td></tr>";
    $form .= $tabletag1 . _MB_LATESTNEWS_IMGHEIGHT . $tabletag2;
    $form .= "<input type='text' name='options[]' value='" . $options[4] . "' size='4'>&nbsp;" . _MB_LATESTNEWS_PIXEL . "</td></tr>";
    $form .= $tabletag1 . _MB_LATESTNEWS_BORDER . $tabletag2;
    $form .= "<input type='text' name='options[]' value='" . $options[5] . "' size='4'>&nbsp;" . _MB_LATESTNEWS_PIXEL . "</td></tr>";
    $form .= $tabletag1 . _MB_LATESTNEWS_BORDERCOLOR . $tabletag2;
    $form .= "<input type='text' name='options[]' value='" . $options[6] . "' size='8'></td></tr>";
    $form .= $tabletag1 . _MB_LATESTNEWS_SELECTEDSTORIES . $tabletag2;
    $form .= "<input type='text' name='options[]' value='" . $options[7] . "' size='16'></td></tr>";
    $form .= $tabletag1 . _MB_LATESTNEWS_IMGPOSITION . $tabletag2;
    $form .= latestnews_mk_select($options, 8);
    $form .= $tabletag1 . _MB_LATESTNEWS_TOPICLINK . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 9);
    $form .= $tabletag1 . _MB_LATESTNEWS_ARCHIVELINK . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 10);
    $form .= $tabletag1 . _MB_LATESTNEWS_SUBMITLINK . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 11);
    $form .= $tabletag1 . _MB_LATESTNEWS_POSTEDBY . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 12);
    $form .= $tabletag1 . _MB_LATESTNEWS_POSTTIME . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 13);
    $form .= $tabletag1 . _MB_LATESTNEWS_TOPICTITLE . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 14);
    $form .= $tabletag1 . _MB_LATESTNEWS_READ . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 15);
    $form .= $tabletag1 . _MB_LATESTNEWS_COMMENT . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 16);
    $form .= $tabletag1 . _MB_LATESTNEWS_PRINT . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 17);
    $form .= $tabletag1 . _MB_LATESTNEWS_PDF . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 18);
    $form .= $tabletag1 . _MB_LATESTNEWS_EMAIL . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 19);
    $form .= $tabletag1 . _MB_LATESTNEWS_MORELINK . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 20);
    $form .= $tabletag1 . _MB_LATESTNEWS_SCROLL . $tabletag2;
    $form .= latestnews_mk_chkbox($options, 21);
    $form .= $tabletag1 . _MB_LATESTNEWS_SCROLLHEIGHT . $tabletag2;
    $form .= "<input type='text' name='options[]' value='" . $options[22] . "' size='4'></td></tr>";
    $form .= $tabletag1 . _MB_LATESTNEWS_SCROLLSPEED . $tabletag2;
    $form .= "<input type='text' name='options[]' value='" . $options[23] . "' size='4'></td></tr>";

    //order
    $form .= $tabletag1 . _MB_LATESTNEWS_ORDERBY . $tabletag2;
    $form .= "<select name='options[]'>";
    $form .= "<option value='published'";
    if ($options[24] == 'published') {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_LATESTNEWS_DATE . "</option>\n";

    $form .= "<option value='counter'";
    if ($options[24] == 'counter') {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_LATESTNEWS_HITS . '</option>';
    $form .= "<option value='rating'";
    if ($options[24] == 'rating') {
        $form .= " selected='selected'";
    }
    $form .= '>' . _MB_LATESTNEWS_RATE . '</option>';
    $form .= "</select></td></tr>";

    //topics
    $form .= $tabletag1 . _MB_LATESTNEWS_TOPICSDISPLAY . $tabletag2;
    $form .= "<select name='options[]' multiple='multiple'>";
    $topics_arr = array();
    $xt         = new LatestnewsXoopsTree($xoopsDB->prefix('mod_news_topics'), 'topic_id', 'topic_pid');
    $topics_arr = $xt->getChildTreeArray(0, 'topic_title');
    $size       = count($options);
    foreach ($topics_arr as $onetopic) {
        $sel = '';
        if ($onetopic['topic_pid'] != 0) {
            $onetopic['prefix'] = str_replace('.', '-', $onetopic['prefix']) . '&nbsp;';
        } else {
            $onetopic['prefix'] = str_replace('.', '', $onetopic['prefix']);
        }
        for ($i = 25; $i < $size; $i++) {
            if ($options[$i] == $onetopic['topic_id']) {
                $sel = " selected='selected'";
            }
        }
        $form .= "<option value='" . $onetopic['topic_id'] . "'$sel>" . $onetopic['prefix'] . $onetopic['topic_title'] . '</option>';
    }
    $form .= '</select></td></tr>';

    $form .= "</table>";

    return $form;
}
