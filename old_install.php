<?php

//*****************************
// Installation du module camportail
//*****************************

include '../../mainfile.php';

if (file_exists($xoopsConfig['root_path'] . 'modules/camportail/lang_install/' . $xoopsConfig['language'] . '/main.php')) {
    include $xoopsConfig['root_path'] . 'modules/camportail/lang_install/' . $xoopsConfig['language'] . '/main.php';
} else {
    include $xoopsConfig['root_path'] . 'modules/camportail/lang_install/french/main.php';
}

if (!$xoopsUser->isAdmin()) {
    redirect_header('index.php', 1, _NOPERM);

    exit();
}

echo "<html>\n"
    . "<head>\n"
    . "<style type=\"text/css\">
<!--
body {
	background-color:#31349C;
     }
-->\n"
    . "</style>\n"
    . "</head>
\n"
    . "</body>\n"
    . '</html>';

function entete()
{
    echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">'
    . "<HTML>\n"
    . "<HEAD>\n"
    . '<TITLE>' . _TITREINSTALL . "</TITLE>\n"
    . "</HEAD>\n"
    . "<BODY text=\"white\" link=\"green\" alink=\"green\">\n"
    . '<CENTER><H3 align="center">' . _TITREINSTALL . "</H3></CENTER>\n";
}

function pied()
{
    echo "</BODY>\n"
    . "</HTML>\n";
}

function license()
{
    entete();

    echo "<table width=\"100%\" cellpaddind=\"0\" cellspacing=\"0\" border=\"0\" align=\"center\">\n"
    . "<tr>\n"
    . '<td align="center"><b>' . _LICENCE . "</b><br>\n"
    . "<textarea name=\"license\" cols=50 rows=6>\n";

    include 'docs/licence.txt';

    echo '</textarea><br><br>';

    include 'xoops_version.php';

    echo "<img src=\"images/webcam.jpg\" border=\"0\"><br><br>\n";

    echo '<big><b>' . $modversion['name'] . "</b></big>\n";

    echo "<br><u>Version</u><br>\n";

    echo $modversion['version'];

    echo "<br><br>\n";

    echo "<u>Description</u><br>\n";

    echo $modversion['description'];

    echo "<br><br>\n";

    echo '<u>Author</u><br>';

    echo $modversion['author'];

    echo "<br><br>\n";

    echo "<u>Credits</u><br>\n";

    echo $modversion['credits'];

    echo "<br><br>\n";

    echo "<u>License</u><br>\n";

    echo $modversion['license'];

    echo "</center>\n"
    . "<form action=install.php method=\"post\">\n"
    . '<center><br><input type="submit" name="op" value="' . _SUITE . "\"></center>\n"
    . "</form></td>\n"
    . "<td><img src=\"images/esprit_libre.jpg\"></td>\n"
    . "</tr>\n"
    . "</table><br>\n";

    pied();
}

function CreationTable()
{
    global $xoopsDB, $xoopsConfig;

    entete();

    echo '<table border="0" width="70%">
<tr>
<td valign="top"><b>' . _CREATE . '</b></td></tr><tr><td>';

    echo '<td><br><br><i>CREATE TABLE ' . $xoopsDB->prefix('camportail') . "</i>\n";

    $result = $xoopsDB->query('CREATE TABLE ' . $xoopsDB->prefix('camportail') . " (
	camid int(11) NOT NULL auto_increment,
	catid int(5) NOT NULL default '0',
	souscatid int(5) NOT NULL default '0',
	userid int(5) default NULL,
	pays varchar(25) default NULL,
	camname varchar(100) NOT NULL default '',
	descript varchar(250) NOT NULL default '',
	camurl varchar(200) NOT NULL default '',
	camimg varchar(200) default NULL,
	camhits int(11) default '0',
	vote int(11) default '0',
	note int(11) default '0',
	valid int(1) default '0',
	mort int(1) default '0',
	date int(10) NOT NULL default '0',
	PRIMARY KEY  (camid)
        ) ENGINE = ISAM;") or die('<font color="red">&nbsp;-&nbsp;Unable to make ' . $xoopsDB->prefix('camportail') . '</font>');

    echo '<br>';

    echo '<i>CREATE TABLE ' . $xoopsDB->prefix('camportail_cat') . "</i>\n";

    $result = $xoopsDB->query('CREATE TABLE ' . $xoopsDB->prefix('camportail_cat') . " (
        catid int(11) NOT NULL auto_increment,
        catname varchar(100) NOT NULL default '',
        cathits tinyint(5) default '0',
        PRIMARY KEY  (catid)
        ) ENGINE = ISAM;") or die('<font color="red">&nbsp;-&nbsp;Unable to make ' . $xoopsDB->prefix('camportail_cat') . '</font>');

    echo '<br>';

    echo '<i>CREATE TABLE ' . $xoopsDB->prefix('camportail_comment') . '</i>';

    $result = $xoopsDB->query('CREATE TABLE ' . $xoopsDB->prefix('camportail_comment') . " (
	id int(10) NOT NULL default '0',
        uid int(10) NOT NULL default '0',
	nom varchar(25) NOT NULL default '',
	email varchar(25) NOT NULL default '',
	texte text NOT NULL,
	date int(10) default NULL
        ) ENGINE = ISAM;") or die('<font color="red">&nbsp;-&nbsp;Unable to make ' . $xoopsDB->prefix('camportail_comment') . '</font>');

    echo '<br>';

    echo '<i>CREATE TABLE ' . $xoopsDB->prefix('camportail_control') . '</i>';

    $result = $xoopsDB->query('CREATE TABLE ' . $xoopsDB->prefix('camportail_control') . ' (
	camid int(11)  default NULL,
	mode int(1)  default NULL,
	camuser int(5) default NULL,
	ip varchar(60) default NULL,
	date int(14) default NULL
        ) ENGINE = ISAM;') or die('<font color="red">&nbsp;-&nbsp;Unable to make ' . $xoopsDB->prefix('camportail_control') . '</font>');

    echo '<br>';

    echo '<i>CREATE TABLE ' . $xoopsDB->prefix('camportail_souscat') . '</i>';

    $result = $xoopsDB->query('CREATE TABLE ' . $xoopsDB->prefix('camportail_souscat') . " (
	souscatid int(11) NOT NULL auto_increment,
	catid int(11) NOT NULL default '0',
	souscatname varchar(50) NOT NULL default '',
	souscathits int(11) NOT NULL default '0',
	UNIQUE KEY souscatid (souscatid)
        ) ENGINE = ISAM;") or die('<br><center><font color="red"><b>&nbsp;-&nbsp;Echec&nbsp;-</b></font></center>');

    echo '<br>';

    echo "</td>\n"
    . "</tr>\n"
    . '</table>';

    // accès à la suite :)

    echo "<form action=install.php method=\"post\">\n"
    . '<div align="center"><b>' . _COOL . '</b><br>' . _1FINIT . '<a href="' . $xoopsConfig['xoops_url'] . '"> ' . $xoopsConfig['sitename'] . "</a></div><br>\n";

    echo '' . _INST . "<br>\n";

    echo "<ul>\n";

    echo "<INPUT type=\"hidden\" name=\"op\" value=\"Installer\">\n";

    echo '<input type="checkbox" name="bip" value="oui">' . _CATINST . "<br>\n";

    echo '<input type="checkbox" name="top" value="oui">' . _EXINST . "\n";

    echo "</ul>\n"
    . "<table width=\"50%\" align=center>\n"
    . "<tr>\n"
    . '<td align=center><br><INPUT type="submit" value="' . _2INST . "\">\n"
    . "</td>\n"
    . "</tr>\n"
    . "<table>\n"
    . "</form>\n";

    pied();
}

function InsertionChaines()
{
    global $xoopsDB, $xoopsConfig, $bip, $top;

    entete();

    echo '<h2>' . _INSERTTABLE . "</h2>\n";

    if ($bip) {
        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_cat') . " VALUES (3, '" . _INSOLITES . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_cat') . " VALUES (4, '" . _DIVERTISSEMENTS . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_cat') . " VALUES (5, '" . _CULTURE . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_cat') . " VALUES (6, '" . _MEDIAS . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_cat') . " VALUES (7, '" . _TRANSPORTS . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_cat') . " VALUES (8, '" . _SCIENCES . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_cat') . " VALUES (9, '" . _EDUCATION . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_cat') . " VALUES (10, '" . _LIEUX . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_cat') . " VALUES (11, '" . _PERSONNELLES . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_cat') . " VALUES (12, '" . _ENTREPRISES . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (1, 11, '" . _HOMME . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (2, 11, '" . _FEMME . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (3, 4, '" . _BAR . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (4, 4, '" . _CAFE . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (5, 4, '" . _RESTAU . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (6, 5, '" . _MONUM . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (7, 5, '" . _ART . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (8, 12, '" . _ENTREP . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (9, 12, '" . _BUREAU . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (10, 6, '" . _TELE . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (11, 6, '" . _RADIO . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (12, 3, '" . _BIZ . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (13, 3, '" . _AMUS . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (14, 10, '" . _PLAGE . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (15, 10, '" . _MONT . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (16, 10, '" . _VILLE . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (17, 8, '" . _LABO . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (18, 8, '" . _ANIMO . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (19, 7, '" . _TRAFIC . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (20, 7, '" . _AEROP . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (21, 7, '" . _GARE . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (22, 9, '" . _CAMP . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (23, 9, '" . _CLASSE . "', 0)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail_souscat') . " VALUES (24, 12, '" . _COMERCE . "', 0)");
    }

    if ($top) {
        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (6, 5, 6, 0, 'France', 'Paris (arc de triomphe)', 'Un aperçu de trafic automobile parisien. Un grand classique.', 'http://www-compat.tf1.fr/livecam//webcams/cam_paris2.htm', 'etoile.jpg', 17, 10, 87, 1, 0, 1023294537)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (7, 10, 14, 0, 'France', 'Biarritz', 'pas de description disponible pour cette webcam', 'http://www-compat.tf1.fr/livecam//webcams/cam_biarritz.htm', 'biarritz.jpg', 16, 10, 85, 1, 0, 1023294537)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (8, 5, 6, 1, 'France', 'Mont St-Michel', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_mont_stmichel.htm', 'mont_stmichel.jpg', 23, 8, 75, 1, 0, 1023294537)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (4, 10, 16, 0, 'France', 'Bordeaux (la bourse)', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_bordeaux2.htm', 'bordeaux.jpg', 19, 2, 10, 1, 0, 1023294537)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (5, 9, 0, 1, 'France', 'Paris (la tour eiffel)', 'la célèbre tour construite par gustuve eiffel, une des webcam les plus connues au monde.', 'http://www-compat.tf1.fr/livecam//paris.phtml?langue=fr&cam=eiffel', 'eiffel2.jpg', 21, 10, 90, 1, 0, 1023094537)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (9, 10, 14, 0, 'France', 'Saint-Malo', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_stmalo.htm', 'stmalo.jpg', 8, 1, 6, 1, 0, 1023194537)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (10, 10, 16, 0, 'France', 'Toulouse', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_toulouse.htm', 'toulouse.jpg', 7, 1, 5, 1, 0, 1023294137)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (11, 10, 14, 0, 'France', 'Lacanau Nord', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_lacanau_nord.htm', 'lacanaunord.jpg', 0, 2, 20, 1, 0, 1023294327)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (12, 10, 16, 0, 'France', 'Narbonne', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_narbonne2.htm', 'narbonne.jpg', 11, 1, 8, 1, 0, 1023294447)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (13, 10, 14, 0, 'France', 'Deauville', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_deauville.htm', 'deauville.jpg', 4, 2, 17, 1, 0, 1023294507)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (14, 8, 18, 0, 'France', 'Rhinoceros', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_rhino.htm', 'rhino.jpg', 2, 11, 25, 1, 0, 1023294537)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (15, 8, 18, 0, 'Etats Unis', 'Lamas (ranch)', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_alpagas.htm', 'alpagas.jpg', 3, 4, 25, 1, 0, 1023295517)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (16, 8, 18, 0, 'Canada', 'Furet (Ottawa)', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_furet.htm', 'furet.jpg', 2, 5, 35, 1, 0, 1023295527)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (17, 8, 18, 0, 'Quebec', 'Pingouins (Montreal)', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_montreal5.htm', 'pingouins.jpg', 6, 6, 37, 1, 0, 1023295528)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (18, 8, 18, 0, 'Etats Unis', 'Loups', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_loups.htm', 'loups.jpg', 4, 4, 25, 1, 0, 1023295529)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (19, 8, 18, 0, 'Etats Unis', 'Ours blancs', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_ours.htm', 'ours.jpg', 5, 12, 76, 1, 0, 1023295530)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (20, 8, 18, 0, 'France', 'Cheveaux', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_cheval.htm', 'cheval.jpg', 5, 0, 0, 1, 0, 1023295531)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (21, 8, 18, 0, 'Etat Unis', 'Grenouille', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_grenouille.htm', 'grenouille.jpg', 2, 1, 10, 1, 0, 1023295532)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (22, 8, 18, 0, 'Etats Unis', 'Rats', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_rats.htm', 'rats.jpg', 4, 1, 5, 1, 0, 1023295533)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (23, 8, 18, 0, 'Suisse', 'Souris', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_souris.htm', 'souris.jpg', 2, 27, 180, 1, 0, 1023295534)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (25, 10, 14, 0, 'Bali', 'Piscine', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_bali.htm', 'bali.jpg', 10, 4, 39, 1, 0, 1023295535)");

        $result = $xoopsDB->query('INSERT INTO ' . $xoopsDB->prefix('camportail') . " VALUES (24, 12, 24, 0, 'France', 'laverie', 'pas de description disponible pour cette webcam.', 'http://www-compat.tf1.fr/livecam//webcams/cam_laverie.htm', 'laverie.jpg', 20, 1, 3, 1, 0, 1023295536)");
    }

    echo '<b><i>' . _EXEMPLE . "</i></b><br>\n";

    echo "<ul>\n";

    $result = $GLOBALS['xoopsDB']->queryF('SELECT catid, catname FROM ' . $xoopsDB->prefix('camportail_cat') . ' ORDER BY catid');

    while ($ligne = $GLOBALS['xoopsDB']->fetchObject($result)) {
        $catid = (string)$ligne->catid;

        if ($catid) {
            echo "<li>$ligne->catname<br>\n";
        } else {
            echo "XXXXXX\n";
        }

        $sql = $GLOBALS['xoopsDB']->queryF('SELECT souscatname FROM ' . $xoopsDB->prefix('camportail_souscat') . " WHERE catid='$catid'");

        while ($ligne = $GLOBALS['xoopsDB']->fetchObject($sql)) {
            $souscatname = (string)$ligne->souscatname;

            if ($souscatname) {
                echo "$souscatname &nbsp;";
            } else {
                echo 'XXXXXX';
            }
        }

        echo "</li>\n";
    }

    echo "</ul>\n";

    if ($top) {
        echo "<br>\n";

        echo '<b>' . _INSTLINK . "</b>\n";
    }

    echo "<br>\n";

    echo '<center><i>' . _FINISH . "</i></center>\n";

    echo '<br><font color="red"><b>' . _EFFACE . "</b></font>\n";

    echo '<br><br><div align="center"><a href="' . $xoopsConfig['xoops_url'] . '">' . $xoopsConfig['sitename'] . "</a></div><br><br>\n";
}

switch ($op) {
        case 'Suite':
             CreationTable();
        break;
        case 'Installer':
             InsertionChaines();
        break;
        case 'Finish':
             fin();
        break;
        default:
                license();
        break;
}
