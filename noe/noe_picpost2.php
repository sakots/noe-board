<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」v0.7.2
//　by sakots https://sakots.red/
//--------------------------------------------------

//Skinny 0.4.1
include_once( "Skinny.php" );
$out = array();

//設定の読み込み
require("config.php");
require("template_ini.php");

//スクリプトのバージョン
$out["ver"] = "v0.7.2";

$out["btitle"] = TITLE;
$out["home"] = HOME;
$out["self"] = PHP_SELF;
$out["message"] = $message;
$out["pdefw"] = PDEF_W;
$out["pdefh"] = PDEF_H;
$out["skindir"] = SKINDIR;
$out["tver"] = TEMPLATE_VER;
$out["picw"] = $_POST["picw"];
$out["pich"] = $_POST["pich"];

$out["parent"] = $_SERVER['REQUEST_TIME'];

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
		if($ucode == $usercode)
			$tmp[] = $ufilename;
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

$out['post_mode'] = true;
$out['regist'] = true;
if(IP_CHECK) $out['ipcheck'] = true;
if(count($tmp)==0){
	$out['notmp'] = true;
	$out['pictmp'] = 1;
}else{
	$out['pictmp'] = 2;
	sort($tmp);
	reset($tmp);
	foreach($tmp as $tmpfile){
		$src = TEMP_DIR.$tmpfile;
		$srcname = $tmpfile;
		$date = gmdate("Y/m/d H:i", filemtime($src)+9*60*60);
		$out['tmp'][] = compact('src','srcname','date');
	}
}

//画像投稿処理
$upfile = $temppath.$picfile;
$upfile_name = $picfile;
$picfile = str_replace(strrchr($picfile,"."),"",$picfile); //拡張子除去

$out["tmp"][] = $tmp;

if( file_exists( $logfile ) ) {
	$lognum = count( file( $logfile ) ) + 1;
} else {
	$lognum = 1;
}
$out["lognum"] = $lognum;

$Skinny->SkinnyDisplay( SKINDIR.PICFILE, $out );
exit;

?>
