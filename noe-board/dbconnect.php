<?php

// DBへ接続
try {
	$db = new PDO("mysql:dbname=".LOGDB.";host=".DBHOST.";charset=utf8",DBUSER,DBPASS);
} catch (PDOException $e) {
	echo "DB接続エラー:" .$e->getMessage();
}

?>