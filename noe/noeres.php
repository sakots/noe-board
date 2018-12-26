<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」v0.6.1
//　by sakots https://sakots.red/
//--------------------------------------------------

//Skinny 0.4.1
include_once( "Skinny.php" );
$out = array();

//設定の読み込み
require("config.php");
require("template_ini.php");
//DB接続
require("dbconnect.php");

//スクリプトのバージョン
$out["ver"] = "v0.6.1";

//var_dump($_POST);

$message = "";

$out["btitle"] = TITLE;
$out["home"] = HOME;
$out["self"] = PHP_SELF;
$out["message"] = $message;
$out["pdefw"] = PDEF_W;
$out["pdefh"] = PDEF_H;
$out["skindir"] = SKINDIR;
$out["tver"] = TEMPLATE_VER;

$out["parent"] = $_GET['res'];
$resno =  $_GET['res'];

$out["base"] = BASE;

//読み込み
$sql = "SELECT id,date,name,sub,com,mail,url,picfile,pchfile FROM ".TABLE." WHERE (invz=0 AND parent=".$resno.") ORDER BY tree DESC";
$posts = $db->query($sql);
while ($out['bbsline'][] = $posts->fetch() ) {
	$out['bbsline'];
}

$Skinny->SkinnyDisplay( SKINDIR.RESFILE, $out );
//var_dump($out['bbsline']);

?>
