# noe_plugin

お絵かき掲示板noe-boardのためのプラグインです。

## noe_newimg.php

データベースの最新画像を表示します。

### 設置方法

1. noe-boardをダウンロードして設置します。
2. noe_newimg.phpを index.phpと同じディレクトリにアップロードします。

### 特徴

noe-boardに投稿された一番新しい画像を取得して静的HTMLファイルに表示します。 掲示板に入らなくても新着画像を見ることができるようになります。

### 使い方

- 画像と同じようにこのphpのファイルをimgタグで呼び出します。
- HTMLファイルに `<img src="https://hoge.ne.jp/bbs/noe_newimg.php" alt="" width="300">` のように書きます。
- 画像が無い時にデフォルト画像を表示させる事もできます。

## 履歴

### [2020/06/07] lot.200607

- modified順からpicfile順に
  - modified順だと親コメント更新で順番が変わっていた

### [2020/06/04] lot.200604a

- id順からmodified順に（画像差し替えに対応）

### [2020/06/04] lot.200604

- Githubに公開