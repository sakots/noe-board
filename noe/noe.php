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

$smarty->assign('useanime',USE_ANIME);
$smarty->assign('defanime',DEF_ANIME);

//スパム無効化関数
function newstring($string) {
	if(get_magic_quotes_gpc()){
		$string = stripslashes($string);
	}
	//$string = sqlite_escape_string($string);
	$string = htmlspecialchars($string,ENT_QUOTES,'utf-8');
	$string = str_replace(",","，",$string);
	$string = str_replace(array("\r\n","\n","\r"),"<br>",$string);
	return $string;
}
//無効化ここまで

/* オートリンク */
function auto_link($proto){
	$proto = preg_replace("{(https?|ftp|news)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)}","<a href=\"\\1\\2\" target=\"_blank\" rel=\"nofollow noopener noreferrer\">\\1\\2</a>",$proto);
	return $proto;
}

$message ="";
$sub = ( isset( $_POST["sub"] ) === true ) ? newstring($_POST["sub"]): "";
$name = ( isset( $_POST["name"] ) === true ) ? newstring($_POST["name"]): "";
$url = ( isset( $_POST["url"] )  === true ) ? newstring(trim($_POST["url"]))  : "";
$mail = ( isset( $_POST["mail"] )  === true ) ? newstring(trim($_POST["mail"]))  : "";
$com = ( isset( $_POST["com"] )  === true ) ? newstring(trim($_POST["com"]))  : "";
$parent = ( isset( $_POST["parent"] )  === true ) ? newstring(trim($_POST["parent"]))  : "";
$picfile = ( isset( $_POST["picfile"] )  === true ) ? newstring(trim($_POST["picfile"]))  : "";
$invz = ( isset( $_POST["invz"] )  === true ) ? newstring(trim($_POST["invz"]))  : "";
$img_w = ( isset( $_POST["img_w"] )  === true ) ? newstring(trim($_POST["img_w"]))  : "";
$img_h = ( isset( $_POST["img_h"] )  === true ) ? newstring(trim($_POST["img_h"]))  : "";
$time = ( isset( $_POST["time"] )  === true ) ? newstring(trim($_POST["time"]))  : "";
$pwd = ( isset( $_POST["pwd"] )  === true ) ? newstring(trim($_POST["pwd"]))  : "";
$pwd = password_hash($pwd,PASSWORD_DEFAULT);
$exid = ( isset( $_POST["exid"] )  === true ) ? newstring(trim($_POST["exid"]))  : "";

//投稿があればデータベースへ保存する
if (isset($_POST["send"] ) ===  true) {

	if ( $name   === "" ) $name = DEF_NAME;

	if ( $com  === "" ) $com  = DEF_COM;

	if ( $sub  === "" ) $sub  = DEF_SUB;

	$host = $_SERVER["REMOTE_ADDR"];
	$utime = time();

	if ($parent == 0 ) {
		$parent = $utime;
	}

	$tree = ($parent * 1000000000) - $utime;

	//画像ファイルとか処理
	if ( $picfile == true ) {
		$imagesize = getimagesize(TEMP_DIR.$picfile);
		$img_w = $imagesize[0];
		$img_h = $imagesize[1];
		rename( TEMP_DIR.$picfile , IMG_DIR.$picfile );
		chmod( IMG_DIR.$picfile , 0666);
		$picdat = strtr($picfile , png, dat);
		rename( TEMP_DIR.$picdat, IMG_DIR.$picdat );
		chmod( IMG_DIR.$picdat , 0666);
		$pchfile = strtr($picfile , png, pch);
		if ( file_exists(TEMP_DIR.$pchfile) == TRUE ) {
			rename( TEMP_DIR.$pchfile, IMG_DIR.$pchfile );
			chmod( IMG_DIR.$pchfile , 0666);
		} else {
			$pchfile = "";
		}
	} else {
		$img_w = 0;
		$img_h = 0;
	}

	// 値を追加する
	// スレ建ての場合
	if ($_POST["modid"] == "") {
		$sql = "INSERT INTO ".TABLE." SET created=NOW() ,modified=NOW() ,name='$name', sub='$sub', com='$com', mail='$mail', url='$url',picfile='$picfile', pchfile='$pchfile', img_w='$img_w', img_h='$img_h', utime='$utime', parent='$parent', time='$time', pwd='$pwd', exid='$exid', tree='$tree', invz='$invz', host='$host'";
		$dh = $db->exec($sql);
	} else {
		//レスの場合
		$tid = $_POST["modid"];
		$sql = "INSERT INTO ".TABLETREE." SET created=NOW() , modified=NOW() , tid='$tid', name='$name', sub='$sub', com='$com', mail='$mail', url='$url',picfile='$picfile', pchfile='$pchfile', img_w='$img_w', img_h='$img_h', utime='$utime', parent='$parent', time='$time', pwd='$pwd', exid='$exid', tree='$tree', invz='$invz', host='$host'";
		$dh = $db->exec($sql);
	}
	$smarty->assign('message','書き込みに成功しました。');
}

//ページング
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
	$page = $_GET['page'];
	$page = max($page,1);
} else {
	$page = 1;
}
$start = PAGE_DEF * ($page - 1);

//最大何ページあるのか
$sql = "SELECT COUNT(*) as cnt FROM ".TABLE." WHERE invz=0";
$counts = $db->query("$sql");
$count = $counts->fetch();
$max_page = floor($count["cnt"] / PAGE_DEF) + 1;
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
//1ページの全スレッド取得
$sql = "SELECT * FROM ".TABLE." WHERE invz=0 ORDER BY tree DESC LIMIT ".$start.",".PAGE_DEF;
$posts = $db->query($sql);
$oya = array();
while ($bbsline = $posts->fetch() ) {
	$oya[] = $bbsline;
} 
$smarty->assign('oya',$oya);

//スレッドの記事を取得
$sqli = "SELECT * FROM ".TABLETREE." WHERE invz=0 ORDER BY tree DESC";
$postsi = $db->query($sqli);
$ko = array();
while ($res = $postsi->fetch() ) {
	$ko[] = $res;
}
$smarty->assign('ko',$ko);


$smarty->assign('path',IMG_DIR);


//$smarty->debugging = true;
$smarty->display(SKINDIR.MAINFILE);
?>
