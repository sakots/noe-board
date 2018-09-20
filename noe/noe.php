<?php
//--------------------------------------------------
//　おえかきけいじばん「noe-board」v0.1.1
//　by sakots https://sakots.red/
//--------------------------------------------------

//Skinny 0.4.1
include_once( "Skinny.php" );
$out = array();
$out["ver"] = "v0.1.1";

//設定の読み込み
require("config.php");
require($skindir."template_ini.php");
$mainfile = $skindir.$mainfile;
$resfile = $skindir.$resfile;
$picpfile = $skindir.$picpfile;
$otherfile = $skindir.$otherfile;
$paintfile = $skindir.$paintfile;

//var_dump($_POST);


//スパム無効化関数
function newstring($string) {
	if(get_magic_quotes_gpc()){
		$string = stripslashes($string);
	}
$string = htmlspecialchars($string,ENT_QUOTES,'utf-8');
$string = str_replace(",","，",$string);
$string = str_replace(array("\r\n","\n","\r"),"<br>",$string);
return $string;
}
//無効化ここまで

$message ="";
$thtitle = ( isset( $_POST["thtitle"] ) === true ) ? newstring($_POST["thtitle"]): "";
$name = ( isset( $_POST["name"] ) === true ) ? newstring($_POST["name"]): "";
$comment = ( isset( $_POST["comment"] )  === true ) ? newstring(trim($_POST["comment"]))  : "";
$picfile = newstring(trim($_POST["picfile"]));

//投稿がある場合のみ処理を行う

// ファイルの先頭に文字列を追加する関数
function addFirstRow($str, $file_name) {
	// 事前にファイルの内容を取得
	$contents = file_get_contents($file_name);
	// 文字列を先頭に追加
	$contents = $str . $contents;
	// 上書き 書き込み
	$re = file_put_contents($file_name, $contents);
}

if (  isset($_POST["send"] ) ===  true ) {
	if ( $name   === "" ) $name = $def_name;

	if ( $comment  === "" ) $comment  = $def_comment;

	if ( $thtitle  === "" ) $thtitle  = $def_thtitle;

	if( $err_msg1 === "" && $err_msg2 ==="" ){
		if( file_exists( $logfile ) ) {
			$lognum = count( file( $logfile ) ) + 1;
		} else {
			$lognum = 1;
		}
		$str = $lognum.",".$thtitle.",".$name.",".$picfile.",".$comment.",\n";
		$file_name = $logfile;
		addFirstRow($str, $file_name);
		$out["message"] ="書き込みに成功しました。";
		if ( $picfile == true ) {
			rename( TEMP_DIR.$picfile , IMG_DIR.$picfile );
			chmod( IMG_DIR.$picfile , 0666);
			$picdat = strtr($picfile , png, dat);
			rename( TEMP_DIR.$picdat, IMG_DIR.$picdat );
			chmod( IMG_DIR.$picdat , 0666);
		}
	}

}

$fp = fopen( $logfile,"r");
while( $res = fgets( $fp)){

	$tmp1 = explode(",",$res);
	$arr = array(
		"lognum"=>$tmp1[0],
		"thtitle"=>$tmp1[1],
		"name"=>$tmp1[2],
		"picfile"=>$tmp1[3],
		"comment"=>$tmp1[4]
	);
	$out["bbsline"][] = $arr;
}
fclose( $fp );

$Skinny->SkinnyDisplay( $mainfile, $out );
exit;

?>
