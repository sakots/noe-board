<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」v0.8.4
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
$out["ver"] = "v0.8.4";

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

$out["useanime"] = USE_ANIME;
$out["defanime"] = DEF_ANIME;

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
$sql = "SELECT COUNT(*) as cnt FROM ".TABLE." WHERE invz=0";
$counts = $db->query("$sql");
$count = $counts->fetch();
$max_page = floor($count["cnt"] / $page10) + 1;

//リンク作成用
$out["nowpage"] = $page;
$p = 1;
while ($p <= $max_page) {
	$out["paging"][($p -1)] = compact(p);
	$p++;
}

$out["back"] = $page - 1;
if ($out["back"] == 0) {
	$out["back"] = NULL;
}
$out["next"] = $page + 1;
if ($out["next"] > $max_page) {
	$out["next"] = NULL;
}

//読み込み
if ($_GET["adminpass"] == ADMIN_PASS) {
	$sql = "SELECT * FROM ".TABLE." ORDER BY tree DESC LIMIT ".$start.",".$page10;
	$posts = $db->query($sql);
	while ($out['bbsline'][] = $posts->fetch() ) {
		$out['bbsline'];
	} 
	//スレッドの記事を取得
	$sqli = "SELECT * FROM ".TABLETREE." WHERE invz=0 ORDER BY tree DESC";
	$postsi = $db->query($sqli);
	while ($out['ko'][] = $postsi->fetch()){
		$out['bbsline']['ko'];
	}
	$Skinny->SkinnyDisplay( SKINDIR.ADMINFILE, $out );
} else {
	echo "エラーです";
}



//var_dump($out['bbsline']);

?>
