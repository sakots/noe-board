<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」v0.0.2
//　by sakots https://sakots.red/
//--------------------------------------------------

//設定の読み込み
require("config.php");
require("template_ini.php");

//Skinny 0.4.1
include_once( "Skinny.php" );
$out = array();
$out["ver"] = "v0.0.2";

$out["btitle"] = $btitle;
$out["home"] = $home;
$out["self"] = $self;
$out["message"] = $message;
$out["pdefw"] = $pdefw;
$out["pdefh"] = $pdefh;

$out["picw"] = $_POST["picw"];
$out["pich"] = $_POST["pich"];
$out["w"] = $_POST["picw"] + 150;
$out["h"] = $_POST["pich"] + 170;

$Skinny->SkinnyDisplay( $paintfile, $out );

?>
