<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」v0.9.0
//　by sakots https://sakots.red/
//--------------------------------------------------

//smarty-3.1.33
require_once('libs/Smarty.class.php');
$smarty = new Smarty();

//設定の読み込み
require("config.php");
require("template_ini.php");
//DB接続
require("dbconnect.php");

//スクリプトのバージョン
$smarty->assign('ver','v0.9.0');

//var_dump($_POST);

$message = "";

$smarty->assign('btitle',TITLE);
$smarty->assign('home',HOME);
$smarty->assign('self',PHP_SELF);
$smarty->assign('message',$message);
$smarty->assign('pdefw',PDEF_W);
$smarty->assign('pdefh',PDEF_H);
$smarty->assign('skindir',SKINDIR);
$smarty->assign('tver',TEMPLATE_VER);

$parent = $_GET['res'];
$smarty->assign('parent',$parent);
$resno = $_GET['res'];
$smarty->assign('resno',$resno);

$smarty->assign('base',BASE);

/* オートリンク */
function auto_link($proto){
	$proto = preg_replace("{(https?|ftp|news)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)}","<a href=\"\\1\\2\" target=\"_blank\" rel=\"nofollow noopener noreferrer\">\\1\\2</a>",$proto);
	return $proto;
}

//読み込み
$sql = "SELECT tid,modified,name,sub,com,mail,url,picfile,pchfile FROM ".TABLE." WHERE tid=".$resno." ORDER BY tree DESC";
$posts = $db->query($sql);

$oya = array();
while ($bbsline = $posts->fetch() ) {
	//スレッドの記事を取得
	$sqli = "SELECT * FROM ".TABLETREE." WHERE (invz=0 AND tid=".$resno.") ORDER BY tree DESC";
	$postsi = $db->query($sqli);
	$ko = array();
	while ($res = $postsi->fetch()){
		$ko[] = $res;
		$smarty->assign('ko',$ko);
	}
	$oya[] = $bbsline;
	$smarty->assign('oya',$oya);
}

$smarty->assign('path',IMG_DIR);

$smarty->display( SKINDIR.RESFILE );

?>
