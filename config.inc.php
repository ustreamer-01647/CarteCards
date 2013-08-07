<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *  カードデータベースファイル
 */
define("DatabaseFilename", "fer.dat");
/**
 * ファイル出力先
 */
define("OutputDir" , "output/");
/**
 * 最新エピソード
 */
define("LatestEpisode", 6);
/**
 * 最新エピソードのチャプター
 */
define("LatestEpisodeChapter", 3);

// 無視するカードのseq_Card番号
// 未実装タロットJustice
$ignoreCards[] = 572;
// 謎のカード「a」E_name GrayとGgay
for ($n = 561; $n <= 569; $n++) {
    $ignoreCards[] = $n;
}
// 謎のカード「か」E_name a98 - a107
for ($n = 951; $n <= 960; $n++) {
    $ignoreCards[] = $n;
}
// ヒョン3号
$ignoreCards[] = 963;
// ヒョン4号
$ignoreCards[] = 964;
// 盗掘者
$ignoreCards[] = 965;
// 未実装ヒーロー
for ($n = 1325; $n <= 1354; $n++) {
    $ignoreCards[] = $n;
}
// カード融合イベント材料
for ($n = 1555; $n <= 1558; $n++) {
    $ignoreCards[] = $n;
}
// 出力してもよいカードのseq_Card上限値
// 2116 Ex. 不安定なホムンクルス
$outputLimit = 2116;

define("Copyright", "<address>Copyright (C) 2012 株式会社 OnNet. All Right Reserverd.</address>
        <address>Copyright (C) 2012 Ncrew Entertainment Co., Ltd. All Right Reserverd.</address>");
?>
