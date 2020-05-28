<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」
//　by sakots https://sakots.red/
//--------------------------------------------------

//smarty-3.1.34
require_once(__DIR__.'/libs/Smarty.class.php');
$smarty = new Smarty();

//スクリプトのバージョン
$smarty->assign('ver','v0.13.1');

//設定の読み込み
require(__DIR__."/config.php");
require(__DIR__."/templates/".THEMEDIR."template_ini.php");
//DB接続
//require(__DIR__."/dbconnect.php");

//var_dump($_POST);

$message = "";
$self = PHP_SELF;

$smarty->assign('base',BASE);
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

/* オートリンク */
function auto_link($proto){
	$proto = preg_replace("{(https?|ftp|news)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)}","<a href=\"\\1\\2\" target=\"_blank\" rel=\"nofollow noopener noreferrer\">\\1\\2</a>",$proto);
	return $proto;
}

$mode = newstring(filter_input(INPUT_POST, 'mode'));

//var_dump($_GET);
if(filter_input(INPUT_GET, 'mode')==="anime"){
	$pch = newstring(filter_input(INPUT_GET, 'pch'));
	$shi = filter_input(INPUT_GET, 'shi',FILTER_VALIDATE_INT);
	$mode = "anime";
}
if(filter_input(INPUT_GET, 'mode')==="continue"){
	$no = filter_input(INPUT_GET, 'no',FILTER_VALIDATE_INT);
	$mode = "continue";
}
if(filter_input(INPUT_GET, 'mode')==="admin"){
	$mode = "admin";
}
if(filter_input(INPUT_GET, 'mode')==="admin_in"){
	$mode = "admin_in";
}
if(filter_input(INPUT_GET, 'mode')==="piccom"){
	$stime = filter_input(INPUT_GET, 'stime',FILTER_VALIDATE_INT);
	$resto = filter_input(INPUT_GET, 'resto',FILTER_VALIDATE_INT);
	$mode = "piccom";
}
if(filter_input(INPUT_GET, 'mode')==="picrep"){
	$no = filter_input(INPUT_GET, 'no',FILTER_VALIDATE_INT);
	$pwd = newstring(filter_input(INPUT_GET, 'pwd'));
	$repcode = newstring(filter_input(INPUT_GET, 'repcode'));
	$stime = filter_input(INPUT_GET, 'stime',FILTER_VALIDATE_INT);
	$mode = "picrep";
}
if(filter_input(INPUT_GET, 'mode')==="regist"){
	$mode = "regist";
}
if(filter_input(INPUT_GET, 'mode')==="res"){
	$mode = "res";
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
/* 記事書き込み */
function regist() {
	global $name,$com,$sub,$parent,$picfile,$mail,$url,$time,$pwd,$exid,$invz;
	global $smarty;

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

			//age_sageカウント 兼 レス数カウント
			$sql = "SELECT COUNT(*) as cnt FROM tabletree WHERE invz=0";
			$counts = $db->query("$sql");
			$count = $counts->fetch();
			$age = $count["cnt"];

			//スレッド数カウント
			$sql = "SELECT COUNT(*) as cnti FROM tablelog WHERE invz=0";
			$countsi = $db->query("$sql");
			$counti = $countsi->fetch();
			$logt = $counti["cnti"];

			// 値を追加する
			// スレ建ての場合
			if (empty($_POST["modid"])==true && $logt <= LOG_MAX_T) {
				//id生成
				$id = substr(crypt(md5($host.ID_SEED.date("Ymd", $utime)),'id'),-8);
				$sql = "INSERT INTO tablelog (created, modified, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, age, invz, host) VALUES (datetime('now', 'localtime'), datetime('now', 'localtime'), '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwd', '$id', '$exid', '$tree', '$age', '$invz', '$host')";
				$db = $db->exec($sql);
			} elseif(empty($_POST["modid"])==true && $logt > LOG_MAX_T) {
				//id生成
				$id = substr(crypt(md5($host.ID_SEED.date("Ymd", $utime)),'id'),-8);
				$sql = "INSERT INTO tablelog (created, modified, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, age, invz, host) VALUES (datetime('now', 'localtime'), datetime('now', 'localtime'), '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwd', '$id', '$exid', '$tree', '$age', '$invz', '$host'); DELETE FROM tablelog WHERE not exists( select * from tablelog as tb where tablelog.ID > tb.ID) LIMIT 1";
				$db = $db->exec($sql);
			} elseif(empty($_POST["modid"])!=true && strpos($mail,'sage')!==false ) {
				//レスの場合でメール欄にsageが含まれる
				$tid = $_POST["modid"];
				//id生成
				$id = substr(crypt(md5($host.ID_SEED.date("Ymd", $utime)),'id'),-8);
				if ($age <= LOG_MAX_R) {
					$sql = "INSERT INTO tabletree (created, modified, tid, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, invz, host) VALUES (datetime('now', 'localtime') , datetime('now', 'localtime') , '$tid', '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwd', '$id', '$exid', '$tree', '$invz', '$host')";
					$db = $db->exec($sql);
				} else {
					$sql = "INSERT INTO tabletree (created, modified, tid, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, invz, host) VALUES (datetime('now', 'localtime') , datetime('now', 'localtime') , '$tid', '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwd', '$id', '$exid', '$tree', '$invz', '$host'); DELETE FROM tabletree WHERE not exists( select * from tabletree as tb where tabletree.ID > tb.ID) LIMIT 1";
					$db = $db->exec($sql);
				}
			} else {
				//レスの場合でメール欄にsageが含まれない
				$tid = $_POST["modid"];
				//id生成
				$id = substr(crypt(md5($host.ID_SEED.date("Ymd", $utime)),'id'),-8);
				$nage = $age +1;
				if ($age <= LOG_MAX_R) {
					$sql = "INSERT INTO tabletree (created, modified, tid, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, invz, host) VALUES (datetime('now', 'localtime') , datetime('now', 'localtime') , '$tid', '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwd', '$id', '$exid', '$tree', '$invz', '$host'); UPDATE tablelog set age = '$nage' where tid = '$tid'";
					$db = $db->exec($sql);
				} else {
					$sql = "INSERT INTO tabletree (created, modified, tid, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, invz, host) VALUES (datetime('now', 'localtime') , datetime('now', 'localtime') , '$tid', '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwd', '$id', '$exid', '$tree', '$invz', '$host'); UPDATE tablelog set age = '$nage' where tid = '$tid'; DELETE FROM tabletree WHERE not exists( select * from tabletree as tb where tabletree.ID > tb.ID) LIMIT 1";
					$db = $db->exec($sql);
				}
			}
			$smarty->assign('message','書き込みに成功しました。');
			$db = null;
		}
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	def();
}

//通常時
function def() {
	global $smarty;

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
		$sql = "SELECT * FROM tablelog WHERE invz=0 ORDER BY age DESC, tree DESC LIMIT ".$start.",".PAGE_DEF;
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
}

//レス画面

function res(){
	global $resno;
	global $smarty;
	$resno = $_GET["res"];
	$smarty->assign('resno',$resno);
	try {
		$db = new PDO("sqlite:noe.db");
		$sql = "SELECT tid,modified,name,id,sub,com,mail,url,picfile,pchfile FROM tablelog WHERE tid=".$resno." ORDER BY tree DESC";
		$posts = $db->query($sql);
	
		$oya = array();
		while ($bbsline = $posts->fetch() ) {
			//スレッドの記事を取得
			$sqli = "SELECT * FROM tabletree WHERE (invz=0 AND tid=".$resno.") ORDER BY tree DESC";
			$postsi = $db->query($sqli);
			$ko = array();
			while ($res = $postsi->fetch()){
				$ko[] = $res;
				$smarty->assign('ko',$ko);
			}
			$oya[] = $bbsline;
			$smarty->assign('oya',$oya);
		}
		$db = null;
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	
	$smarty->assign('path',IMG_DIR);
	
	$smarty->display( THEMEDIR.RESFILE );
}

//ペイント画面

function paintform(){
	global $message,$usercode;
	global $smarty;

	if ($_POST["useneo"]){$smarty->assign('useneo',true);} //NEOを使う

	$smarty->assign('mode','piccom');

	$smarty->assign('btitle',TITLE);
	$smarty->assign('home',HOME);
	$smarty->assign('self',PHP_SELF);
	$smarty->assign('message',$message);
	$smarty->assign('pdefw',PDEF_W);
	$smarty->assign('pdefh',PDEF_H);
	$smarty->assign('skindir',THEMEDIR);
	$smarty->assign('tver',TEMPLATE_VER);

	$smarty->assign('picw',$_POST["picw"]);
	$smarty->assign('pich',$_POST["pich"]);
	$smarty->assign('w',$_POST["picw"] + 150);
	$smarty->assign('h',$_POST["pich"] + 170);

	$smarty->assign('undo',UNDO);
	$smarty->assign('undo_in_mg',UNDO_IN_MG);

	$smarty->assign('useanime',USE_ANIME);

	$smarty->assign('path',IMG_DIR);

	$smarty->assign('usercode',$usercode);
	$smarty->assign('stime',time());

	$userip = get_uip();

	$smarty->display( THEMEDIR.PAINTFILE );
}

//アニメ再生

function openpch($pch,$sp="") {
	global $shi;
	global $smarty;
	$message = "";

	$pch = $_GET["pch"];
	$pch = str_replace( strrchr($pch,"."), "", $pch); //拡張子除去
	$picfile = IMG_DIR.$pch.".png";
	if($shi==1){
		$pchfile = PCH_DIR.$pch.'.spch';
	}else{
		$pchfile = PCH_DIR.$pch.'.pch';
	}
	if(is_file($pchfile)){//動画が無い時は処理しない
		$datasize = filesize($pchfile);
		$size = getimagesize($picfile);
		if(!$sp) $sp = PCH_SPEED;
		$picw = $size[0];
		$pich = $size[1];
		$w = $picw;
		$h = $pich + 26;
		if($w < 200){$w = 200;}
		if($h < 226){$h = 226;}
	}else{
		$w=$h=$picw=$pich=$datasize="";
	}

	$smarty->assign('btitle',TITLE);
	$smarty->assign('home',HOME);
	$smarty->assign('self',PHP_SELF);
	$smarty->assign('message',$message);
	$smarty->assign('pdefw',PDEF_W);
	$smarty->assign('pdefh',PDEF_H);
	$smarty->assign('skindir',THEMEDIR);
	$smarty->assign('tver',TEMPLATE_VER);

	$smarty->assign('picw',$picw);
	$smarty->assign('pich',$pich);
	$smarty->assign('w',$w);
	$smarty->assign('h',$h);
	$smarty->assign('pchfile','./'.$pchfile);
	$smarty->assign('datasize',$datasize);

	$smarty->assign('speed',PCH_SPEED);

	$smarty->assign('path',IMG_DIR);

	$smarty->display( THEMEDIR.ANIMEFILE );
}

//お絵かき投稿
function paintcom(){
	global $usercode;
	global $smarty;

	$smarty->assign('btitle',TITLE);
	$smarty->assign('home',HOME);
	$smarty->assign('self',PHP_SELF);
	$message = "";
	$smarty->assign('message',$message);
	$smarty->assign('pdefw',PDEF_W);
	$smarty->assign('pdefh',PDEF_H);
	$smarty->assign('skindir',THEMEDIR);
	$smarty->assign('tver',TEMPLATE_VER);

	$smarty->assign('parent',$_SERVER['REQUEST_TIME']);

	$smarty->assign('usercode',$usercode);

	//----------

	//var_dump($_POST);

	//テンポラリ画像リスト作成
	$tmplist = array();
	$handle = @opendir(TEMP_DIR);
	while ($file = readdir($handle)) {
		if(!is_dir($file) && preg_match("/\.(dat)$/i",$file)) {
			$fp = fopen(TEMP_DIR.$file, "r");
			$userdata = fread($fp, 1024);
			fclose($fp);
			list($uip,$uhost,$uagent,$imgext,$ucode,) = explode("\t", rtrim($userdata));
			$file_name = preg_replace("/\.(dat)$/i","",$file);
			if(@file_exists(TEMP_DIR.$file_name.$imgext)) //画像があればリストに追加
				$tmplist[] = $ucode."\t".$uip."\t".$file_name.$imgext;
		}
	}
	closedir($handle);
	$tmp = array();
	if(count($tmplist)!=0){
		//user-codeでチェック
		foreach($tmplist as $tmpimg){
			list($ucode,$uip,$ufilename) = explode("\t", $tmpimg);
			if($ucode == $usercode){
				$tmp[] = $ufilename;
			}
		}
		//user-codeでhitしなければIPで再チェック
		if(count($tmp)==0){
			$userip = getenv("HTTP_CLIENT_IP");
			if(!$userip) $userip = getenv("HTTP_X_FORWARDED_FOR");
			if(!$userip) $userip = getenv("REMOTE_ADDR");
			foreach($tmplist as $tmpimg){
				list($ucode,$uip,$ufilename) = explode("\t", $tmpimg);
				if(!IP_CHECK || $uip == $userip)
					$tmp[] = $ufilename;
			}
		}
	}

	$post_mode = true;
	$regist = true;
	if(IP_CHECK) $ipcheck = true;
	if(count($tmp)==0){
		$notmp = true;
		$pictmp = 1;
	}else{
		$pictmp = 2;
		sort($tmp);
		reset($tmp);
		$temp = array();
		foreach($tmp as $tmpfile){
			$src = TEMP_DIR.$tmpfile;
			$srcname = $tmpfile;
			$date = gmdate("Y/m/d H:i", filemtime($src)+9*60*60);
			$temp[] = compact('src','srcname','date');
		}
		$smarty->assign('temp', $temp);
	}

	$tmp2 = array();
	$smarty->assign('tmp',$tmp2);

	$smarty->assign('path',IMG_DIR);

	//$smarty->debugging = true;
	$smarty->display( THEMEDIR.PICFILE );
}

//削除くん

function delmode(){
	global $admin_pass;
	global $smarty;
	$delno = $_POST["delno"];
	$delt = $_POST["delt"]; //0親1レス削除

	if ($delt == 0) {
		$deltable = 'tablelog';
		$id = "tid";
	} else {
		$deltable = 'tabletree';
		$id = "iid";
	}
	//記事呼び出し
	try {
		$db = new PDO("sqlite:noe.db");
		$sql ="SELECT * FROM ".$deltable." WHERE ".$id."=".$delno;
		$msgs = $db->prepare($sql);
		$msgs->execute(array($delno));
		$msg = $msgs->fetch();

		if (password_verify($_POST["pwd"],$msg['pwd']) == true) {
			$sql = "DELETE FROM ".$deltable." WHERE ".$id."=".$delno;
			$del = $db->prepare($sql);
			$del->execute(array($delno));
		} elseif ($admin_pass == $_POST["pwd"] && $_POST["admindel"] == 1) {
			$sql = "DELETE FROM ".$deltable." WHERE ".$id."=".$delno;
			$del = $db->prepare($sql);
			$del->execute(array($delno));
		} elseif ($admin_pass == $_POST["pwd"] && $_POST["admindel"] != 1) {
			$sql = "UPDATE ".$deltable." SET invz=1 WHERE ".$id."=".$delno;
			$del = $db->exec($sql);
		} else {
			$smarty->assign('message','パスワードまたは記事番号が違います。');
			exit();
		}
		$db = null; //db切断 
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	//header('Location:'.PHP_SELF);
}

//管理モードin
function admin_in() {
	global $self;
	echo '<!DOCTYPE html>'."\n".'<head><meta charset="utf-8"><title>管理モードin</title></head>';
	echo '<body><h1>管理モードin</h1><form action="'.$self.'?mode=admin" method="post"><input type="hidden" name="admin" value="admin"><input class="form" type="password" name="adminpass" size="8">
	<input class="button" type="submit" value="SUBMIT"></form></body>';
}

//管理モード
function admin() {
	global $admin_pass;
	global $smarty;
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
	//記事呼び出しから
	try {
		$db = new PDO("sqlite:noe.db");
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
		$adminpass = ( isset( $_POST["adminpass"] ) === true ) ? newstring($_POST["adminpass"]): "";
		if ($adminpass == $admin_pass) {
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
			echo "管理パスを入力してください";
		}
		$db = null; //db切断
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
}


//初期設定

function init(){
	try {
		if (!file_exists('noe.db')) {
			// はじめての実行なら、テーブルを作成
			$db = new PDO("sqlite:noe.db");
			$db->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  
			$sql = "CREATE TABLE tablelog (tid integer primary key autoincrement, created timestamp, modified TIMESTAMP, name VARCHAR(".MAX_NAME."), mail VARCHAR(".MAX_EMAIL."), sub VARCHAR(".MAX_SUB."), com VARCHAR(".MAX_COM."), url VARCHAR(".MAX_URL."), host TEXT, exid TEXT, id TEXT, pwd TEXT, utime INT, picfile TEXT, pchfile TEXT, img_w INT, img_h INT, time TEXT, tree BIGINT, parent INT, age INT, invz VARCHAR(1))";
			$db = $db->query($sql);
			$db = null; //db切断
			$db = new PDO("sqlite:noe.db");
			$sql = "CREATE TABLE tabletree (iid integer primary key autoincrement, tid INT, created timestamp, modified TIMESTAMP, name VARCHAR(".MAX_NAME."), mail VARCHAR(".MAX_EMAIL."), sub VARCHAR(".MAX_SUB."), com VARCHAR(".MAX_COM."), url VARCHAR(".MAX_URL."), host TEXT, exid TEXT, id TEXT, pwd TEXT, utime INT, picfile TEXT, pchfile TEXT, img_w INT, img_h INT, time TEXT, tree BIGINT, parent INT, invz VARCHAR(1))";
			$db = $db->query($sql);
			$db = null; //db切断
		} else {
			$db = new PDO("sqlite:noe.db");
			$db = null; //db切断
		}
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	$err='';
	if(!is_writable(realpath("./")))error("カレントディレクトリに書けません<br>");
	if(!is_dir(realpath(IMG_DIR))){
		mkdir(IMG_DIR,0707);chmod(IMG_DIR,0707);
	}
	if(!is_dir(realpath(IMG_DIR)))$err.=IMG_DIR."がありません<br>";
	if(!is_writable(realpath(IMG_DIR)))$err.=IMG_DIR."を書けません<br>";
	if(!is_readable(realpath(IMG_DIR)))$err.=IMG_DIR."を読めません<br>";

	if(USE_PAINT){
	if(!is_dir(realpath(TEMP_DIR))){
		mkdir(TEMP_DIR,0707);chmod(TEMP_DIR,0707);
	}
		if(!is_dir(realpath(TEMP_DIR)))$err.=TEMP_DIR."がありません<br>";
		if(!is_writable(realpath(TEMP_DIR)))$err.=TEMP_DIR."を書けません<br>";
		if(!is_readable(realpath(TEMP_DIR)))$err.=TEMP_DIR."を読めません<br>";
	}
	if($err)error($err);
}

/* テンポラリ内のゴミ除去 */
function deltemp(){
	$handle = opendir(TEMP_DIR);
	while ($file = readdir($handle)) {
		if(!is_dir($file)) {
			$lapse = time() - filemtime(TEMP_DIR.$file);
			if($lapse > (TEMP_LIMIT*24*3600)){
				unlink(TEMP_DIR.$file);
			}
			//pchアップロードペイントファイル削除
			if(preg_match("/\A(pchup-.*-tmp\.s?pch)\z/i",$file)) {
				$lapse = time() - filemtime(TEMP_DIR.$file);
				if($lapse > (300)){//5分
					unlink(TEMP_DIR.$file);
				}
			}
		}
	}
	closedir($handle);
}

/*-----------Main-------------*/
init();		//←初期設定後は不要なので削除可
deltemp();

//user-codeの発行
if(!$usercode){//falseなら発行
	$userip = get_uip();
	$usercode = substr(crypt(md5($userip.ID_SEED.date("Ymd", time())),'id'),-12);
	//念の為にエスケープ文字があればアルファベットに変換
	$usercode = strtr($usercode,"!\"#$%&'()+,/:;<=>?@[\\]^`/{|}~","ABCDEFGHIJKLMNOabcdefghijklmn");
}
setcookie("usercode", $usercode, time()+86400*365);//1年間

/*-----------mode-------------*/

switch($mode){
	case 'regist':
		regist();
		break;
	case 'res':
		res();
		break;
	case 'paint':
		$palette = "";
		paintform($palette);
		break;
	case 'piccom':
		paintcom();
		break;
	case 'anime':
		if(!isset($sp)){$sp="";}
		openpch($pch,$sp);
		break;
	case 'del':
		delmode();
		break;
	case 'admin_in':
		admin_in();
		break;
	case 'admin':
		admin();
		break;
	default:
		def();
}

?>
