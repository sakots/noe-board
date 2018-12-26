<?php
//--------------------------------------------------
//　「noe」v0.6.0～用スキン「noedef」設定ファイル
//　by sakots https://sakots.red/
//--------------------------------------------------

//テンプレートのバージョン
define('TEMPLATE_VER', "v0.04 lot.181226");

/* -------------------- */

//スキンのフォルダ
define('SKINDIR', "skin/");

//メインのテンプレートファイル
define('MAINFILE', "noe_main.html");

//レスのテンプレートファイル
define('RESFILE', "noe_res.html");

//お絵かきのテンプレートファイル
define('PAINTFILE', "noe_paint.html");

//動画再生のテンプレートファイル
define('ANIMEFILE', "noe_anime.html");

//投稿時のテンプレートファイル
define('PICFILE', "noe_picpost.html");

//その他のテンプレートファイル
define('OTHERFILE', "noe_other.html");



//描画時間の書式
//※日本語だと、"1日1時間1分1秒"
//※英語だと、"1day 1hr 1min 1sec"
define('PTIME_D', '日');
define('PTIME_H', '時間');
define('PTIME_M', '分');
define('PTIME_S', '秒');

//＞が付いた時の書式
//※RE_STARTとRE_ENDで囲むのでそれを考慮して
//cssで設定するの推奨
define('RE_START', '<span class="resma">');
define('RE_END', '</span>');

//編集したときの目印
//※記事を編集したら日付の後ろに付きます
define('UPDATE_MARK', ' *');

?>
