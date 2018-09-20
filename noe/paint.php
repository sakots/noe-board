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

$Skinny->SkinnyDisplay( $paintfile, $out );
exit;

?>
