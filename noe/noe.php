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

$out["useanime"] = USE_ANIME;
$out["defanime"] = DEF_ANIME;

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
	$utime = $_SERVER['REQUEST_TIME'];

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
	$sql = "INSERT INTO ".TABLE." SET date=NOW() ,name='$name', sub='$sub', com='$com', mail='$mail', url='$url',picfile='$picfile', pchfile='$pchfile', img_w='$img_w', img_h='$img_h', utime='$utime', parent='$parent', time='$time', pwd='$pwd', exid='$exid', tree='$tree', invz='$invz', host='$host'";
	$dh = $db->exec($sql);
	$out["message"] = $dh ."件の書き込みに成功しました。";
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
$sql = "SELECT * FROM ".TABLE." WHERE invz=0 ORDER BY tree DESC LIMIT ".$start.",".PAGE_DEF;
$posts = $db->query($sql);
while ($out['bbsline'][] = $posts->fetch() ) {
	$out['bbsline'];
} 

$Skinny->SkinnyDisplay( SKINDIR.MAINFILE, $out );
//var_dump($out['bbsline']);

?>
