<?php
//--------------------------------------------------
//　「noe-board」v0.10.0～用スキン「mono」設定ファイル
//　by sakots https://sakots.red/
//--------------------------------------------------

//テンプレートのバージョン
define('TEMPLATE_VER', "v0.2.0 lot.200527");

/* -------------------- */

//メインのテンプレートファイル
define('MAINFILE', "mono_main.html");

//レスのテンプレートファイル
define('RESFILE', "mono_res.html");

//お絵かきのテンプレートファイル
define('PAINTFILE', "mono_paint.html");

//動画再生のテンプレートファイル
define('ANIMEFILE', "mono_anime.html");

//投稿時のテンプレートファイル
define('PICFILE', "mono_picpost.html");

//管理モードのテンプレートファイル
define('ADMINFILE', "mono_admin.html");

//その他のテンプレートファイル
define('OTHERFILE', "mono_other.html");



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
