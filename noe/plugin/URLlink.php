<?php

// URLエンコード
function URLlink( $value = null  ) {
    if (AUTOLINK == 1) {
        $value = preg_replace("{(https?|ftp|news)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)}","<a href=\"\\1\\2\" target=\"_blank\" rel=\"nofollow noopener noreferrer\">\\1\\2</a>",$value);
        return $value;
    }
    // '>'色設定
    $value = preg_replace("/(^|>)((&gt;|＞)[^<]*)/i", "\\1".RE_START."\\2".RE_END, $value);
    return $value;
}
