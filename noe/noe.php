<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」v0.2.0
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
$out["ver"] = "v0.2.0";

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
$picfile = ( isset( $_POST["picfile"] )  === true ) ? newstring(trim($_POST["picfile"]))  : "";
$tree = ( isset( $_POST["tree"] )  === true ) ? newstring(trim($_POST["tree"]))  : "";
$invz = ( isset( $_POST["invz"] )  === true ) ? newstring(trim($_POST["invz"]))  : "";
$img_w = ( isset( $_POST["img_w"] )  === true ) ? newstring(trim($_POST["img_w"]))  : "";
$img_h = ( isset( $_POST["img_h"] )  === true ) ? newstring(trim($_POST["img_h"]))  : "";
$time = ( isset( $_POST["time"] )  === true ) ? newstring(trim($_POST["time"]))  : "";
$pwd = ( isset( $_POST["pwd"] )  === true ) ? newstring(trim($_POST["pwd"]))  : "";
$pwd = sha1($pwd);
$exid = ( isset( $_POST["exid"] )  === true ) ? newstring(trim($_POST["exid"]))  : "";

//投稿があればデータベースへ保存する
if (isset($_POST["send"] ) ===  true) {

	if ( $name   === "" ) $name = DEF_NAME;

	if ( $com  === "" ) $com  = DEF_COM;

	if ( $sub  === "" ) $sub  = DEF_SUB;

	$host = $_SERVER["REMOTE_ADDR"];
	// 値を追加する
	$sql = "INSERT INTO logs SET date=NOW() ,name='$name', sub='$sub', com='$com', mail='$mail', url='$url',picfile='$picfile', tree='$tree', img_w='$img_w', img_h='$img_h', time='$time', pwd='$pwd', exid='$exid', invz='$invz', host='$host'";
	$dh = $db->exec($sql);
	$out["message"] = $dh ."件の書き込みに成功しました。";

	if ( $picfile == true ) {
		rename( TEMP_DIR.$picfile , IMG_DIR.$picfile );
		chmod( IMG_DIR.$picfile , 0666);
		$picdat = strtr($picfile , png, dat);
		rename( TEMP_DIR.$picdat, IMG_DIR.$picdat );
		chmod( IMG_DIR.$picdat , 0666);
	}
}

//読み込み
$sql = "SELECT id,date,name,sub,com,mail,url,picfile FROM logs ORDER BY id DESC LIMIT 0,".LOG_MAX;
$posts = $db->query($sql);
while ($out['bbsline'][] = $posts->fetch()) {
	$out['bbsline'];
}

$Skinny->SkinnyDisplay( SKINDIR.MAINFILE, $out );
//var_dump($out['bbsline']);

?>
