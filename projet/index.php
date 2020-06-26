<?php
/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");

/* Inclusion des classes utilisées dans ce fichier */
require_once("Router.php");
require_once("/users/21605680/private/mysql_config.php");

session_name("monSiteID");
session_start();


define("ID_REF",'id');
define("TITLE_REF",'title');
define("AUTHOR_REF",'author_name');
define("TIME_REF",'temps');
define("INGREDIENTS_REF",'ingredients');
define("PREPARATION_REF",'preparation');
define("IMAGE_REF",'image_url');
define("DATETIME_REF",'creation_date');
define("TAG_REF",'tag');
define("AVIS_REF",'avis');
define("NB_AVIS_REF",'nbAvis');

$router = new Router();
$router->main();
?>
