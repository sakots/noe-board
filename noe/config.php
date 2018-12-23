<?php
/*
  * noe-board v0.2.0 lot.181223
  * by sakots >> https://sakots.red/
  *
  * noe-boardの設定ファイルです。
  *
*/

//データベース設定　これだけは設置前に！

//ログDB名
define('LOGDB', 'noe.db');

//DBのアドレス
define('DBHOST', 'localhost');

//DBのユーザー名
define('DBUSER', 'root');

//DBのパスワード
define('DBPASS', '');


//urlパラメータを追加する する:1 しない:0
//ブラウザのキャッシュが表示されて投稿しても反映されない時は1。
//.htaccessでキャッシュの有効期限を過去にしている場合は設定不要。

define('URL_PARAMETER', '1');

//シェアボタンを表示する する:1 しない:0
//対応テンプレートが必要
//設置場所のURL ROOT_URL で設定したurlをもとにリンクを作成
define('SHARE_BUTTON', '0');

//本文に日本語がなければ拒絶
define('USE_JAPANESEFILTER', '1');

//指定文字列+本文へのURLの書き込みで拒絶（正規表現）
$badstring_and_url = array("ブランド","偽物","財布","\[\/URL\]");

//本文へのURLの書き込みを禁止する する:1 しない:0
define('DENY_COMMENTS_URL', '1');

// 連続・二重投稿対象セキュリティレベル
//※連続・二重投稿チェック対象を決める条件
// 0:最低　…　チェックしない
// 1:低　　…　ホストかパスワードが同じログの場合(従来の条件)
// 2:中　　…　低レベルの条件に加え、名前・メールアドレス・URL・題名のいずれかが同じ場合
// 3:高　　…　低レベルの条件に加え、名前・メールアドレス・URL・題名のいずれかが類似率を上回る場合
// 4:最高　…　無条件でチェック。最新ログ20件と連続・二重投稿チェックする事になる
//※中・高レベルのとき、見入力項目は無視
define('POST_CHECKLEVEL', '0');

// 連続・二重投稿対象セキュリティレベルが 高 のときの類似率(単位％)
define('VALUE_LIMIT', '80');

// 二重投稿セキュリティレベル
//※二重投稿とみなす条件
// 0:最低　…　本文が一致し、画像なしの場合(従来の条件)
// 1:低　　…　本文が一致する場合
// 2:中　　…　本文が類似率(中)を上回る場合
// 3:高　　…　本文が類似率(高)を上回る場合
define('D_POST_CHECKLEVEL', '0');

// 二重投稿セキュリティレベルが 中 のときの類似率(単位％)
define('COMMENT_LIMIT_MIDDLE', '90');

// 二重投稿セキュリティレベルが 高 のときの類似率(単位％)
define('COMMENT_LIMIT_HIGH', '80');

// 言語設定
define('LANG', 'Japanese');

// 出力文字コード指定 1:EUC-JP, 2:Shift_JIS, 3:ISO-2022-JP(JIS), 4:UTF-8
//※1～4に該当しない場合は、直接文字コード名を入れて下さい
define('CHARSET_OUT', '4');

// 文字コード変換対象指定 0:出力HTMLとログも含むすべて, 1:クッキーとメールのみ
define('CHARSET_CONVERT', '0');

// 内部文字コード指定 1:EUC-JP, 2:Shift_JIS, 3:ISO-2022-JP(JIS), 4:UTF-8
//※1～4に該当しない場合は、直接文字コード名を入れて下さい
//★上のCHARSET_CONVERTで 0 を指定した場合に有効★
define('CHARSET_IN', '4');

//ユーザー削除権限 (0:不可 1:treeのみ許可 2:treeと画像のみ許可 3:tree,log,画像全て許可)
//※treeのみを消して後に残ったlogは管理者のみ削除可能
define('USER_DEL', '1');

/* ---------- お絵かきアプレット設定 ---------- */
/* ※詳しい内容はアプレットのreadme参照 */
//アンドゥの回数(デフォルト)
define('UNDO', '90');

//アンドゥを幾つにまとめて保存しておくか(デフォルト)
define('UNDO_IN_MG', '45');

//　セキュリティ関連－URLとクリック数かタイマーのどちらかが設定されていれば有効
//※アプレットのreadmeを参照し、十分テストした上で設定して下さい
//セキュリティクリック数。設定しないなら''で
define('SECURITY_CLICK', '1');
//セキュリティタイマー(単位:秒)。設定しないなら''で
define('SECURITY_TIMER', '1');
//セキュリティにヒットした場合の飛び先
define('SECURITY_URL', 'http://www.npa.go.jp/');

//続きを描くときのセキュリティ。利用しないなら両方''で
//続きを描くときのセキュリティクリック数。設定しないなら''で
define('C_SECURITY_CLICK', '2');
//続きを描くときのセキュリティタイマー(単位:秒)。設定しないなら''で
define('C_SECURITY_TIMER', '6');

//そろそろ消える表示のボーダー。最大ログ数からみたパーセンテージ
define('LOG_LIMIT', '92');


/* ---------- メイン設定 ---------- */

//画像保存ディレクトリ。noe.phpから見て
define('IMG_DIR', 'src/');

//タイトル（<title>とTOP）
define('TITLE', 'お絵かき掲示板');

//「ホーム」へのリンク
define('HOME', '../');

//管理者パス
define('ADMIN_PASS', 'pass');

//スクリプト名
define('PHP_SELF', 'noe.php');

//入り口ファイル名
define('PHP_SELF2', 'index.html');

//1ページ以降の拡張子
define('PHP_EXT', '.html');

//投稿容量制限 KB（phpの設定により2Mまで
define('MAX_KB', '2000');

//投稿サイズ（これ以上はサイズを縮小
define('MAX_W', '400');	//幅
define('MAX_H', '600');	//高さ

//名前の制限文字数。半角で
define('MAX_NAME', '100');

//メールアドレスの制限文字数。半角で
define('MAX_EMAIL', '100');

//題名の制限文字数。半角で
define('MAX_SUB', '100');

//URLの制限文字数。半角で
define('MAX_URL', '100');

//本文の制限文字数。半角で
define('MAX_COM', '1000');

//一ページに表示する記事
define('PAGE_DEF', '7');

//最大ログ数
define('LOG_MAX', '900');

//1スレ内のレス表示件数(0で全件表示)
//レスがこの値より多いと古いレスから省略されます
//返信画面で全件表示されます
//[新規投稿は管理者のみ]にした場合の 0 はレスを表示しません
define('DSP_RES', '7');

//クッキー保存日数
define('SAVE_COOKIE', '7');

//連続投稿秒数
define('RENZOKU', '10');

//画像連続投稿秒数
define('RENZOKU2', '20');

//強制sageレス数( 0 ですべてsage)
define('MAX_RES', '20');

//proxyの書込みを制限する y:1 n:0
define('PROXY_CHECK', '0');

//IDを表示する 強制:2 する:1 しない:0
define('DISP_ID', '0');

//ID生成の種
define('ID_SEED', 'IDの種');

//改行を抑制する行数 しない:0
define('BR_CHECK', '0');

//URLを自動リンクする する:1 しない:0
define('AUTOLINK', '1');

//名前を必須にする する:1 しない:0
define('USE_NAME', '0');
define('DEF_NAME', '名無しさん');	//未入力時の名前

//本文を必須にする する:1 しない:0
define('USE_COM', '0');
define('DEF_COM', '本文無し');	//未入力時の本文

//題名を必須にする する:1 しない:0
define('USE_SUB', '0');
define('DEF_SUB', '無題');	//未入力時の題名

//レス時にスレ題名を引用する する:1 しない:0
define('USE_RESUB', '0');

//各スレにレスフォームを表示する する:1 しない:0
define('RES_FORM', '0');

//フォーム下の追加お知らせ
//(例)'<LI>お知らせデース
//     <LI>サーバの規約で<font color=red><big><B>アダルト禁止</B></big></font>'
$addinfo='';

//拒絶する文字列
$badstring = array("irc.s16.xrea.com","著作権の侵害","未承諾広告","URL]");

//拒絶するファイルのmd5
$badfile = array("dummy","dummy2");

//拒絶するホスト
$badip = array("addr.dummy.com","185.36.102.114");


/* ---------- お絵かき設定 ---------- */

//お絵かき機能を使用する お絵かきのみ:2 する:1 しない:0
define('USE_PAINT', '2');

//お絵かき画像ファイル名の頭文字
//お絵かき投稿した画像のファイル名には、必ずこれが先頭に付きます
define('KASIRA', 'oe');

//テンポラリディレクトリ
define('TEMP_DIR', 'tmp/');

//テンポラリ内のファイル有効期限(日数)
define('TEMP_LIMIT', '14');

//お絵描き最大サイズ（これ以上は強制でこの値
//最小値は幅、高さともに 100 固定です
define('PMAX_W', '800');	//幅
define('PMAX_H', '800');	//高さ

//お絵描きデフォルトサイズ
define('PDEF_W', '300');	//幅
define('PDEF_H', '300');	//高さ

//描画時間の表示 する:1 しない:0
define('DSP_PAINTTIME', '1');

//パレットデータファイル名
define('PALETTEFILE', 'palette.txt');

//動画機能を使用する する:1 しない:0
define('USE_ANIME', '1');

//動画記録デフォルトスイッチ ON:1 OFF:0
define('DEF_ANIME', '1');

//動画(PCH)保存ディレクトリ
define('PCH_DIR', 'src/');

//動画再生スピード 超高速:-1 高速:0 中速:10 低速:100 超低速:1000
define('PCH_SPEED', '10');

//お絵かき投稿時のIPチェックをする する:1 しない:0
define('IP_CHECK', '1');

//コンティニューを使用する する:1 しない:0
define('USE_CONTINUE', '1');

//コンティニュー時、削除キーを必要とする 必要:1 不要:0
define('CONTINUE_PASS', '1');

/* ---------- picpost.php用設定 ---------- */
//システムログファイル名
$syslog = "picpost.systemlog";
//システムログ保存件数
$syslogmax = '100';

$path = realpath("./").'/'.IMG_DIR;
$temppath = realpath("./").'/'.TEMP_DIR;

$out["path"] = IMG_DIR;

?>
