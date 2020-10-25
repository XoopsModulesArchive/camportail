#Base de données xoop sur le serveur  localhost
# phpMyAdmin MySQL-Dump
# version 2.2.6
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# Serveur: localhost
# Généré le : Mercredi 19 Juin 2002 à 17:21
# Version du serveur: 3.23.47
# Version de PHP: 4.1.2
# Base de données: `xoops`
# -------------------------------------------------------- #
# Structure de la table `camportail`
CREATE TABLE camportail (
    camid     INT(11) AUTO_INCREMENT,
    catid     INT(5)       NOT NULL DEFAULT '0',
    souscatid INT(5)       NOT NULL DEFAULT '0',
    userid    INT(5)                DEFAULT NULL,
    pays      VARCHAR(25)           DEFAULT NULL,
    camname   VARCHAR(100) NOT NULL DEFAULT '',
    descript  VARCHAR(250) NOT NULL DEFAULT '',
    camurl    VARCHAR(200) NOT NULL DEFAULT '',
    camimg    VARCHAR(200)          DEFAULT NULL,
    camhits   INT(11)               DEFAULT '0',
    vote      INT(11)               DEFAULT '0',
    note      INT(11)               DEFAULT '0',
    valid     INT(1)                DEFAULT '0',
    mort      INT(1)                DEFAULT '0',
    date      INT(10)      NOT NULL DEFAULT '0',
    PRIMARY KEY (camid)
)
    ENGINE = ISAM;
# -------------------------------------------------------- #
# Structure de la table `camportail_cat`
CREATE TABLE camportail_cat (
    catid   INT(11)      NOT NULL AUTO_INCREMENT,
    catname VARCHAR(100) NOT NULL DEFAULT '',
    cathits TINYINT(5)            DEFAULT '0',
    PRIMARY KEY (catid)
)
    ENGINE = ISAM;
# -------------------------------------------------------- #
# Structure de la table `camportail_comment`
CREATE TABLE camportail_comment (
    id    INT(10)     NOT NULL DEFAULT '0',
    uid   INT(10)     NOT NULL DEFAULT '0',
    nom   VARCHAR(25) NOT NULL DEFAULT '',
    email VARCHAR(25) NOT NULL DEFAULT '',
    texte TEXT        NOT NULL,
    date  INT(10)              DEFAULT NULL
)
    ENGINE = ISAM;
# -------------------------------------------------------- #
# Structure de la table `camportail_control`
CREATE TABLE camportail_control (
    camid   INT(11)     DEFAULT NULL,
    mode    INT(1)      DEFAULT NULL,
    camuser INT(5)      DEFAULT NULL,
    ip      VARCHAR(60) DEFAULT NULL,
    date    INT(14)     DEFAULT NULL
)
    ENGINE = ISAM;
# -------------------------------------------------------- #
# Structure de la table `camportail_souscat`
CREATE TABLE camportail_souscat (
    souscatid   INT(11)     NOT NULL AUTO_INCREMENT,
    catid       INT(11)     NOT NULL DEFAULT '0',
    souscatname VARCHAR(50) NOT NULL DEFAULT '',
    souscathits INT(11)     NOT NULL DEFAULT '0',
    UNIQUE KEY souscatid (souscatid)
)
    ENGINE = ISAM;
