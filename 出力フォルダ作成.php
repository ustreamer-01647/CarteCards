<?php

// 出力フォルダ
mkdir(OutputDir);
// カード単体ページフォルダ
mkdir(OutputDir . "na");
mkdir(OutputDir . "0-0");
mkdir(OutputDir . "0-1");
mkdir(OutputDir . "0-2");
for ($ep = 1; $ep < LatestEpisode; $ep++) {
    // 4はチャプター最大値
    for ($ch = 0; $ch <= 4; $ch++) {
        mkdir(OutputDir . $ep . "-" . $ch);
    }
}
for ($ch = 0; $ch <= LatestEpisodeChapter; $ch++) {
    mkdir(OutputDir . LatestEpisode . "-" . $ch);
}

require_once './Indexer.php';
// インデックスページ出力フォルダ
mkdir(OutputDir.Indexer::$OutputDir);
mkdir(OutputDir.Indexer::$OutputDir. "/aspire");
mkdir(OutputDir.Indexer::$OutputDir. "/kaideron");
mkdir(OutputDir.Indexer::$OutputDir. "/sierrion");
mkdir(OutputDir.Indexer::$OutputDir. "/saike");
mkdir(OutputDir.Indexer::$OutputDir. "/arken");
mkdir(OutputDir.Indexer::$OutputDir. "/grey");
mkdir(OutputDir.Indexer::$OutputDir. "/other");
mkdir(OutputDir.Indexer::$OutputDir. "/episode");
?>
