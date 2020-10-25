<?php

##################################################################################
# webcam-portail Version 1.0 alpha                                               #
#                                                                                #
# Projet du 30/05/2002          dernière modification:                           #
# Scripts Home:                 http://www.lespace.org                           #
#              							                 #
# Xoops-RC2 Version: 1.0 alpha  bidou@lespace.org                                #
# Site web         :		http://www.lespace.org                           #
# licence          :            Gpl                                              #
# Merci de laisser cette entête en place.                                        #
##################################################################################
include('../../mainfile.php');
include('cache/config-inc.php');

require_once('function.php');

require_once($xoopsConfig['root_path'] . 'class/module.textsanitizer.php');

if (file_exists($xoopsConfig['root_path'] . "modules/$Module_Name/language/" . $xoopsConfig['language'] . '/main.php')) {
    include $xoopsConfig['root_path'] . "modules/$Module_Name/language/" . $xoopsConfig['language'] . '/main.php';
} else {
    include $xoopsConfig['root_path'] . "modules/$Module_Name/language/french/main.php";
}

if (file_exists($xoopsConfig['root_path'] . "modules/$Module_Name/language/" . $xoopsConfig['language'] . '/categorie.php')) {
    include $xoopsConfig['root_path'] . "modules/$Module_Name/language/" . $xoopsConfig['language'] . '/categorie.php';
} else {
    include $xoopsConfig['root_path'] . "modules/$Module_Name/language/french/categorie.php";
}
if ('camportail' == $xoopsConfig['startpage']) {
    $xoopsOption['show_rblock'] = 1;

    include $xoopsConfig['root_path'] . 'header.php';

    make_cblock();

    echo '<br>';
} else {
    $xoopsOption['show_rblock'] = 0;

    include $xoopsConfig['root_path'] . 'header.php';
}







