function c(resnames){
    //名前を取得
    var dest = document.getElementsByClassName('resname');
    var resnames = "";
    for(var i = 0; i < dest.length; ++i){
        resnames = resnames + dest[i].innerHTML + "さん "; 
    }
    // テキストエリアを用意する
    var copyFrom = document.createElement("textarea");
    // テキストエリアへ値をセット
    copyFrom.textContent = resnames;
    // bodyタグの要素を取得
    var bodyElm = document.getElementsByTagName("body")[0];
    // 子要素にテキストエリアを配置
    bodyElm.appendChild(copyFrom);
 
    // テキストエリアの値を選択
    copyFrom.select();
    // コピーコマンド発行
    var retVal = document.execCommand('copy');
    // 追加テキストエリアを削除
    bodyElm.removeChild(copyFrom);
    // 処理結果を返却
    return retVal;
}