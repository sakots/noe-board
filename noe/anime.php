<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」v0.9.0
//　by sakots https://sakots.red/
//--------------------------------------------------

//smarty-3.1.33
require_once('libs/Smarty.class.php');
$smarty = new Smarty();
$smarty->assign('ver','v0.9.0');

//設定の読み込み
require("config.php");
require("template_ini.php");

$message = "";

$smarty->assign('btitle',TITLE);
$smarty->assign('home',HOME);
$smarty->assign('self',PHP_SELF);
$smarty->assign('message',$message);
$smarty->assign('pdefw',PDEF_W);
$smarty->assign('pdefh',PDEF_H);
$smarty->assign('skindir',SKINDIR);
$smarty->assign('tver',TEMPLATE_VER);

$smarty->assign('picw',$_GET["img_w"]);
$smarty->assign('pich',$_GET["img_h"]);
$smarty->assign('w',$_GET["img_w"]);
$smarty->assign('h',$_GET["img_h"] + 26);
$smarty->assign('pchfile',$_GET["pch"]);

$smarty->assign('speed',PCH_SPEED);

$path = realpath("./").'/'.IMG_DIR;
$temppath = realpath("./").'/'.TEMP_DIR;

$smarty->assign('path',IMG_DIR);

$smarty->display( SKINDIR.ANIMEFILE );
exit;

?>
