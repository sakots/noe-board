<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」v0.6.2
//　by sakots https://sakots.red/
//--------------------------------------------------

//Skinny 0.4.1
include_once( "Skinny.php" );
$out = array();
$out["ver"] = "v0.6.2";

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

$out["picw"] = $_POST["picw"];
$out["pich"] = $_POST["pich"];
$out["w"] = $_POST["picw"] + 150;
$out["h"] = $_POST["pich"] + 170;

$out["undo"] = UNDO;
$out["undo_in_mg"] = UNDO_IN_MG;

$out["useanime"] = $_POST["anime"];


$path = realpath("./").'/'.IMG_DIR;
$temppath = realpath("./").'/'.TEMP_DIR;

$out["path"] = IMG_DIR;

$Skinny->SkinnyDisplay( SKINDIR.PAINTFILE, $out );
exit;

?>
