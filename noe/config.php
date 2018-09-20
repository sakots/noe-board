<?php
//--------------------------------------------------
//　けいじばん「noe」v0.1.1～　設定ファイル
//　by sakots http://red860000i.sakura.ne.jp/
//--------------------------------------------------

//掲示板タイトル
$btitle = "「noe」";
//[ホーム]の戻り先
$home = "https://sakots.red/";
//名無し
$def_name = "名無し";
//タイトルが空のとき
$def_thtitle = "無題";
//本文が空のとき
$def_comment = "本文なし";
//スキンのディレクトリ
$skindir = "./skin/";

//お絵かきデフォルトサイズ縦
$pdefh = "300";
//お絵かきデフォルトサイズ横
$pdefw = "300";

//画像保存ディレクトリ
define('IMG_DIR', 'src/');

//一時保存ディレクトリ
define('TEMP_DIR', 'tmp/');

//サムネイル保存ディレクトリ
define('THUMB_DIR', 'thumb/');

//お絵描き最大サイズ（これ以上は強制でこの値
//最小値は幅、高さともに 100 固定です
define('PMAX_W', '600');	//幅
define('PMAX_H', '800');	//高さ

//ログファイル名
$logfile = "data.txt";

//ログ数
$def_log = "300";
//このスクリプト名
$self ="noe.php";

//エラーメッセージ集
$err_msg1 = "";
$err_msg2 = "";
$err_msg3 = "";
$err_msg4 = "";
$err_msg5 = "";
$err_msg6 = "";
$err_msg7 = "";
$err_msg8 = "";
$err_msg9 = "";
$err_msg10 = "";
$err_msg11 = "";
$err_msg12 = "";
$err_msg13 = "";
$err_msg14 = "";
$err_msg15 = "";
$err_msg16 = "";
$err_msg17 = "";
$err_msg18 = "";
$err_msg19 = "";

$out["btitle"] = $btitle;
$out["home"] = $home;
$out["self"] = $self;
$out["message"] = $message;
$out["pdefw"] = $pdefw;
$out["pdefh"] = $pdefh;
$out["skindir"] = $skindir;

$out["picw"] = $_POST["picw"];
$out["pich"] = $_POST["pich"];
$out["w"] = $_POST["picw"] + 150;
$out["h"] = $_POST["pich"] + 170;

$path = realpath("./").'/'.IMG_DIR;
$temppath = realpath("./").'/'.TEMP_DIR;

$out["path"] = IMG_DIR;

?>
