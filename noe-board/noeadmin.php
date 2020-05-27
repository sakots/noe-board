<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」
//　by sakots https://sakots.red/
//--------------------------------------------------

//smarty-3.1.33
require_once(__DIR__.'/libs/Smarty.class.php');
$smarty = new Smarty();

//設定の読み込み
require(__DIR__."/config.php");
require(__DIR__."/templates/".THEMEDIR."template_ini.php");
//DB接続
require(__DIR__."/dbconnect.php");

//スクリプトのバージョン
$smarty->assign('ver','v0.10.0');

$message = "";

$smarty->assign('btitle',TITLE);
$smarty->assign('home',HOME);
$smarty->assign('self',PHP_SELF);
$smarty->assign('message',$message);
$smarty->assign('pdefw',PDEF_W);
$smarty->assign('pdefh',PDEF_H);
$smarty->assign('skindir',THEMEDIR);
$smarty->assign('tver',TEMPLATE_VER);

$smarty->assign('useanime',USE_ANIME);
$smarty->assign('defanime',DEF_ANIME);

//ページング
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
	$page = $_GET['page'];
	$page = max($page,1);
} else {
	$page = 1;
}
$page10 = PAGE_DEF * 10;
$start = $page10 * ($page - 1);

//最大何ページあるのか
$sql = "SELECT COUNT(*) as cnt FROM tablelog WHERE invz=0";
$counts = $db->query("$sql");
$count = $counts->fetch();
$max_page = floor($count["cnt"] / $page10) + 1;
$smarty->assign('max_page',$max_page);

//リンク作成用
$smarty->assign('nowpage',$page);
$p = 1;
$pp = array();
$paging = array();
while ($p <= $max_page) {
	$paging[($p)] = compact('p');
	$pp[] = $paging;
	$p++;
}
$smarty->assign('paging',$paging);
$smarty->assign('pp',$pp);

$smarty->assign('back',$page - 1);

$smarty->assign('next',$page + 1);

//読み込み
if ($_GET["adminpass"] == ADMIN_PASS) {
	$sql = "SELECT * FROM tablelog ORDER BY tree DESC LIMIT ".$start.",".$page10;
	$oya = array();
	$posts = $db->query($sql);
	while ($bbsline = $posts->fetch() ) {
		$oya[] = $bbsline;
	} 
	$smarty->assign('oya',$oya);

	//スレッドの記事を取得
	$sqli = "SELECT * FROM tabletree WHERE invz=0 ORDER BY tree DESC";
	$ko = array();
	$postsi = $db->query($sqli);
	while ($res = $postsi->fetch()){
		$ko[] = $res;
	}
	$smarty->assign('ko',$ko);
	$smarty->display( THEMEDIR.ADMINFILE );
} else {
	echo "エラーです";
}


?>
