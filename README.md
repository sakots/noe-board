# noe-board
noe-boardは全く新しいお絵かき掲示板スクリプトです。  
new oekaki engine で「noe」です。
nap of the earth(匍匐飛行)ではございません。どうでもいいけど。

<a href="https://github.com/funige/neo/">PaintBBS NEO</a>

## 概要
POTI-board改で使用しているテンプレートエンジン「htmltemplate.inc」は老朽化して今後が危ない…  
ということでなんか新しいテンプレートエンジンはないか探したところ、

<a href="http://skinny.sx68.net/">Skinny</a>  
↓  
<a href="https://www.smarty.net/">smarty</a>  

見つけました！これにPOTI-boardを移植…  
できねえ〜〜〜〜〜〜〜〜！！！！！！！！しるか〜〜〜〜〜〜〜〜！！！  
なら新しく作ったるわ〜〜〜〜〜〜〜〜〜！！！！！！！ASY  

いや、できた。しかしデータベースも使ってみたい…  

という経緯です。

## 設置方法

- パスワードその他を設定  
- アップロード   
- OK！

## 注意

- まだいわゆるアルファ版です。テーマ等仕様がころころ変わる可能性があります。

## サンプルとサポート
<a href="https://sakots.red/noe/">noe</a>

## 履歴

### [2020/06/02] v0.19.0

- 「続きから描く」を新規投稿のみ実装
  - 差し替えはなぜかユーザーコードが一致しないのでできない

### [2020/06/01]

- picpost.phpを最新版に差し替え

### [2020/06/01] v0.18.3

- 編集と削除できなくてもOK画面になってたの修正。

### [2020/06/01] v0.18.2

- 日付フォーマット(テーマ依存)

### [2020/06/01] v0.18.1

- 書き込み/編集成功時にOK画面を出す

### [2020/06/01]

- テーマ修正(動画のないファイルでもレス画面では動画があるように表示される)

### [2020/06/01] v0.18.0

- いくつかのエラーでエラー画面が出るようにした
  - システムメッセージが見れない… → 投稿成功画面つくる…？
- 管理モードinにもテンプレートを適用

### [2020/05/31] v0.17.2

- 二重投稿対策

### [2020/05/31] v0.17.1

- cookieのパスワードにハッシュ化されたものが保存されていたの修正
- ~~連続投稿に確認アラートを出す~~

### [2020/05/31]

- テーマのjavascript、描画時間関連修正

### [2020/05/31]

- テーマ修正

### [2020/05/31] v0.17.0

- レスできないの修正
- スレ立てできないの修正
- 編集モード搭載

#### 今後の予定

- 続きから描く
- 指定日数後に返信できなくする

### [2020/05/29] v0.16.0

- 改行が反映されないの修正
- 連続改行をまとめる機能搭載
- オートリンク実装
- 「本文中にURLがあると拒絶」実装
- 「本文中に日本語がないと拒絶」実装
- 「拒絶する文字列」「使用できない名前」実装
- 「AとBが両方あったら拒絶」実装
- 「名前」「本文」「題名」必須、および長さの最大文字数実装
- 「拒絶するホスト」実装
  - セキュリティ関連はこれでOKかなあ

### [2020/05/29] v0.15.0

- 「データベース上からデータは消えるが画像ファイルが消えない」解決
- 画像がないスレが建てられるのを（テーマレベルで）阻止
- おまけテーマは落ち着くまで削除

### [2020/05/29] v0.14.1

- 動画保存しない設定ができなかったの修正
- その他テーマのエラー修正

### [2020/05/29] v0.14.0

- しぃペインター対応！！！！！！！！(要config再設定)

#### 今後の予定

- 続きから描く対応 (差し替え/新規投稿)
- 記事編集
- セキュリティ

### [2020/05/29] v0.13.11

- 削除くんが動くようになった

### [2020/05/29] v0.13.10

- 問題整理

### [2020/05/29] v0.13.9

- コード整理
- しぃペインターを使う準備までできた

### [2020/05/29] v0.13.8

- 管理画面に画像リンク追加
- config整理
  - 動画保存専用ディレクトリの廃止
- しぃペインター実装に向け各種ファイル同梱

### [2020/05/29] v0.13.7

- 「そうだね」実装
- テーマ修正

### [2020/05/28] v0.13.6

- 描画時間表示対応
- メールのリンク対応

### [2020/05/28] v0.13.5

- テーマのエラー修正
  - しぃペインター対応始めるかなー

### [2020/05/28] v0.13.3

- レス省略設定のデフォルトが間違っていたの修正(テーマ)

### [2020/05/28] v0.13.2

- 1ページ内のレス表示数設定(要config再設定)
  - 省略の法則性が分からない…

### [2020/05/28] v0.13.1

- ページング処理大丈夫でした configのデフォルト値整理

### [2020/05/28] v0.13.0

- 最大ログ数実装（したつもり）
- ~~ページング処理がうまくいってない~~
- config.php更新。互換性がなくなっています。

### [2020/05/28] v0.12.0

- age/sage機能実装！ age/sage機能実装！
  - 「これ以上レスがあると強制sage」は実装できないかも

### [2020/05/28] v0.11.2

- 管理モードのpassがない時のエラー修正

### [2020/05/28] v0.11.1

- テーマに管理モードinのリンクを付けた

#### 今後の予定

- 続きから描く対応
- しぃペインター実装　→　OK！
- age機能　→　OK！

### [2020/05/28] v0.11.0

- 1ファイルにまとめた！
- sage機能のためにまたデータファイル形式変わりました…すみません

### [2020/05/27]

- .htaccess追加
- サンプルのリンク修正

### [2020/05/27] v0.10.0

- MySQLからSQLiteに変更
- setupdb.php、dbconnect.php廃止
- `$usercode`関連のエラーが出る（ファイルを1つにまとめたら治るかも）→ 治った

### [2020/05/27] v0.9.1

- smartyを最新版に更新
- NEO更新

#### 今後の予定

- ファイルを一つにまとめる　→ OK
- 続きから描く対応
- しぃペインター実装
- MySQLからSQLiteに変更 → OK

### [2019/05/19] v0.9.0

- smartyに変更。

### [2019/05/19] スキンv0.2.0

- ダイナミックパレット搭載。

### [2019/05/17] v0.8.5

- スキン修正x2。

### [2018/12/28] v0.8.5

- URL自動リンク実装。

### [2018/12/27] v0.8.4

- 管理モードスクリプトにレスが表示されないの修正。

### [2018/12/27] v0.8.3

- 削除ができなくなっていたのをさらに修正。

### [2018/12/27] v0.8.2

- 削除ができなくなっていたのを修正。

### [2018/12/27] v0.8.1

- 日付が表示されないの修正。

### [2018/12/27] v0.8.0

- 完全にレス対抗。
- 最後のデータベース形式変更にしたい。テーブルを2つ使っています。

### [2018/12/27] v0.7.2

- シェアボタン修正。

### [2018/12/26] v0.7.1

- レスの付いていない記事を削除すると返信ボタンが消えてしまう問題に対処。
- レスが付くまで削除できなくしただけですけどね。

### [2018/12/26] v0.7.0

- 管理モード搭載。
- ~~noeadmin.php?adminpass=(管理者パス)をURLに直打ちして入ってください。~~
- 一時削除になっているレスも見れます。
- この画面からの削除は管理者パスでもデータベースからの削除になります。

### [2018/12/26] v0.6.2

- 返信ボタン増殖を阻止。

### [2018/12/26] v0.6.1

- ページング周りをふたば式にできるように。

### [2018/12/26] v0.6.0

- pch保存/再生仮対応。
- データベース形式がまた変わりました。お手数ですが作り直してください。これ以上は変わらないと思いたい。

### [2018/12/26] v0.5.2

- シェアボタンがうまく表示されないの修正。

### [2018/12/26] v0.5.1

- レスにSNSでシェアするボタン実装。
- configに追加項目があります。

### [2018/12/26] v0.5.0

- ページング機能実装。

#### 予定

- 管理モード　→ OK
- URL自動リンク → OK?
- pch保存/再生 → OK
- セキュリティ向上

### [2018/12/25] v0.4.0

- 削除機能実装。記事番号が投稿者passか管理passと一致したときに削除します。
- 管理passと一致のときはデータベース上からは削除しません。
- 伴ってパスワードの暗号化を password_hash() に変更。

### [2018/12/25] v0.3.1

- よくわからないエラーを出なくした。

### [2018/12/24] v0.3.0

- レスを付けられるようにした
- データベース形式が変更になっているのでテーブルを削除してください。
- ディレクトリもセットアップスクリプトで作るように設定。

#### 予定

- 削除機能
- ページング
- よくわからないエラー削除

### [2018/12/24] v0.2.0

- ログをmySQLのデータベースに変更

### [2018/09/20] v0.1.1

- スレッド式にするためにログに通し番号を付けた（ログの形式が変わりました注意）

### [2018/09/20] v0.1.0

- 画像の投稿ができるようになりました！！やった！！！！！！

#### 予定

- スレッド式にする　→ OK
- ログ数を決めて何ページかに分ける → OK

### [2018/09/20] v0.0.3

- 一時保存フォルダに画像を保存するまでできた（最初の画面に反映できない）
- スキンをフォルダに格納

### [2018/09/20] v0.0.2

- 投稿画面を出すまでできた（保存はできない）

### [2018/09/19] v0.0.1

- プロジェクト開始
- とりあえずSkinnyを動かせる掲示板を作った（削除も何もできないけど）
