<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」v0.8.5
//　by sakots https://sakots.red/
//--------------------------------------------------

//Skinny 0.4.1
include_once( "Skinny.php" );
$out = array();
$out["ver"] = "v0.8.5";

//設定の読み込み
require("config.php");
require("template_ini.php");

$message = "";

$out["btitle"] = TITLE;
$out["home"] = HOME;
$out["self"] = PHP_SELF;
$out["message"] = $message;
$out["pdefw"] = PDEF_W;
$out["pdefh"] = PDEF_H;
$out["skindir"] = SKINDIR;
$out["tver"] = TEMPLATE_VER;

$out["picw"] = $_GET["img_w"];
$out["pich"] = $_GET["img_h"];
$out["w"] = $_GET["img_w"];
$out["h"] = $_GET["img_h"] + 26;
$out["pchfile"] = $_GET["pch"];

$out["speed"] = PCH_SPEED;

$path = realpath("./").'/'.IMG_DIR;
$temppath = realpath("./").'/'.TEMP_DIR;

$out["path"] = IMG_DIR;

$Skinny->SkinnyDisplay( SKINDIR.ANIMEFILE, $out );
exit;

?>
