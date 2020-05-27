<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」
//　by sakots https://sakots.red/
//--------------------------------------------------

//smarty-3.1.34
require_once(__DIR__.'/libs/Smarty.class.php');
$smarty = new Smarty();

//スクリプトのバージョン
$smarty->assign('ver','v0.10.0');

//設定の読み込み
require(__DIR__."/config.php");
require(__DIR__."/templates/".THEMEDIR."template_ini.php");
//DB接続
//require(__DIR__."/dbconnect.php");

//var_dump($_POST);

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

//スパム無効化関数
function newstring($string) {
	$string = htmlspecialchars($string,ENT_QUOTES,'utf-8');
	$string = str_replace(",","，",$string);
	return $string;
}
//無効化ここまで

try {
	if (file_exists('noe.db') == FALSE) {
		// はじめての実行なら、テーブルを作成
		$db = new PDO("sqlite:noe.db");
		$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
		$sql = "CREATE TABLE tablelog (tid integer primary key autoincrement, created timestamp, modified TIMESTAMP, name VARCHAR(".MAX_NAME."), mail VARCHAR(".MAX_EMAIL."), sub VARCHAR(".MAX_SUB."), com VARCHAR(".MAX_COM."), url VARCHAR(".MAX_URL."), host TEXT, exid TEXT, id TEXT, pwd TEXT, utime INT, picfile TEXT, pchfile TEXT, img_w INT, img_h INT, time TEXT, tree BIGINT, parent INT, invz VARCHAR(1))";
		$dh = $db->query($sql);
		$sql = "CREATE TABLE tabletree (iid integer primary key autoincrement, tid INT, created timestamp, modified TIMESTAMP, name VARCHAR(".MAX_NAME."), mail VARCHAR(".MAX_EMAIL."), sub VARCHAR(".MAX_SUB."), com VARCHAR(".MAX_COM."), url VARCHAR(".MAX_URL."), host TEXT, exid TEXT, id TEXT, pwd TEXT, utime INT, picfile TEXT, pchfile TEXT, img_w INT, img_h INT, time TEXT, tree BIGINT, parent INT, invz VARCHAR(1))";
		$dh = $db->query($sql);
	} else {
		$db = new PDO("sqlite:noe.db");
	}
	$db = null; //db切断
} catch (PDOException $e) {
	echo "DB接続エラー:" .$e->getMessage();
}
if (file_exists(IMG_DIR) == FALSE) {
	mkdir(IMG_DIR,0705);
}
if (file_exists(TEMP_DIR) == FALSE) {
	mkdir(TEMP_DIR,0705);
}

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

//var_dump($_COOKIE);

$pwdc = filter_input(INPUT_COOKIE, 'pwdc');
$usercode = filter_input(INPUT_COOKIE, 'usercode');//nullならuser-codeを発行

//ユーザーip
function get_uip(){
	$userip = getenv("HTTP_CLIENT_IP");
	if(!$userip){
		$userip = getenv("HTTP_X_FORWARDED_FOR");
	} 
	if(!$userip){
		$userip = getenv("REMOTE_ADDR");
	} 
	return $userip;
}

$smarty->assign('usercode',$usercode);

//投稿があればデータベースへ保存する
try {
	$db = new PDO("sqlite:noe.db");
	if (isset($_POST["send"] ) ===  true) {

		if ( $name   === "" ) $name = DEF_NAME;

		if ( $com  === "" ) $com  = DEF_COM;

		if ( $sub  === "" ) $sub  = DEF_SUB;

		$host = get_uip();
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
			chmod( IMG_DIR.$picfile , 0606);
			$picdat = strtr($picfile , 'png', 'dat');
			rename( TEMP_DIR.$picdat, IMG_DIR.$picdat );
			chmod( IMG_DIR.$picdat , 0606);
			$pchfile = strtr($picfile , 'png', 'pch');
			if ( file_exists(TEMP_DIR.$pchfile) == TRUE ) {
				rename( TEMP_DIR.$pchfile, IMG_DIR.$pchfile );
				chmod( IMG_DIR.$pchfile , 0606);
			} else {
				$pchfile = "";
			}
		} else {
			$img_w = 0;
			$img_h = 0;
			$pchfile = "";
		}

		// 値を追加する
		// スレ建ての場合
		if (empty($_POST["modid"])==true) {
			//id生成
			$id = substr(crypt(md5($host.ID_SEED.date("Ymd", $utime)),'id'),-8);
			$sql = "INSERT INTO tablelog (created, modified, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, invz, host) VALUES (datetime('now', 'localtime'), datetime('now', 'localtime'), '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwd', '$id', '$exid', '$tree', '$invz', '$host')";
			$dh = $db->exec($sql);
		} else {
			//レスの場合
			$tid = $_POST["modid"];
			//id生成
			$id = substr(crypt(md5($host.ID_SEED.date("Ymd", $utime)),'id'),-8);
			$sql = "INSERT INTO tabletree (created, modified, tid, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, invz, host) VALUES (datetime('now', 'localtime') , datetime('now', 'localtime') , '$tid', '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwd', '$id', '$exid', '$tree', '$invz', '$host')";
			$dh = $db->exec($sql);
		}
		$smarty->assign('message','書き込みに成功しました。');
	}
	$db = null;
} catch (PDOException $e) {
	echo "DB接続エラー:" .$e->getMessage();
}

//ページング
try {
	$db = new PDO("sqlite:noe.db");
	if (isset($_GET['page']) && is_numeric($_GET['page'])) {
		$page = $_GET['page'];
		$page = max($page,1);
	} else {
		$page = 1;
	}
	$start = PAGE_DEF * ($page - 1);

	//最大何ページあるのか
	$sql = "SELECT COUNT(*) as cnt FROM tablelog WHERE invz=0";
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

	$db = null;
} catch (PDOException $e) {
	echo "DB接続エラー:" .$e->getMessage();
}
//読み込み
//1ページの全スレッド取得
try {
	$db = new PDO("sqlite:noe.db");
	$sql = "SELECT * FROM tablelog WHERE invz=0 ORDER BY tree DESC LIMIT ".$start.",".PAGE_DEF;
	$posts = $db->query($sql);
	$oya = array();
	while ($bbsline = $posts->fetch() ) {
		$oya[] = $bbsline;
	} 
	$smarty->assign('oya',$oya);

	//スレッドの記事を取得
	$sqli = "SELECT * FROM tabletree WHERE invz=0 ORDER BY tree DESC";
	$postsi = $db->query($sqli);
	$ko = array();
	while ($res = $postsi->fetch() ) {
		$ko[] = $res;
	}
	$smarty->assign('ko',$ko);


	$smarty->assign('path',IMG_DIR);


	//$smarty->debugging = true;
	$smarty->display(THEMEDIR.MAINFILE);
	$db = null;
} catch (PDOException $e) {
	echo "DB接続エラー:" .$e->getMessage();
}

//-------------------------

//user-codeの発行
if(!$usercode){//falseなら発行
	$userip = get_uip();
	$usercode = substr(crypt(md5($userip.ID_SEED.date("Ymd", time())),'id'),-12);
	//念の為にエスケープ文字があればアルファベットに変換
	$usercode = strtr($usercode,"!\"#$%&'()+,/:;<=>?@[\\]^`/{|}~","ABCDEFGHIJKLMNOabcdefghijklmn");
}
setcookie("usercode", $usercode, time()+86400*365);//1年間

?>
