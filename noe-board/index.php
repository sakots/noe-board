<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」
//　by sakots https://sakots.red/
//--------------------------------------------------

//スクリプトのバージョン
define('NOE_VER','v0.29.2'); //lot.200608.3

//smarty-3.1.34
require_once(__DIR__.'/libs/Smarty.class.php');
$smarty = new Smarty();

//設定の読み込み
require(__DIR__."/config.php");
require(__DIR__."/templates/".THEMEDIR."template_ini.php");

//var_dump($_POST);

$message = "";
$self = PHP_SELF;

$smarty->assign('ver',NOE_VER);
$smarty->assign('base',BASE);
$smarty->assign('btitle',TITLE);
$smarty->assign('home',HOME);
$smarty->assign('self',PHP_SELF);
$smarty->assign('message',$message);
$smarty->assign('pdefw',PDEF_W);
$smarty->assign('pdefh',PDEF_H);
$smarty->assign('pmaxw',PMAX_W);
$smarty->assign('pmaxh',PMAX_H);
$smarty->assign('skindir',THEMEDIR);
$smarty->assign('tver',TEMPLATE_VER);

$smarty->assign('dispid',DISP_ID);
$smarty->assign('updatemark',UPDATE_MARK);
$smarty->assign('use_resub',USE_RESUB);

$smarty->assign('useanime',USE_ANIME);
$smarty->assign('defanime',DEF_ANIME);
$smarty->assign('use_continue',USE_CONTINUE);

$smarty->assign('use_name',USE_NAME);
$smarty->assign('use_com',USE_COM);
$smarty->assign('use_sub',USE_SUB);

$smarty->assign('dptime',DSP_PAINTTIME);

$smarty->assign('addinfo',$addinfo);

$smarty->assign('share_button',SHARE_BUTTON);

$path = realpath("./").'/'.IMG_DIR;
$temppath = realpath("./").'/'.TEMP_DIR;

//指定した日数を過ぎたスレッドのフォームを閉じる　→def()へ
if(!defined('ELAPSED_DAYS')){//config.phpで未定義なら0
	define('ELAPSED_DAYS','0');
}

//描画時間表示するときに「秘密」にできる設定を　使う:1 使わない:0
if(!defined('SEC_PAINTTIME')){
	define('SEC_PAINTTIME', '1'); //configで未定義なら1
}
if(!defined('PTIME_SEC')){
	define('PTIME_SEC', '秘密'); //configで未定義なら「秘密」
}
if(!defined('USE_HASHTAG')){
	define('USE_HASHTAG', '1'); //configで未定義なら使う
}

$smarty->assign('sptime',SEC_PAINTTIME);
$smarty->assign('use_hashtag',USE_HASHTAG);

//ペイント画面の$pwdの暗号化
if(!defined('CRYPT_PASS')){//config.phpで未定義なら初期値が入る
	define('CRYPT_PASS','qRyFfhV6nyUggSb');//暗号鍵初期値
	}
define('CRYPT_METHOD','aes-128-cbc');
define('CRYPT_IV','T3pkYxNyjN7Wz3pu');//半角英数16文字
	

//スパム無効化関数
function newstring($string) {
	$string = htmlspecialchars($string,ENT_QUOTES,'utf-8');
	$string = str_replace(",","，",$string);
	return $string;
}
//無効化ここまで

/* オートリンク */
function auto_link($proto){
	if(!(stripos($proto,"script")!==false)){//scriptがなければ続行
	$proto = preg_replace("{(https?|ftp)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)}","<a href=\"\\1\\2\" target=\"_blank\" rel=\"nofollow noopener noreferrer\">\\1\\2</a>",$proto);
	return $proto;
	}else{
	return $proto;
	}
}

/* ハッシュタグリンク */
function hashtag_link($hashtag) {
	$self = PHP_SELF;
	$hashtag = preg_replace("/(?:^|[^ｦ-ﾟー゛゜々ヾヽぁ-ヶ一-龠ａ-ｚＡ-Ｚ０-９a-zA-Z0-9&_\/]+)[#＃]([ｦ-ﾟー゛゜々ヾヽぁ-ヶ一-龠ａ-ｚＡ-Ｚ０-９a-zA-Z0-9_]*[ｦ-ﾟー゛゜々ヾヽぁ-ヶ一-龠ａ-ｚＡ-Ｚ０-９a-zA-Z]+[ｦ-ﾟー゛゜々ヾヽぁ-ヶ一-龠ａ-ｚＡ-Ｚ０-９a-zA-Z0-9_]*)/u", " <a href=\"{$self}?mode=search&amp;tag=tag&amp;search=\\1\">#\\1</a>", $hashtag);
	return $hashtag;
}

$mode = newstring(filter_input(INPUT_POST, 'mode'));

//var_dump($_GET);
if(filter_input(INPUT_GET, 'mode')==="anime"){
	$pch = newstring(filter_input(INPUT_GET, 'pch'));
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
	$no = filter_input(INPUT_GET, 'no');
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
if(filter_input(INPUT_GET, 'mode')==="sodane"){
	$mode = "sodane";
	$resto = filter_input(INPUT_GET, 'resto',FILTER_VALIDATE_INT);
}
if(filter_input(INPUT_GET, 'mode')==="rsodane"){
	$mode = "rsodane";
	$resto = filter_input(INPUT_GET, 'resto',FILTER_VALIDATE_INT);
}
if(filter_input(INPUT_GET, 'mode')==="continue"){
	$no = filter_input(INPUT_GET, 'no');
	$mode = "continue";
}
if(filter_input(INPUT_GET, 'mode')==="del"){
	$mode = "del";
}
if(filter_input(INPUT_GET, 'mode')==="edit"){
	$mode = "edit";
}
if(filter_input(INPUT_GET, 'mode')==="editexec"){
	$mode = "editexec";
}
if(filter_input(INPUT_GET, 'mode')==="catalog"){
	$mode = "catalog";
}
if(filter_input(INPUT_GET, 'mode')==="search"){
	$mode = "search";
}

$message ="";
$sub = newstring(filter_input(INPUT_POST, 'sub'));
$name = newstring(filter_input(INPUT_POST, 'name'));
$mail = newstring(filter_input(INPUT_POST, 'mail'));
$url = newstring(filter_input(INPUT_POST, 'url'));
$com = newstring(filter_input(INPUT_POST, 'com'));
$parent = newstring(trim(filter_input(INPUT_POST, 'parent')));
$picfile = newstring(trim(filter_input(INPUT_POST, 'picfile')));
$invz = newstring(trim(filter_input(INPUT_POST, 'invz')));
$img_w = newstring(trim(filter_input(INPUT_POST, 'img_w')));
$img_h = newstring(trim(filter_input(INPUT_POST, 'img_h')));
$time = newstring(trim(filter_input(INPUT_POST, 'time')));
$pwd = newstring(trim(filter_input(INPUT_POST, 'pwd')));
$pwdh = password_hash($pwd,PASSWORD_DEFAULT);
$exid = newstring(trim(filter_input(INPUT_POST, 'exid')));

//var_dump($_COOKIE);

$pwdc = filter_input(INPUT_COOKIE, 'pwdc');
$usercode = filter_input(INPUT_COOKIE, 'usercode');//nullならuser-codeを発行

//$_SERVERから変数を取得
//var_dump($_SERVER);

$req_method = ( isset($_SERVER["REQUEST_METHOD"]) === true ) ? ($_SERVER["REQUEST_METHOD"]): "";
//INPUT_SERVER が動作しないサーバがあるので$_SERVERを使う。

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
	global $name,$com,$sub,$parent,$picfile,$img_w,$img_h,$mail,$url,$time,$pwd,$pwdh,$exid,$invz;
	global $badstring,$badip;
	global $req_method;
	global $badstr_A,$badstr_B,$badname;
	global $smarty;
	$userip = get_uip();

	$ptime = newstring(trim(filter_input(INPUT_POST, 'ptime')));
	$secptime = newstring(filter_input(INPUT_POST, 'secptime'));

	if($req_method !== "POST") {error(MSG006);exit;}

	//チェックする項目から改行・スペース・タブを消す
	$chk_com  = preg_replace("/\s/u", "", $com );
	$chk_name = preg_replace("/\s/u", "", $name );
	$chk_sub = preg_replace("/\s/u", "", $sub );
	$chk_mail = preg_replace("/\s/u", "", $mail );

	//本文に日本語がなければ拒絶
	if (USE_JAPANESEFILTER) {
		mb_regex_encoding("UTF-8");
		if (strlen($com) > 0 && !preg_match("/[ぁ-んァ-ヶー一-龠]+/u",$chk_com)) {
			error(MSG035);
			exit;
		}
	}

	//本文へのURLの書き込みを禁止
	if(DENY_COMMENTS_URL && preg_match('/:\/\/|\.co|\.ly|\.gl|\.net|\.org|\.cc|\.ru|\.su|\.ua|\.gd/i', $com)) {error(MSG036); exit;}

	foreach($badstring as $value){//拒絶する文字列
		if($value===''){
		break;
		}
		if(preg_match("/$value/ui",$chk_com)||preg_match("/$value/ui",$chk_sub)||preg_match("/$value/ui",$chk_name)||preg_match("/$value/ui",$chk_mail)){
			error(MSG032);
			exit;
		}
	}
	unset($value);	
	if(isset($badname)){//使えない名前
		foreach($badname as $value){
			if($value===''){
			break;
			}
			if(preg_match("/$value/ui",$chk_name)){
				error(MSG037);
				exit;
			}
		}
		unset($value);	
	}

	$bstr_A_find=false;
	$bstr_B_find=false;

	foreach($badstr_A as $value){//指定文字列が2つあると拒絶
		if($value===''){
		break;
		}
		if(preg_match("/$value/ui",$chk_com)||preg_match("/$value/ui",$chk_sub)||preg_match("/$value/ui",$chk_name)||preg_match("/$value/ui",$chk_mail)){
			$bstr_A_find=true;
		break;
		}
	}
	unset($value);
	foreach($badstr_B as $value){
		if($value===''){
			break;
		}
		if(preg_match("/$value/ui",$chk_com)||preg_match("/$value/ui",$chk_sub)||preg_match("/$value/ui",$chk_name)||preg_match("/$value/ui",$chk_mail)){
			$bstr_B_find=true;
		break;
		}
	}
	unset($value);
	if($bstr_A_find && $bstr_B_find){
		error(MSG032);
		exit;
	}

	if(USE_NAME&&!$name) {error(MSG009);exit;}
	if(USE_COM&&!$com) {error(MSG008);exit;}
	if(USE_SUB&&!$sub) {error(MSG010);exit;}

	if(strlen($com) > MAX_COM) {error(MSG011);exit;}
	if(strlen($name) > MAX_NAME) {error(MSG012);exit;}
	if(strlen($mail) > MAX_EMAIL) {error(MSG013);exit;}
	if(strlen($sub) > MAX_SUB) {error(MSG014);exit;}

	//ホスト取得
	$host = gethostbyaddr($userip);

	foreach($badip as $value){ //拒絶host
		if(preg_match("/$value$/i",$host)) {error(MSG016);exit;}
	}
	//↑セキュリティ関連ここまで

	//投稿時間を隠す設定
	if($secptime == true) {
		$time = PTIME_SEC;
	} else {
		$time = $ptime;
	}
	
	try {
		$db = new PDO("sqlite:noe.db");
		if (isset($_POST["send"] ) ===  true) {

			if ( $name   === "" ) $name = DEF_NAME;
			if ( $com  === "" ) $com  = DEF_COM;
			if ( $sub  === "" ) $sub  = DEF_SUB;

			$utime = time();
			if ($parent == 0 ) {
				$parent = $utime;
			}
			$tree = ($parent * 1000000000) - $utime;

			// 二重投稿チェック
			if (empty($_POST["modid"])==true) {
				// スレ立ての場合
				$table = 'tablelog';
				$wid = 'tid';
			} else {
				// レスの場合
				$table = 'tabletree';
				$wid = 'iid';
			}
			//最新コメント取得
			$sqlw = "SELECT sub, com, host, picfile FROM $table ORDER BY $wid DESC LIMIT 1";
			$msgw = $db->prepare($sqlw);
			$msgw->execute();
			$msgwc = $msgw->fetch();
			if(!empty($msgwc)){
				$msgsub = $msgwc["sub"]; //最新タイトル
				$msgwcom = $msgwc["com"]; //最新コメント取得できた
				$msgwhost = $msgwc["host"]; //最新ホスト取得できた
				//どれも一致すれば二重投稿だと思う
				if($com == $msgwcom && $host == $msgwhost && $sub == $msgsub ){
					$msgs = null;
					$msgw = null;
					$db = null; //db切断
					error('二重投稿ですか？');
					exit;
				}
				//画像番号が一致の場合(投稿してブラウザバック、また投稿とか)
				//二重投稿と判別(画像がない場合は処理しない)
				if($msgwc["picfile"] !== "" && $picfile == $msgwc["picfile"]){
					error('二重投稿ですか？');
					exit;
				}
			}
			//↑二重投稿チェックおわり

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

				$spchfile = str_replace('png','spch', $picfile);
				$pchfile = strtr($picfile , 'png', 'pch');
				
				if ( file_exists(TEMP_DIR.$pchfile) == TRUE ) {
					rename( TEMP_DIR.$pchfile, IMG_DIR.$pchfile );
					chmod( IMG_DIR.$pchfile , 0606);
				} elseif( file_exists(TEMP_DIR.$spchfile) == TRUE ) {
					rename( TEMP_DIR.$spchfile, IMG_DIR.$spchfile );
					chmod( IMG_DIR.$spchfile , 0606);
					$pchfile = $spchfile;
				} else {
					$pchfile = "";
				}
			} else {
				$img_w = 0;
				$img_h = 0;
				$pchfile = "";
			}

			// URLとメールにリンク
			if(AUTOLINK) $com = auto_link($com);
			//ハッシュタグ
			if(USE_HASHTAG) $com = hashtag_link($com);

			// '>'色設定
			$com = preg_replace("/(^|>)((&gt;|＞)[^<]*)/i", "\\1".RE_START."\\2".RE_END, $com);

			// 連続する空行を一行
			$com = preg_replace("/\n((　| )*\n){3,}/","\n",$com);
			//改行をタグに
			if(TH_XHTML == 1){
				//<br />に
				$com = nl2br($com);
			} else {
				//<br>に
				$com = nl2br($com, false);
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
				$sql = "INSERT INTO tablelog (created, modified, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, age, invz, host) VALUES (datetime('now', 'localtime'), datetime('now', 'localtime'), '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwdh', '$id', '$exid', '$tree', '$age', '$invz', '$host')";
				$db = $db->exec($sql);
			} elseif(empty($_POST["modid"])==true && $logt > LOG_MAX_T) {
				//ログ行数オーバーの場合
				//id生成
				$id = substr(crypt(md5($host.ID_SEED.date("Ymd", $utime)),'id'),-8);
				$sql = "INSERT INTO tablelog (created, modified, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, age, invz, host) VALUES (datetime('now', 'localtime'), datetime('now', 'localtime'), '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwdh', '$id', '$exid', '$tree', '$age', '$invz', '$host')";
				$db->exec($sql);
				//最初の行にある画像の名前を取得
				$sqlimg = "SELECT picfile FROM tablelog ORDER BY tid LIMIT 1";
				$msgs = $db->prepare($sqlimg);
				$msgs->execute();
				$msg = $msgs->fetch();
				$msgpic = $msg["picfile"]; //画像の名前取得できた
				//画像とかの削除処理
				if (file_exists(IMG_DIR.$msgpic)) {
					$msgdat = str_replace( strrchr($msgpic,"."), "", $msgpic); //拡張子除去
					if (file_exists(IMG_DIR.$msgdat.'.png')) {
						unlink(IMG_DIR.$msgdat.'.png');
					}
					if (file_exists(IMG_DIR.$msgdat.'.jpg')) {
						unlink(IMG_DIR.$msgdat.'.jpg'); //一応jpgも
					}
					if (file_exists(IMG_DIR.$msgdat.'.pch')) {
						unlink(IMG_DIR.$msgdat.'.pch'); 
					}
					if (file_exists(IMG_DIR.$msgdat.'.spch')) {
						unlink(IMG_DIR.$msgdat.'.spch'); 
					}
					if (file_exists(IMG_DIR.$msgdat.'.dat')) {
						unlink(IMG_DIR.$msgdat.'.dat'); 
					}
				}
				//↑画像とか削除処理完了
				//db最初の行を削除
				$sqldel = "DELETE FROM tablelog ORDER BY tid LIMIT 1";
				$db = $db->exec($sqldel);
			} elseif(empty($_POST["modid"])!=true && strpos($mail,'sage')!==false ) {
				//レスの場合でメール欄にsageが含まれる
				$tid = newstring(filter_input(INPUT_POST, 'modid'));
				//id生成
				$id = substr(crypt(md5($host.ID_SEED.date("Ymd", $utime)),'id'),-8);
				if ($age <= LOG_MAX_R) {
					$sql = "INSERT INTO tabletree (created, modified, tid, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, invz, host) VALUES (datetime('now', 'localtime') , datetime('now', 'localtime') , '$tid', '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwdh', '$id', '$exid', '$tree', '$invz', '$host')";
					$db = $db->exec($sql);
				} else {
					//ログ行数オーバーの場合
					$sql = "INSERT INTO tabletree (created, modified, tid, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, invz, host) VALUES (datetime('now', 'localtime') , datetime('now', 'localtime') , '$tid', '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwdh', '$id', '$exid', '$tree', '$invz', '$host')";
					$db->exec($sql);
					//レス画像貼りは今のところ未対応だけど念のため
					//最初の行にある画像の名前を取得
					$sqlimg = "SELECT picfile FROM tabletree ORDER BY iid LIMIT 1";
					$msgs = $db->prepare($sqlimg);
					$msgs->execute();
					$msg = $msgs->fetch();
					$msgpic = $msg["picfile"]; //画像の名前取得できた
					//画像とかの削除処理
					if (file_exists(IMG_DIR.$msgpic)) {
						$msgdat = str_replace( strrchr($msgpic,"."), "", $msgpic); //拡張子除去
						if (file_exists(IMG_DIR.$msgdat.'.png')) {
						unlink(IMG_DIR.$msgdat.'.png');
						}
						if (file_exists(IMG_DIR.$msgdat.'.jpg')) {
							unlink(IMG_DIR.$msgdat.'.jpg'); //一応jpgも
						}
						if (file_exists(IMG_DIR.$msgdat.'.pch')) {
							unlink(IMG_DIR.$msgdat.'.pch'); 
						}
						if (file_exists(IMG_DIR.$msgdat.'.spch')) {
							unlink(IMG_DIR.$msgdat.'.spch'); 
						}
						if (file_exists(IMG_DIR.$msgdat.'.dat')) {
							unlink(IMG_DIR.$msgdat.'.dat'); 
						}
					}
					//↑画像とか削除処理完了
					//db最初の行を削除
					$sqlresdel = "DELETE FROM tabletree ORDER BY iid LIMIT 1";
					$db = $db->exec($sqlresdel);
				}
			} else {
				//レスの場合でメール欄にsageが含まれない
				$tid = newstring(filter_input(INPUT_POST, 'modid'));
				//id生成
				$id = substr(crypt(md5($host.ID_SEED.date("Ymd", $utime)),'id'),-8);
				$nage = $age +1;
				if ($age <= LOG_MAX_R) {
					$sql = "INSERT INTO tabletree (created, modified, tid, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, invz, host) VALUES (datetime('now', 'localtime') , datetime('now', 'localtime') , '$tid', '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwdh', '$id', '$exid', '$tree', '$invz', '$host'); UPDATE tablelog set age = '$nage' where tid = '$tid'";
					$db = $db->exec($sql);
				} else {
					//ログ行数オーバーの場合
					$sql = "INSERT INTO tabletree (created, modified, tid, name, sub, com, mail, url, picfile, pchfile, img_w, img_h, utime, parent, time, pwd, id, exid, tree, invz, host) VALUES (datetime('now', 'localtime') , datetime('now', 'localtime') , '$tid', '$name', '$sub', '$com', '$mail', '$url', '$picfile', '$pchfile', '$img_w', '$img_h', '$utime', '$parent', '$time', '$pwdh', '$id', '$exid', '$tree', '$invz', '$host')";
					$db->exec($sql);
					//レス画像貼りは今のところ未対応だけど念のため
					//最初の行にある画像の名前を取得
					$sqlimg = "SELECT picfile FROM tabletree ORDER BY iid LIMIT 1";
					$msgs = $db->prepare($sqlimg);
					$msgs->execute();
					$msg = $msgs->fetch();
					$msgpic = $msg["picfile"]; //画像の名前取得できた
					//画像とかの削除処理
					if (file_exists(IMG_DIR.$msgpic)) {
						$msgdat = str_replace( strrchr($msgpic,"."), "", $msgpic); //拡張子除去
						if (file_exists(IMG_DIR.$msgdat.'.png')) {
						unlink(IMG_DIR.$msgdat.'.png');
						}
						if (file_exists(IMG_DIR.$msgdat.'.jpg')) {
							unlink(IMG_DIR.$msgdat.'.jpg'); //一応jpgも
						}
						if (file_exists(IMG_DIR.$msgdat.'.pch')) {
							unlink(IMG_DIR.$msgdat.'.pch'); 
						}
						if (file_exists(IMG_DIR.$msgdat.'.spch')) {
							unlink(IMG_DIR.$msgdat.'.spch'); 
						}
						if (file_exists(IMG_DIR.$msgdat.'.dat')) {
							unlink(IMG_DIR.$msgdat.'.dat'); 
						}
					}
					//↑画像とか削除処理完了
					//db最初の行を削除
					$sqlresdel = "DELETE FROM tabletree ORDER BY iid LIMIT 1";
					$db = $db->exec($sqlresdel);
				}
			}

			$c_pass = $pwd;
			$names = $name;

			//-- クッキー保存 --
			//漢字を含まない項目はこちらの形式で追加
			setcookie ("pwdc", $c_pass,time()+(SAVE_COOKIE*24*3600));

			//クッキー項目："クッキー名<>クッキー値"　※漢字を含む項目はこちらに追加
			$cooks = array("namec<>".$names,"emailc<>".$mail,"urlc<>".$url);

			foreach ( $cooks as $cook ) {
				list($c_name,$c_cookie) = explode('<>',$cook);
				// $c_cookie = str_replace("&amp;", "&", $c_cookie);
				setcookie ($c_name, $c_cookie,time()+(SAVE_COOKIE*24*3600));
			}

			$smarty->assign('message','書き込みに成功しました。');
			$msgs = null;
			$msgw = null;
			$count = null;
			$counts = null;
			$db = null; //db切断
		}
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	unset($name,$mail,$sub,$com,$url,$pwd,$pwdh,$resto,$pictmp,$picfile,$mode);
	//header('Location:'.PHP_SELF);
	ok('書き込みに成功しました。画面を切り替えます。');
}

//通常表示モード
function def() {
	global $smarty;
	$dsp_res = DSP_RES;
	$page_def = PAGE_DEF;

	//古いスレのレスボタンを表示しない
	$elapsed_time = ELAPSED_DAYS * 86400; //デフォルトの1年だと31536000
	$nowtime = time(); //いまのunixタイムスタンプを取得
	//あとはテーマ側で計算する
	$smarty->assign('elapsed_time',$elapsed_time);
	$smarty->assign('nowtime',$nowtime);

	//ページング
	try {
		$db = new PDO("sqlite:noe.db");
		if (isset($_GET['page']) && is_numeric($_GET['page'])) {
			$page = $_GET['page'];
			$page = max($page,1);
		} else {
			$page = 1;
		}
		$start = $page_def * ($page - 1);

		//最大何ページあるのか
		$sql = "SELECT COUNT(*) as cnt FROM tablelog WHERE invz=0";
		$counts = $db->query("$sql");
		$count = $counts->fetch(); //スレ数取得できた
		$max_page = floor($count["cnt"] / $page_def) + 1;
		//最後にスレ数0のページができたら表示しない処理
		if(($count["cnt"] % $page_def) == 0){
			$max_page = $max_page - 1;
			//ただしそれが1ページ目なら困るから表示
			$max_page = max($max_page,1);
		}
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

		//そろそろ消える用
		//一番大きい（新しい）スレのIDを取得
		$sql_log_m = "SELECT tid FROM tablelog ORDER by tid DESC LIMIT 1";
		$log_mid = $db->prepare($sql_log_m);
		$log_mid->execute();
		$mid = $log_mid->fetch(); //取り出せた
		if(!empty($mid)) {
			$m_tid = $mid['tid'];
		} else {
			$m_tid = 0;
		} //一番大きいスレID または0
		$smarty->assign('m_tid',$m_tid); //テーマのほうでこれから親idを引く
		// →「スレの古さ番号」が出る。大きいほど古い。
		//閾値を考える
		$thid = LOG_MAX_T * LOG_LIMIT/100; //閾値
		$smarty->assign('thid',$thid);
		//テーマのほうでこの数字と「スレの古さ番号」を比べる
		//thidよりスレの古さ番号が大きいスレは消えるリミットフラグが立つ

		$db = null; //db切断
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	//読み込み
	
	try {
		$db = new PDO("sqlite:noe.db");
		//1ページの全スレッド取得
		$sql = "SELECT tid, created, modified, name, mail, sub, com, url, host, exid, id, pwd, utime, picfile, pchfile, img_w, img_h, time, tree, parent, age, utime FROM tablelog WHERE invz=0 ORDER BY age DESC, tree DESC LIMIT $start,$page_def"; 
		$posts = $db->query($sql);

		$ko = array();
		$oya = array();

		$i = 0;
		while ( $i < PAGE_DEF) {
			$bbsline = $posts->fetch();
			if(empty($bbsline)){break;} //スレがなくなったら抜ける
			$oid = $bbsline["tid"]; //スレのtid(親番号)を取得
			$sqli = "SELECT iid, tid, created, modified, name, mail, sub, com, url, host, exid, id, pwd, utime, picfile, pchfile, img_w, img_h, time, tree, parent FROM tabletree WHERE tid = $oid and invz=0 ORDER BY tree DESC";
			//レス取得
			$postsi = $db->query($sqli);
			$j = 0;
			$flag = true;
			while ( $flag == true) {
				$res = $postsi->fetch();
				if(empty($res)){ //レスがなくなったら
					$bbsline['ressu'] = $j; //スレのレス数
					$bbsline['res_d_su'] = $j - DSP_RES; //スレのレス省略数
					if ($j > DSP_RES) { //スレのレス数が規定より多いと
						$bbsline['rflag'] = true; //省略フラグtrue
					} else {
						$bbsline['rflag'] = false; //省略フラグfalse
					}
					$flag = false;
					break;
				} //抜ける
				$res['resno'] = $j +1; //レス番号
				$ko[] = $res;
				$j++;
			}
			$oya[] = $bbsline;
			$i++;
		}

		$smarty->assign('ko',$ko);
		$smarty->assign('oya',$oya);
		$smarty->assign('dsp_res',DSP_RES);
		$smarty->assign('path',IMG_DIR);

		//$smarty->debugging = true;
		$smarty->display(THEMEDIR.MAINFILE);
		$db = null; //db切断
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
}

//カタログモード
function catalog() {
	global $smarty;
	$page_def = CATALOG_N;

	//ページング
	try {
		$db = new PDO("sqlite:noe.db");
		if (isset($_GET['page']) && is_numeric($_GET['page'])) {
			$page = $_GET['page'];
			$page = max($page,1);
		} else {
			$page = 1;
		}
		$start = $page_def * ($page - 1);

		//最大何ページあるのか
		$sql = "SELECT COUNT(*) as cnt FROM tablelog WHERE invz=0";
		$counts = $db->query("$sql");
		$count = $counts->fetch(); //スレ数取得できた
		$max_page = floor($count["cnt"] / $page_def) + 1;
		//最後にスレ数0のページができたら表示しない処理
		if(($count["cnt"] % $page_def) == 0){
			$max_page = $max_page - 1;
			//ただしそれが1ページ目なら困るから表示
			$max_page = max($max_page,1);
		}
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

		$db = null; //db切断
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	//読み込み
	
	try {
		$db = new PDO("sqlite:noe.db");
		//1ページの全スレッド取得
		$sql = "SELECT tid, created, modified, name, mail, sub, com, url, host, exid, id, pwd, utime, picfile, pchfile, img_w, img_h, time, tree, parent, age, utime FROM tablelog WHERE invz=0 ORDER BY age DESC, tree DESC LIMIT $start,$page_def"; 
		$posts = $db->query($sql);

		$oya = array();

		$i = 0;
		while ( $i < CATALOG_N) {
			$bbsline = $posts->fetch();
			if(empty($bbsline)){break;} //スレがなくなったら抜ける
			$oya[] = $bbsline;
			$i++;
		}

		$smarty->assign('oya',$oya);
		$smarty->assign('path',IMG_DIR);

		//$smarty->debugging = true;
		$smarty->assign('catalogmode','catalog');
		$smarty->display(THEMEDIR.CATALOGFILE);
		$db = null; //db切断
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
}

//検索モード 現在全件表示のみ対応
function search() {
	global $smarty;

	$search = filter_input(INPUT_GET, 'search');
	//部分一致検索
	$bubun =  filter_input(INPUT_GET, 'bubun');
	//本文検索
	$tag = filter_input(INPUT_GET, 'tag');

	//読み込み
	try {
		$db = new PDO("sqlite:noe.db");
		//1ページの全スレッド取得
		//まずtagがあれば本文検索
		if ($tag === 'tag') {
			$sql = "SELECT tid, created, modified, name, mail, sub, com, url, host, exid, id, pwd, utime, picfile, pchfile, img_w, img_h, time, tree, parent, age, utime FROM tablelog WHERE com LIKE '%$search%' AND invz=0 ORDER BY age DESC, tree DESC"; 
			$smarty->assign('catalogmode','hashsearch');
			$smarty->assign('tag',$search);
		} else {
			//tagがなければ作者名検索
			if($bubun === "bubun"){
				$sql = "SELECT tid, created, modified, name, mail, sub, com, url, host, exid, id, pwd, utime, picfile, pchfile, img_w, img_h, time, tree, parent, age, utime FROM tablelog WHERE name LIKE '%$search%' AND invz=0 ORDER BY age DESC, tree DESC"; 
			} else {
				$sql = "SELECT tid, created, modified, name, mail, sub, com, url, host, exid, id, pwd, utime, picfile, pchfile, img_w, img_h, time, tree, parent, age, utime FROM tablelog WHERE name LIKE '$search' AND invz=0 ORDER BY age DESC, tree DESC"; 
			}
			$smarty->assign('catalogmode','search');
			$smarty->assign('author',$search);
		}
		
		$posts = $db->query($sql);

		$oya = array();

		$i = 0;
		while ($bbsline = $posts->fetch()) {
			$oya[] = $bbsline;
			$i++;
		}

		$smarty->assign('oya',$oya);
		$smarty->assign('path',IMG_DIR);

		//$smarty->debugging = true;
		$smarty->assign('s_result',$i);
		$smarty->display(THEMEDIR.CATALOGFILE);
		$db = null; //db切断
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
}

//そうだね
function sodane(){
	$resto = newstring(filter_input(INPUT_GET, 'resto'));
	try {
		$db = new PDO("sqlite:noe.db");
		$sql = "UPDATE tablelog set exid = exid+1 where tid = '$resto'";
		$db = $db->exec($sql);
		$db = null;
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	header('Location:'.PHP_SELF);
	def();
}

//レスそうだね
function rsodane(){
	$resto = newstring(filter_input(INPUT_GET, 'resto'));
	try {
		$db = new PDO("sqlite:noe.db");
		$sql = "UPDATE tabletree set exid = exid+1 where iid = '$resto'";
		$db = $db->exec($sql);
		$db = null;
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	header('Location:'.PHP_SELF);
	def();
}

//レス画面

function res(){
	global $smarty;
	$resno = newstring(filter_input(INPUT_GET, 'res'));
	$smarty->assign('resno',$resno);

	//古いスレのレスフォームを表示しない
	$elapsed_time = ELAPSED_DAYS * 86400; //デフォルトの1年だと31536000
	$nowtime = time(); //いまのunixタイムスタンプを取得
	//あとはテーマ側で計算する
	$smarty->assign('elapsed_time',$elapsed_time);
	$smarty->assign('nowtime',$nowtime);

	try {
		$db = new PDO("sqlite:noe.db");
		$sql = "SELECT * FROM tablelog WHERE tid=".$resno." ORDER BY tree DESC";
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
		//そろそろ消える用
		//一番大きい（新しい）スレのIDを取得
		$sql_log_m = "SELECT tid FROM tablelog ORDER by tid DESC LIMIT 1";
		$log_mid = $db->prepare($sql_log_m);
		$log_mid->execute();
		$mid = $log_mid->fetch(); //取り出せた
		if(!empty($mid)) {
			$m_tid = $mid['tid'];
		} else {
			$m_tid = 0;
		} //一番大きいスレID または0
		$smarty->assign('m_tid',$m_tid); //テーマのほうでこれから親idを引く
		// →「スレの古さ番号」が出る。大きいほど古い。
		//閾値を考える
		$thid = LOG_MAX_T * LOG_LIMIT/100; //閾値
		$smarty->assign('thid',$thid);
		//テーマのほうでこの数字と「スレの古さ番号」を比べる
		//thidよりスレの古さ番号が大きいスレは消えるリミットフラグが立つ
		$db = null;
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	
	$smarty->assign('path',IMG_DIR);
	
	$smarty->display( THEMEDIR.RESFILE );
}

//お絵描き画面
function paintform(){
	global $message,$usercode,$quality,$qualitys,$pwd,$no;
	global $mode,$ctype,$pch,$type;
	global $useneo; //NEOを使う
	global $smarty;

	//NEOを使う or しぃペインター
	if (filter_input(INPUT_POST, 'useneo')){
		$useneo = true;
		$smarty->assign('useneo',true);
	} else {
		$useneo = false;
		$smarty->assign('useneo',false);
	}

	$smarty->assign('mode','piccom');

	$smarty->assign('btitle',TITLE);
	$smarty->assign('home',HOME);
	$smarty->assign('self',PHP_SELF);
	$smarty->assign('message',$message);
	$smarty->assign('pdefw',PDEF_W);
	$smarty->assign('pdefh',PDEF_H);
	$smarty->assign('skindir',THEMEDIR);
	$smarty->assign('tver',TEMPLATE_VER);

	$picw = filter_input(INPUT_POST, 'picw',FILTER_VALIDATE_INT);
	$pich = filter_input(INPUT_POST, 'pich',FILTER_VALIDATE_INT);
	$anime = isset($_POST["anime"]) ? true : false;
	$smarty->assign('anime',$anime);
	
	if($picw < 300) $picw = 300;
	if($pich < 300) $pich = 300;
	if($picw > PMAX_W) $picw = PMAX_W;
	if($pich > PMAX_H) $pich = PMAX_H;

	$smarty->assign('picw',$picw);
	$smarty->assign('pich',$pich);

	if(!$useneo) { //しぃペインターの時の幅と高さ
		$ww = $picw + 510;
		$hh = $pich + 172;
	} else { //NEOのときの幅と高さ
		$ww = $picw + 150;
		$hh = $pich + 172;
	}
	if($hh < 560){$hh = 560;}//共通の最低高
	$smarty->assign('w',$ww);
	$smarty->assign('h',$hh);
	
	$smarty->assign('undo',UNDO);
	$smarty->assign('undo_in_mg',UNDO_IN_MG);

	$smarty->assign('useanime',USE_ANIME);

	$smarty->assign('path',IMG_DIR);

	$smarty->assign('usercode',$usercode);
	$smarty->assign('stime',time());
	
	$userip = get_uip();

	//しぃペインター
	$smarty->assign('layer_count',LAYER_COUNT);
	$qq = $quality ? $quality : $qualitys[0];
	$smarty->assign('quality',$qq);

	if($mode=="contpaint"){
		$ctype = filter_input(INPUT_POST, 'ctype');
		$type = filter_input(INPUT_POST, 'type');
		$pwdf = filter_input(INPUT_POST, 'pwd');
		$smarty->assign('no',$no);
		$smarty->assign('pwd',$pwdf);
		$smarty->assign('ctype',$ctype);
		if(is_file(IMG_DIR.$pch.'.pch')){
			$useneo = true;
			$smarty->assign('useneo',true);
		}elseif(is_file(IMG_DIR.$pch.'.spch')){
			$useneo = false;
			$smarty->assign('useneo',false);
		}
		if((C_SECURITY_CLICK || C_SECURITY_TIMER) && SECURITY_URL){
			$smarty->assign('security',true);
			$smarty->assign('security_click',C_SECURITY_CLICK);
			$smarty->assign('security_timer',C_SECURITY_TIMER);
		}
	}else{
		if((SECURITY_CLICK || SECURITY_TIMER) && SECURITY_URL){
			$smarty->assign('security',true);
			$smarty->assign('security_click',SECURITY_CLICK);
			$smarty->assign('security_timer',SECURITY_TIMER);
		}
		$smarty->assign('newpaint',true);
	}
	$smarty->assign('security_url',SECURITY_URL);

	//if($pwd){
	//	$pwd=openssl_encrypt ($pwd,CRYPT_METHOD, CRYPT_PASS, true, CRYPT_IV);//暗号化
	//	$pwd=bin2hex($pwd);//16進数に
	//}

	if($ctype=='pch'){
		//if(is_file(__DIR__.'/'.IMG_DIR.$pch.'.pch')){
		//	$datpch = './'.IMG_DIR.$pch.'.pch';
		//	$smarty->assign('pchfile',$datpch);
		//} 
		//elseif(is_file(__DIR__.'/'.IMG_DIR.$pch.'.spch')){
		//	$datpch = './'.IMG_DIR.$pch.'.spch';
		//	$smarty->assign('pchfile',$datpch);
		//}
		$pchfile = filter_input(INPUT_POST, 'pch');
		$smarty->assign('pchfile',IMG_DIR.$pchfile);
	}
	if($ctype=='img'){
		$smarty->assign('animeform',false);
		$smarty->assign('anime',false);
		$imgfile = filter_input(INPUT_POST, 'img');
		$smarty->assign('imgfile',IMG_DIR.$imgfile);
	}

	//差し換え時の認識コード追加
	if($type==='rep'){
		$no = filter_input(INPUT_POST, 'no',FILTER_VALIDATE_INT);
		$pwdf = filter_input(INPUT_POST, 'pwd');
		$time=time();
		$repcode = substr(crypt(md5($no.$userip.$pwdf.date("Ymd", $time)),$time),-8);
		//念の為にエスケープ文字があればアルファベットに変換
		$repcode = strtr($repcode,"!\"#$%&'()+,/:;<=>?@[\\]^`/{|}~","ABCDEFGHIJKLMNOabcdefghijklmn");
		//パスワード暗号化
		$pwdf = openssl_encrypt ($pwdf,CRYPT_METHOD, CRYPT_PASS, true, CRYPT_IV);//暗号化
		$pwdf = bin2hex($pwdf);//16進数に
		$datmode = 'picrep&amp;no='.$no.'&amp;pwd='.$pwdf.'&amp;repcode='.$repcode;
		$smarty->assign('mode',$datmode);
		$datusercode = $usercode.'&amp;repcode='.$repcode;
		$smarty->assign('usercode',$datusercode);
	}

	//出力
	$smarty->display( THEMEDIR.PAINTFILE );
}

//アニメ再生

function openpch($pch,$sp="") {
	global $smarty;
	$message = "";

	$pch = newstring(filter_input(INPUT_GET, 'pch'));
	$pchh = str_replace( strrchr($pch,"."), "", $pch); //拡張子除去
	$extn = substr($pch, strrpos($pch, '.') + 1); //拡張子取得

	$picfile = IMG_DIR.$pchh.".png";

	if($extn=='spch'){
		$pchfile = IMG_DIR.$pch;
		$smarty->assign('useneo',false); //拡張子がspchのときはしぃぺ
	}elseif($extn=='pch'){
		$pchfile = IMG_DIR.$pch;
		$smarty->assign('useneo',true); //拡張子がpchのときはNEO
	}else { //動画が無い時は処理しない
		$w=$h=$picw=$pich=$datasize="";
		$smarty->assign('useneo',true);
	}
	$datasize = filesize($pchfile);
	$size = getimagesize($picfile);
	if(!$sp) $sp = PCH_SPEED;
	$picw = $size[0];
	$pich = $size[1];
	$w = $picw;
	$h = $pich + 26;
	if($w < 300){$w = 300;}
	if($h < 326){$h = 326;}

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
	$smarty->assign('pchfile','./'.$pch);
	$smarty->assign('datasize',$datasize);

	$smarty->assign('speed',PCH_SPEED);

	$smarty->assign('path',IMG_DIR);

	$smarty->display( THEMEDIR.ANIMEFILE );
}

//お絵かき投稿
function paintcom(){
	global $usercode,$stime,$ptime;
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
	$smarty->assign('stime',$stime);
	$smarty->assign('usercode',$usercode);

	//----------
	$time = time();

	//描画時間
	if($stime){
		$ptime = '';
		if($stime){
			$psec = $time-$stime;
			if($psec >= 86400){
				$D=($psec - ($psec % 86400)) / 86400;
				$ptime .= $D.PTIME_D;
				$psec -= $D*86400;
			}
			if($psec >= 3600){
				$H=($psec - ($psec % 3600)) / 3600;
				$ptime .= $H.PTIME_H;
				$psec -= $H*3600;
			}
			if($psec >= 60){
				$M=($psec - ($psec % 60)) / 60;
				$ptime .= $M.PTIME_M;
				$psec -= $M*60;
			}
			if($psec){
				$ptime .= $psec.PTIME_S;
			}
		}
	}

	$smarty->assign('ptime',$ptime);

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

//コンティニュー画面in 今のところレス画像には非対応
function incontinue($no) {
	global $smarty;
	$smarty->assign('othermode','incontinue');
	$smarty->assign('continue_mode',true);
	$smarty->assign('path',IMG_DIR);

	//コンティニュー時は削除キーを常に表示
	$smarty->assign('passflag',true);
	//新規投稿で削除キー不要の時 true
	if(! CONTINUE_PASS) {
		$smarty->assign('newpost_nopassword',true);
	} else {
		$smarty->assign('newpost_nopassword',false);
	}

	try{
		$db = new PDO("sqlite:noe.db");
		$sql = "SELECT * FROM tablelog WHERE picfile='$no' ORDER BY tree DESC";
		$posts = $db->query($sql);

		$oya = array();
		while ($bbsline = $posts->fetch() ) {
			$oya[] = $bbsline;
			$smarty->assign('oya',$oya); //配列に格納
		}
		$pchh = str_replace( strrchr($no,"."), "", $no); //拡張子除去
		$pchfilename = IMG_DIR.$pchh;
		if(is_file($pchfilename.'.spch')){
			//$pchfile = IMG_DIR.$pch;
			$smarty->assign('useshi',true);
			$smarty->assign('useneo',false); //拡張子がspchのときはしぃぺ
			$smarty->assign('ctype_pch',true);
		}elseif(is_file($pchfilename.'.pch')){
			//$pchfile = IMG_DIR.$pch;
			$smarty->assign('useshi',false);
			$smarty->assign('useneo',true); //拡張子がpchのときはNEO
			$smarty->assign('ctype_pch',true);
		}else { //どっちもない＝動画が無い時
			//$w=$h=$picw=$pich=$datasize="";
			$smarty->assign('useneo',true);
			$smarty->assign('useshi',true);
			$smarty->assign('ctype_pch',false);
		}
		$smarty->assign('ctype_img',true);

		$db = null; //db切断
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}

	$smarty->display( THEMEDIR.OTHERFILE );
}

//削除くん

function delmode(){
	global $admin_pass;
	global $smarty;
	$delno = newstring(filter_input(INPUT_POST, 'delno'));
	$delt = newstring(filter_input(INPUT_POST, 'delt')); //0親1レス削除

	$ppwd = newstring(filter_input(INPUT_POST, 'pwd'));

	if ($delt == 0) {
		$deltable = 'tablelog';
		$idk = "tid";
	} else {
		$deltable = 'tabletree';
		$idk = "iid";
	}
	//記事呼び出し
	try {
		$db = new PDO("sqlite:noe.db");

		//パスワードを取り出す
		$sql ="SELECT pwd FROM $deltable WHERE $idk = $delno";
		$msgs = $db->prepare($sql);
		if ($msgs == false) {
			error('そんな記事ない気がします。');exit;
		}
		$msgs->execute();
		$msg = $msgs->fetch();
		if (empty($msg)) {
			error('そんな記事ない気がします。');exit;
		}

		//削除記事の画像を取り出す
		$sqlp ="SELECT picfile FROM $deltable WHERE $idk = $delno";
		$msgsp = $db->prepare($sqlp);
		$msgsp->execute();
		$msgp = $msgsp->fetch();
		if (empty($msgp)) {
			error('画像が見当たりません。');exit;
		}
		$msgpic = $msgp['picfile']; //画像の名前取得できた

		if (isset($_POST["admindel"]) == true) {
			$admindelmode = 1;
		} else {
			$admindelmode = 0;
		}

		if (password_verify($ppwd,$msg['pwd']) === true) {
			//画像とかファイル削除
			if (file_exists(IMG_DIR.$msgpic)) {
				$msgdat = str_replace( strrchr($msgpic,"."), "", $msgpic); //拡張子除去
				if (file_exists(IMG_DIR.$msgdat.'.png')) {
					unlink(IMG_DIR.$msgdat.'.png');
				}
				if (file_exists(IMG_DIR.$msgdat.'.jpg')) {
					unlink(IMG_DIR.$msgdat.'.jpg'); //一応jpgも
				}
				if (file_exists(IMG_DIR.$msgdat.'.pch')) {
					unlink(IMG_DIR.$msgdat.'.pch'); 
				}
				if (file_exists(IMG_DIR.$msgdat.'.spch')) {
					unlink(IMG_DIR.$msgdat.'.spch'); 
				}
				if (file_exists(IMG_DIR.$msgdat.'.dat')) {
					unlink(IMG_DIR.$msgdat.'.dat'); 
				}
			}
			//↑画像とか削除処理完了
			//データベースから削除
			$sql = "DELETE FROM $deltable WHERE $idk=$delno";
			$db = $db->exec($sql);
			$smarty->assign('message','削除しました。');
		} elseif ($admin_pass == $ppwd && $admindelmode == 1) {
			//画像とかファイル削除
			if (file_exists(IMG_DIR.$msgpic)) {
				$msgdat = str_replace( strrchr($msgpic,"."), "", $msgpic); //拡張子除去
				if (file_exists(IMG_DIR.$msgdat.'.png')) {
					unlink(IMG_DIR.$msgdat.'.png');
				}
				if (file_exists(IMG_DIR.$msgdat.'.jpg')) {
					unlink(IMG_DIR.$msgdat.'.jpg'); //一応jpgも
				}
				if (file_exists(IMG_DIR.$msgdat.'.pch')) {
					unlink(IMG_DIR.$msgdat.'.pch'); 
				}
				if (file_exists(IMG_DIR.$msgdat.'.spch')) {
					unlink(IMG_DIR.$msgdat.'.spch'); 
				}
				if (file_exists(IMG_DIR.$msgdat.'.dat')) {
					unlink(IMG_DIR.$msgdat.'.dat'); 
				}
			}
			//↑画像とか削除処理完了
			//データベースから削除
			$sql = "DELETE FROM $deltable WHERE $idk=$delno";
			$db = $db->exec($sql);
			$smarty->assign('message','削除しました。');
		} elseif ($admin_pass == $ppwd && $admindelmode != 1) {
			//管理モード以外での管理者削除は
			//データベースから削除はせずに非表示
			$sql = "UPDATE $deltable SET invz=1 WHERE $idk=$delno";
			$db = $db->exec($sql);
			$smarty->assign('message','削除しました。');
		} else {
			error('パスワードまたは記事番号が違います。');
			exit;
		}
		$db = null; 
		$msgp = null;
		$msg = null;//db切断 
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	//変数クリア
	unset($delno,$delt);
	//header('Location:'.PHP_SELF);
	ok('削除しました。画面を切り替えます。');
}

//画像差し替え レス画像には非対応
function picreplace($no,$pwdf,$stime){
	global $path,$badip;
	$repcode = filter_input(INPUT_GET, 'repcode');
	$pwdf = filter_input(INPUT_GET, 'pwd');
	$pwdf = hex2bin($pwdf);//バイナリに
	$pwdf = openssl_decrypt($pwdf,CRYPT_METHOD, CRYPT_PASS, true, CRYPT_IV);//復号化
	$userip = get_uip();
	
	//ホスト取得
	$host = gethostbyaddr($userip);

	foreach($badip as $value){ //拒絶host
		if(preg_match("/$value$/i",$host)) error(MSG016);
	}

	/*--- テンポラリ捜査 ---*/
	$find=false;
	$handle = opendir(TEMP_DIR);
	while ($file = readdir($handle)) {
		if(!is_dir($file) && preg_match("/\.(dat)$/i",$file)) {
			$fp = fopen(TEMP_DIR.$file, "r");
			$userdata = fread($fp, 1024);
			fclose($fp);
			list($uip,$uhost,$uagent,$imgext,$ucode,$urepcode) = explode("\t", rtrim($userdata)."\t");//区切りの"\t"を行末に190610
			$file_name = preg_replace("/\.(dat)$/i","",$file);
			//画像があり、認識コードがhitすれば抜ける 
			if($file_name && is_file(TEMP_DIR.$file_name.$imgext) && $urepcode === $repcode){
				$find=true;
				break;
			}
		}
	}
	closedir($handle);
	if(!$find){
		error2();
		exit;
	}

	// 時間
	$time = time();

	//描画時間
	if($stime){
		$ptime = '';
		if($stime){
			$psec = $time-$stime;
			if($psec >= 86400){
				$D=($psec - ($psec % 86400)) / 86400;
				$ptime .= $D.PTIME_D;
				$psec -= $D*86400;
			}
			if($psec >= 3600){
				$H=($psec - ($psec % 3600)) / 3600;
				$ptime .= $H.PTIME_H;
				$psec -= $H*3600;
			}
			if($psec >= 60){
				$M=($psec - ($psec % 60)) / 60;
				$ptime .= $M.PTIME_M;
				$psec -= $M*60;
			}
			if($psec){
				$ptime .= $psec.PTIME_S;
			}
		}
	}

	// ログ読み込み
	try {
		$db = new PDO("sqlite:noe.db");
		//記事を取り出す
		$sql ="SELECT pwd, picfile, pchfile, time FROM tablelog WHERE tid = '$no'";
		$msgs = $db->prepare($sql);
		$msgs->execute();
		$msg_d = $msgs->fetch();

		//パスワード照合
		if(password_verify($pwdf,$msg_d["pwd"])||$msg_d["pwd"]=== substr(md5($pwdf),2,8)){
			//あってたら画像アップロード処理
			$picfile = $file_name.$imgext;

			if ( $picfile == true ) {
				rename( TEMP_DIR.$picfile , IMG_DIR.$picfile );
				chmod( IMG_DIR.$picfile , 0606);
				$picdat = strtr($picfile , 'png', 'dat');
				rename( TEMP_DIR.$picdat, IMG_DIR.$picdat );
				chmod( IMG_DIR.$picdat , 0606);

				$spchfile = str_replace('png','spch', $picfile);
				$pchfile = strtr($picfile , 'png', 'pch');
				
				if ( file_exists(TEMP_DIR.$pchfile) == TRUE ) {
					rename( TEMP_DIR.$pchfile, IMG_DIR.$pchfile );
					chmod( IMG_DIR.$pchfile , 0606);
				} elseif( file_exists(TEMP_DIR.$spchfile) == TRUE ) {
					rename( TEMP_DIR.$spchfile, IMG_DIR.$spchfile );
					chmod( IMG_DIR.$spchfile , 0606);
					$pchfile = $spchfile;
				} else {
					$pchfile = "";
				}
			} else { //念のため
				$pchfile = "";
			}
			//旧ファイル削除
			if(is_file($path.$msg_d["picfile"])) unlink($path.$msg_d["picfile"]);
			if(is_file($path.$msg_d["pchfile"])) unlink($path.$msg_d["pchfile"]);
			$msgedat = str_replace( strrchr($msg_d["picfile"],"."), "", $msg_d["picfile"]); //拡張子除去
			$msgedat = $msgedat.'.dat';
			if(is_file($path.$msgedat)) unlink($path.$msgedat);
			//描画時間追加
			//秘密の時は秘密
			if($msg_d["time"] == PTIME_SEC) {
				$time = PTIME_SEC;
			} elseif($msg_d["time"]) {
				$time = $msg_d["time"].'+'.$ptime;
			}
			//id生成
			$utime = time();
			$id = substr(crypt(md5($host.ID_SEED.date("Ymd", $utime)),'id'),-8);
			//db上書き
			$sqlrep = "UPDATE tablelog set modified = datetime('now', 'localtime'), picfile = '$picfile', pchfile = '$pchfile', host = '$host', id = '$id', time = '$time' where tid = $no";
			$db = $db->exec($sqlrep);
		} else {
			error(MSG028);exit;
		}
		$db = null; //db切断
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	ok('編集に成功しました。画面を切り替えます。');
}


//編集モードくん入口
function editform() {
	global $admin_pass;
	global $smarty;

	$editno = newstring(filter_input(INPUT_POST, 'delno'));
	if ($editno == "") {
		error('記事番号を入力してください');exit;
	}
	$editt = newstring(filter_input(INPUT_POST, 'delt')); //0親1レス
	if ($editt == 0) {
		$edittable = 'tablelog';
		$idk = "tid";
	} else {
		$edittable = 'tabletree';
		$idk = "iid";
		$smarty->assign('resedit','resedit');
	}
	//記事呼び出し
	try {
		$db = new PDO("sqlite:noe.db");

		//パスワードを取り出す
		$sql ="SELECT pwd FROM $edittable WHERE $idk = $editno";
		$msgs = $db->prepare($sql);
		$msgs->execute();
		$msg = $msgs->fetch();
		if (empty($msg)) {
			error('そんな記事ないです。');exit;
		}
		$postpwd = newstring(filter_input(INPUT_POST, 'pwd'));
		if (password_verify($postpwd,$msg['pwd']) === true) {
			//パスワードがあってたら
			$sqli ="SELECT * FROM $edittable WHERE $idk = $editno";
			$posts = $db->query($sqli);
			$oya = array();
			while ($bbsline = $posts->fetch() ) {
				$oya[] = $bbsline;
				$smarty->assign('oya',$oya);
			}
			$smarty->assign('message','編集モード...');
		} elseif ($admin_pass == $postpwd ) {
			//管理者編集モード
			$sqli ="SELECT * FROM $edittable WHERE $idk = $editno";
			$posts = $db->query($sqli);
			$oya = array();
			while ($bbsline = $posts->fetch() ) {
				$oya[] = $bbsline;
				$smarty->assign('oya',$oya);
			}
			$smarty->assign('message','管理者編集モード...');
		} else {
			$db = null; 
			$msgs = null;
			$msg = null;//db切断 
			error('パスワードまたは記事番号が違います。');
			exit;
		}
		$db = null; 
		$msgs = null;
		$posts = null;
		$msg = null;//db切断 

		$smarty->assign('othermode','edit'); //編集モード
		$smarty->display( THEMEDIR.OTHERFILE );
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
}

//編集モードくん本体
function editexec(){
	global $name,$com,$sub,$picfile,$mail,$url,$pwd,$pwdh,$exid;
	global $badstring,$badip;
	global $req_method;
	global $badstr_A,$badstr_B,$badname;
	global $smarty;
	$userip = get_uip();

	$resedit = newstring(trim(filter_input(INPUT_POST, 'resedit')));
	$e_no = newstring(trim(filter_input(INPUT_POST, 'e_no')));

	if($req_method !== "POST") {error(MSG006);exit;}

	//チェックする項目から改行・スペース・タブを消す
	$chk_com  = preg_replace("/\s/u", "", $com );
	$chk_name = preg_replace("/\s/u", "", $name );
	$chk_sub = preg_replace("/\s/u", "", $sub );
	$chk_mail = preg_replace("/\s/u", "", $mail );

	//本文に日本語がなければ拒絶
	if (USE_JAPANESEFILTER) {
		mb_regex_encoding("UTF-8");
		if (strlen($com) > 0 && !preg_match("/[ぁ-んァ-ヶー一-龠]+/u",$chk_com)) {
			error(MSG035);
			exit;
		}
	}

	//本文へのURLの書き込みを禁止
	if(DENY_COMMENTS_URL && preg_match('/:\/\/|\.co|\.ly|\.gl|\.net|\.org|\.cc|\.ru|\.su|\.ua|\.gd/i', $com)) {error(MSG036); exit;}

	foreach($badstring as $value){//拒絶する文字列
		if($value===''){
		break;
		}
		if(preg_match("/$value/ui",$chk_com)||preg_match("/$value/ui",$chk_sub)||preg_match("/$value/ui",$chk_name)||preg_match("/$value/ui",$chk_mail)){
			error(MSG032);
			exit;
		}
	}
	unset($value);	
	if(isset($badname)){//使えない名前
		foreach($badname as $value){
			if($value===''){
			break;
			}
			if(preg_match("/$value/ui",$chk_name)){
				error(MSG037);
				exit;
			}
		}
		unset($value);	
	}

	$bstr_A_find=false;
	$bstr_B_find=false;

	foreach($badstr_A as $value){//指定文字列が2つあると拒絶
		if($value===''){
		break;
		}
		if(preg_match("/$value/ui",$chk_com)||preg_match("/$value/ui",$chk_sub)||preg_match("/$value/ui",$chk_name)||preg_match("/$value/ui",$chk_mail)){
			$bstr_A_find=true;
		break;
		}
	}
	unset($value);
	foreach($badstr_B as $value){
		if($value===''){
			break;
		}
		if(preg_match("/$value/ui",$chk_com)||preg_match("/$value/ui",$chk_sub)||preg_match("/$value/ui",$chk_name)||preg_match("/$value/ui",$chk_mail)){
			$bstr_B_find=true;
		break;
		}
	}
	unset($value);
	if($bstr_A_find && $bstr_B_find){
		error(MSG032);
		exit;
	}

	if(USE_NAME&&!$name) {error(MSG009);exit;}
	if(USE_COM&&!$com) {error(MSG008);exit;}
	if(USE_SUB&&!$sub) {error(MSG010);exit;}

	if(strlen($com) > MAX_COM) {error(MSG011);exit;}
	if(strlen($name) > MAX_NAME) {error(MSG012);exit;}
	if(strlen($mail) > MAX_EMAIL) {error(MSG013);exit;}
	if(strlen($sub) > MAX_SUB) {error(MSG014);exit;}

	//ホスト取得
	$host = gethostbyaddr($userip);

	foreach($badip as $value){ //拒絶host
		if(preg_match("/$value$/i",$host)) {error(MSG016);exit;}
	}
	//↑セキュリティ関連ここまで

	// URLとメールにリンク
	if(AUTOLINK) $com = auto_link($com);
	// '>'色設定
	$com = preg_replace("/(^|>)((&gt;|＞)[^<]*)/i", "\\1".RE_START."\\2".RE_END, $com);

	// 連続する空行を一行
	$com = preg_replace("/\n((　| )*\n){3,}/","\n",$com);
	//改行をタグに
	if(TH_XHTML == 1){
		//<br />に
		$com = nl2br($com);
	} else {
		//<br>に
		$com = nl2br($com, false);
	}	

	if($resedit == 1) {
		$edittable = 'tabletree';
		$eid = 'iid';
	} else {
		$edittable = 'tablelog';
		$eid = 'tid';
	}

	try {
		$db = new PDO("sqlite:noe.db");
		$sql = "UPDATE $edittable set modified = datetime('now', 'localtime'), name = '$name', mail = '$mail', sub = '$sub', com = '$com', url = '$url', host = '$host', exid = '$exid', pwd = '$pwdh' where $eid = $e_no";
		$db = $db->exec($sql);
		$db = null;
		$smarty->assign('message','編集完了しました。');
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	unset($name,$mail,$sub,$com,$url,$pwd,$pwdh,$resto,$pictmp,$picfile,$mode);
	//header('Location:'.PHP_SELF);
	ok('編集に成功しました。画面を切り替えます。');
}

//管理モードin
function admin_in() {
	global $smarty;
	$smarty->assign('self',PHP_SELF);
	$smarty->assign('othermode','admin_in');

	$smarty->display( THEMEDIR.OTHERFILE );
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

	$smarty->assign('path',IMG_DIR);

	//最大何ページあるのか
	//記事呼び出しから
	try {
		$db = new PDO("sqlite:noe.db");
		//読み込み
		$adminpass = newstring(filter_input(INPUT_POST, 'adminpass'));
		if ($adminpass == $admin_pass) {
			$sql = "SELECT * FROM tablelog ORDER BY age DESC,tree DESC";
			$oya = array();
			$posts = $db->query($sql);
			while ($bbsline = $posts->fetch() ) {
				if(empty($bbsline)){break;} //スレがなくなったら抜ける
				$oid = $bbsline["tid"]; //スレのtid(親番号)を取得
				$oya[] = $bbsline;
			} 
			$smarty->assign('oya',$oya);

			//スレッドの記事を取得
			$sqli = "SELECT * FROM tabletree ORDER BY tree DESC";
			$ko = array();
			$postsi = $db->query($sqli);
			while ($res = $postsi->fetch()){
				$ko[] = $res;
			}
			$smarty->assign('ko',$ko);
			$smarty->display( THEMEDIR.ADMINFILE );
		} else {
			$db = null; //db切断
			error('管理パスを入力してください');
			exit;
		}
		$db = null; //db切断
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
}

// コンティニュー認証 レス画像には非対応
function usrchk(){
	$no = filter_input(INPUT_POST, 'no',FILTER_VALIDATE_INT);
	$pwdf = filter_input(INPUT_POST, 'pwd');
	$flag = FALSE;
	try {
		$db = new PDO("sqlite:noe.db");
		//パスワードを取り出す
		$sql ="SELECT pwd FROM tablelog WHERE tid = $no";
		$msgs = $db->prepare($sql);
		$msgs->execute();
		$msg = $msgs->fetch();
		if(password_verify($pwdf,$msg['pwd'])||substr(md5($pwdf),2,8) === $msg['pwd']){
			$flag = true;
		} else {
			$flag = false;
		}
		$db = null; //切断
	} catch (PDOException $e) {
		echo "DB接続エラー:" .$e->getMessage();
	}
	if(!$flag) {
		error(MSG028);
		exit;
	}
}

//OK画面
function ok($mes) {
	global $smarty;
	$smarty->assign('okmes',$mes);
	$smarty->assign('othermode','ok');
	$smarty->display( THEMEDIR.OTHERFILE );
}

//エラー画面
function error($mes) {
	global $db;
	global $smarty;
	$db = null; //db切断
	$smarty->assign('errmes',$mes);
	$smarty->assign('othermode','err');
	$smarty->display( THEMEDIR.OTHERFILE );
}

//画像差し替え失敗
function error2() {
	global $db;
	global $smarty;
	$db = null; //db切断
	$smarty->assign('othermode','err2');
	$smarty->display( THEMEDIR.OTHERFILE );
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

	if(!is_dir(realpath(TEMP_DIR))){
		mkdir(TEMP_DIR,0707);chmod(TEMP_DIR,0707);
	}
	if(!is_dir(realpath(TEMP_DIR)))$err.=TEMP_DIR."がありません<br>";
	if(!is_writable(realpath(TEMP_DIR)))$err.=TEMP_DIR."を書けません<br>";
	if(!is_readable(realpath(TEMP_DIR)))$err.=TEMP_DIR."を読めません<br>";
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
	case 'sodane':
		sodane();
		break;
	case 'rsodane':
		rsodane();
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
	case 'continue':
		incontinue($no);
		break;
	case 'contpaint':
		//パスワードが必要なのは差し換えの時だけ
		if(CONTINUE_PASS||$type==='rep') usrchk();
		// if(ADMIN_NEWPOST) $admin=$pwd;
		$palette="";
		paintform($palette);
		break;
	case 'picrep':
		picreplace($no,$pwd,$stime);
	break;
	case 'catalog':
		catalog();
	break;
	case 'search':
		search();
	break;
	case 'edit':
		editform();
		break;
	case 'editexec':
		editexec();
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
