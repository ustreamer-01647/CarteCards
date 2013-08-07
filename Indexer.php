<?php

require_once './config.inc.php';
require_once './Card.php';

/**
 * インデックスページを作る
 *
 * @author paul
 */
class Indexer {

    static $TableHeader = <<< EOT
<table> 
<thead><tr><th>EP</th><th>No.</th><th>Rarity</th><th>カード名</th><th>レベル</th><th>AP</th><th>HP</th><th>カードタイプ</th><th>スキル</th></tr></thead>
<tbody>
EOT;
    static $TableFooter = "</tbody></table>\n";

    /**
     * 出力するディレクトリ
     */
    static $OutputDir = "index";

    /**
     * ユニオン日本語名 => 英名
     * タロット，マルチユニオン，その他
     * @var type 
     */
    static $KindUnion = array("エスファイア" => "aspire", "カイデロン" => "kaideron", "シエリオン" => "sierrion", "シェイク" => "saike", "アルケン" => "arken", "グレー" => "grey", "材料" => "parts", "タロット" => "tarot", "その他" => "other", "マルチ" => "multi");

    /**
     * 作るインデックスのユニオンforeach用
     * @var type 
     */
    static $KindUnionLoop = array("エスファイア", "カイデロン", "シエリオン", "シェイク", "アルケン", "グレー");

    /**
     * カードタイプ日本語名 => 英名
     * @var type 
     */
    static $KindType = array("ヒーロー" => "hero", "クリーチャー" => "creature", "マジック" => "magic", "アイテム" => "item", "トラップ" => "trap", "シャード" => "shard");
    static $KindHero = array("戦士", "魔法師", "魔剣士");
    static $KindMagic = array("キャスト", "バースト");
    static $KindItem = array("武器", "防具", "装飾具");
    // インデックステーブル
    private $indexTableBody;
    // 出力するユニオン
    private $union;
    // 出力するカードタイプ
    private $type;
    // 出力するエピソード
    private $episode;
    // 出力するチャプター
    private $chapter;

    /**
     * インデクサタイプ
     * @var bool TRUEならエピソードインデクサ
     */
    private $isEpisodeIndexer = FALSE;

    /**
     * ユニオン情報
     * @return string ユニオン文字列
     */
    public function getUnion() {
        return $this->union;
    }

    /**
     * カードタイプ．ユニオン情報によってはNULLかもしれない
     * @return string カードタイプ文字列
     */
    public function getType() {
        return $this->type;
    }

    /**
     * コンストラクタ
     * 作るインデックスの条件を与える
     * 両引数が数値である場合は，episode-chapterと認識する
     * 両引数がNULLである場合は，エピソード情報なしと認識する
     * @param type $union ユニオン日本語名
     * @param type $type カードタイプ日本語名．タロットなど，分類しない場合は空
     */
    public function __construct($union = NULL, $type = NULL) {
        if (is_null($union)) {
            // エピソードインデクサ
            // エピソード情報なし
            $this->isEpisodeIndexer = TRUE;
            $this->episode = $this->chapter = NULL;
        } else if (is_int($union) && is_int($type)) {
            // エピソードインデクサ
            $this->isEpisodeIndexer = TRUE;
            $this->episode = $union;
            $this->chapter = $type;
        } else {
            // ユニオン認識
            $this->union = $union;
            // カードタイプ認識
            $this->type = $type;
        }
    }

    function getIndexTable($tableBody) {
        return Indexer::$TableHeader . $tableBody . Indexer::$TableFooter;
    }

    function output() {
        // ファイル名とページタイトル決定
        $filename = $pagetitle = "";
        if ($this->isEpisodeIndexer) {
            // エピソードインデクサである場合
            $filename = OutputDir . Indexer::$OutputDir . "/" . "episode/";
            if (is_null($this->episode)) {
                // index/episode/na.html
                $filename .= "na.html";
                $pagetitle = "エピソード情報なし";
            } else {
                // index/episode/x-x.html
                $filename .= $this->episode . "-" . $this->chapter . ".html";
                $pagetitle = "エピソード" . $this->episode . "-" . $this->chapter;
            }
        } else {
            // カードタイプ情報の有無で分岐する
            if ($this->type === NULL) {
                // index/other/tarot.html
                $filename = OutputDir . Indexer::$OutputDir . "/"
                        . "other/"
                        . Indexer::$KindUnion[$this->union] . ".html";
                $pagetitle = $this->union;
            } else {
                // index/aspire/creature.html
                $filename = OutputDir . Indexer::$OutputDir . "/"
                        . Indexer::$KindUnion[$this->union] . "/"
                        . Indexer::$KindType[$this->type] . ".html";
                $pagetitle = $this->union . $this->type;
            }
        }
        $html = <<< EOT
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>{$pagetitle}</title>
        <link rel="stylesheet" href="../../common.css">
    </head>
    <body>
        <h1>{$pagetitle}</h1>
        <p><a href="../../">カードインデックスページへ戻る</a></p>
        <p>カード名後ろの数値は別ユニオンペナルティー</p>
EOT;
        // アイテムまたはマジックの場合，節を分けている
        if ($this->type === "マジック") {
            foreach (Indexer::$KindMagic as $kind) {
                $html .= "<h2>" . $kind . "</h2>\n";
                $html .= $this->getIndexTable($this->indexTableBody[$kind]);
            }
        } elseif ($this->type === "アイテム") {
            foreach (Indexer::$KindItem as $kind) {
                $html .= "<h2>" . $kind . "</h2>\n";
                $html .= $this->getIndexTable($this->indexTableBody[$kind]);
            }
        } else {
            $html .= $this->getIndexTable($this->indexTableBody);
        }
        $html .= Copyright . "\n";
        $html .= <<< EOT
    </body>
</html>
EOT;
        file_put_contents($filename, $html);
    }

    /**
     * インデックスをファイル出力する
     */
    function __destruct() {
        $this->output();
    }

    /**
     * インデクサが対象としているカードならば，そのカード情報を読み出し，追加格納する
     * @param Card $card カード情報
     * @return type
     */
    function update(Card $card) {
        if ($this->isEpisodeIndexer) {
            // エピソードインデクサである場合
            if (is_null($this->episode) && $card->getEpisodeChapterSimple() === "na") {
                // エピソードなし条件を確認
                $this->add($card);
            } else if ($this->episode === $card->getEpisode() && $this->chapter === $card->getChapter()) {
                // エピソード情報条件一致を確認
                $this->add($card);
            }
        } else {
            // レイドボスは「その他」
            if ($this->union === "その他" && $card->getカードタイプ() === "ボス") {
                $this->add($card);
                return;
            }
            // このインデクサが「マルチ」ユニオンカード用である場合
            if ($this->union === "マルチ") {
                // ユニオン名の途中に"/"があればマルチユニオン
                if (strpos($card->getユニオン(), "/") > 0) {
                    $this->add($card);
                    return;
                }
            }
            // ユニオン一致確認
            // ユニオン情報が合致する場合は，5ユニオンとグレーとタロットと材料
            if (FALSE === strpos($card->getユニオン(), $this->union)) {
                // ユニオンが合致しない場合，何もしない
                return;
            } else {
                // このインデクサがタロットまたは材料の場合
                if ($this->union === "タロット" || $this->union === "材料") {
                    // タロットと材料は細分化しない．追加し，終える
                    $this->add($card);
                    return;
                }
                // カードタイプ表記統合
                $type = $card->getカードタイプ();
                if ($card->isMagic())
                // キャスト，バーストを「マジック」に置換する
                    $type = "マジック";
                else if ($card->isItem())
                // 武器，防具，装飾具を「アイテム」に置換する
                    $type = "アイテム";
                // カードタイプ一致確認
                if ($this->type === $type) {
                    $this->add($card);
                    return;
                }
            }
        }
    }

    /**
     * 索引情報追加
     * @param Card $card カード情報
     */
    public function add(Card $card) {
        // アイテムまたはマジックの場合，そのページ内にて節を分ける
        if ($this->type === "マジック" || $this->type === "アイテム") {
            $this->indexTableBody[$card->getカードタイプ()].= $card->getCardHtmlPiece();
        } else {
            // 上記条件外では，単純に追加する
            $this->indexTableBody .= $card->getCardHtmlPiece();
        }
    }

}

?>
