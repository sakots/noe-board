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

//スクリプトのバージョン
$smarty->assign('ver',"v0.10.0");

$smarty->assign('btitle',TITLE);
$smarty->assign('home',HOME);
$smarty->assign('self',PHP_SELF);
$message = "";
$smarty->assign('message',$message);
$smarty->assign('pdefw',PDEF_W);
$smarty->assign('pdefh',PDEF_H);
$smarty->assign('skindir',THEMEDIR);
$smarty->assign('tver',TEMPLATE_VER);

//$smarty->assign('picw',$_POST["picw"]);
//$smarty->assign('pich',$_POST["pich"]);


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

//画像投稿処理
//$upfile = $temppath.$picfile;
//$upfile_name = $picfile;
//$picfile = str_replace(strrchr($picfile,"."),"",$picfile); //拡張子除去

$tmp2 = array();
$smarty->assign('tmp',$tmp2);

//if( file_exists( $logfile ) ) {
//	$lognum = count( file( $logfile ) ) + 1;
//} else {
//	$lognum = 1;
//}
//$smarty->assign('lognum',$lognum);

$smarty->assign('path',IMG_DIR);


//$smarty->debugging = true;
$smarty->display( THEMEDIR.PICFILE );
exit;

?>