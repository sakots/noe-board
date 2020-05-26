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

$smarty->assign('picw',$_POST["picw"]);
$smarty->assign('pich',$_POST["pich"]);
$smarty->assign('w',$_POST["picw"] + 150);
$smarty->assign('h',$_POST["pich"] + 170);

$smarty->assign('undo',UNDO);
$smarty->assign('undo_in_mg',UNDO_IN_MG);

$smarty->assign('useanime',$_POST["anime"]);


$path = realpath("./").'/'.IMG_DIR;
$temppath = realpath("./").'/'.TEMP_DIR;

$smarty->assign('path',IMG_DIR);

//$smarty->debugging = true;
$smarty->display( SKINDIR.PAINTFILE );
exit;

?>
