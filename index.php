<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>実行ファイル</title>
    </head>
    <body>
        <?php
        require_once './config.inc.php';
        require_once './Card.php';
        require_once './Indexer.php';

        /**
         * インデックスリストのみ出力する
         */
        $IndexOnly = FALSE;
        // 索引
        foreach (Indexer::$KindUnionLoop as $union) {
            foreach (Indexer::$KindType as $key => $type) {
                // 6ユニオン6カードタイプのインデクサインスタンスを生成する
                $indexes[] = new Indexer($union, $key);
            }
        }
        $indexes[] = new Indexer("材料");
        $indexes[] = new Indexer("タロット");
        $indexes[] = new Indexer("その他");
        $indexes[] = new Indexer("マルチ");
        // episodeインデクサ．エピソード情報なしは引数を与えない．"na"
         $indexes[] = new Indexer();
        // ep0はch2まで
        $indexes[] = new Indexer(0, 0);
        $indexes[] = new Indexer(0, 1);
        $indexes[] = new Indexer(0, 2);
        // ep1以降はch4まで
        for ($ep = 1; $ep < LatestEpisode; $ep++) {
            for ($ch = 0; $ch <= 4; $ch++) {
                $indexes[] = new Indexer($ep, $ch);
            }
        }
        // 最新epの実装状態はconfig.inc.phpで設定する
        for($ch = 0; $ch <= LatestEpisodeChapter; $ch++)
        {
            $indexes[] = new Indexer(LatestEpisode, $ch);
        }
        // DBアクセス
        $pdo = new PDO("sqlite:" . DatabaseFilename);
        // スキル文面なし調査 1780
        // レイドボスカード 875
        // 謎のカード「か」．E_name a98 - a107 951-960
        // 未実装ヒーロー 1325-1354
        // 次期実装カード 2011 以降
        // カード癒合イベント材料 1555-1558
        // 未実装タロットジャスティス 572
        $sql = "select * from CARD_MASTER, CARD_SEARCH_TEXT_LOCALE_content where seq_Card = c0seq_Card and c1locale = 10003 and seq_Card > 0";
        $stmt = $pdo->query($sql);
        echo '<pre>';
        for (;;) {
//        for ($i = 0; $i < 1000; $i++) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row === FALSE)
                break;
            $card = new Card($row);
            $outputFlag = $card->isOutput($ignoreCards, $outputLimit);
            if ($outputFlag) {
                //echo $card->getCardHtml();
                //echo $card->getCardHtmlPiece();
                // インデックスリスト更新
                foreach ($indexes as $index) {
                    $index->update($card);
                }
                // カード単体情報ページ出力制御
                if ($IndexOnly === TRUE) {
                    continue;
                }
                $card->putfile();
            }
        }
        echo '</pre>';
        ?>
    </body>
</html>
