<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Card
 *
 * @author paul
 */
require_once './config.inc.php';

class Card {

    static $TableHeader = "<table class=\"card\">\n";
    static $TableFooter = "</table>\n";

    /**
     * カードタイプ "HH" => "ヒーロー"
     * @var string[] カードタイプ
     */
    static $カードタイプ = array("HH" => "ヒーロー", "CC" => "クリーチャー", "MC" => "キャスト", "MB" => "バースト", "IW" => "武器", "ID" => "防具", "IA" => "装飾具", "TT" => "トラップ", "DD" => "シャード", "TA" => "タロット", "BB" => "ボス",
        // 材料
        "^E" => "材料：エピソード", "^U" => "材料：ユニオン", "^K" => "材料：タイプ", "^R" => "材料：レアリティ", "^P" => "材料：エピソード（イベント）", "^N" => "材料：ユニオン（イベント）", "^Y" => "材料：タイプ（イベント）", "^A" => "材料：レアリティ（イベント）");

    /**
     * @var string[] ヒーロー職業
     */
    static $職業 = array("W" => "戦士", "M" => "魔法師", "B" => "魔剣士");

    /**
     * n => ユニオン
     * @var string[] ユニオン 
     */
    static $ユニオン = array(1 => "エスファイア", 3 => "カイデロン", 5 => "シエリオン", 7 => "シェイク", 9 => "アルケン", 10 => "グレー",
        // マルチユニオン
        101 => "エスファイア/カイデロン", "エスファイア/シエリオン", "エスファイア/シェイク", "エスファイア/アルケン", "カイデロン/シエリオン", "カイデロン/シェイク", "カイデロン/アルケン", "シエリオン/シェイク", "シエリオン/アルケン", "シェイク/アルケン",
        // マルチユニオン（グレー）
        "エスファイア/グレー", "カイデロン/グレー", "シエリオン/グレー", "シェイク/グレー", "アルケン/グレー",
        // その他
        999 => "タロット", 1000 => "材料");

    /**
     * レアリティ英語名
     * @var string レアリティ
     */
    static $レアリティ = array("BASIC", "COMMON", 3 => "UNCOMMON", 5 => "RARE", 6 => "HYPER", 7 => "RAID", 10 => "EPIC", 20 => "C.E.", 32 => "ELITE", 50 => "ARENA");

    /**
     * レアリティカタカナ表記
     * @var string[] レアリティのカタカナ表記
     */
    static $レアリティJp = array("ベーシック", "コモン", 3 => "アンコモン", 5 => "レア", 6 => "ハイパー", 7 => "レイド", 10 => "エピック", 20 => "コレクターズエディション", 32 => "エリート", 50 => "アリーナ");

    /**
     * 日本語ロケール番号
     * @var int 日本語ロケール番号
     */
    static $LocaleJp = 10003;

    /**
     * 名前付きパッシブアクションの名称番号の列名
     * @var string[] カラム名
     */
    static $PA_namedKeys = array("PA_named1", "PA_named2", "PA_named3", "PA_named4", "PA_named5", "PA_named6", "PA_named7", "PA_named8", "PA_named9", "PA_named10");

    /**
     * 名前付きパッシブアクションの内容番号の列名
     * @var string[] カラム名
     */
    static $PA_codeKeys = array("PA_code1", "PA_code2", "PA_code3", "PA_code4", "PA_code5", "PA_code6", "PA_code7", "PA_code8", "PA_code9", "PA_code10");

    /**
     * アクティブアクションの内容番号の列名
     * @var string[] カラム名
     */
    static $AA_codeKeys = array("AA_code1", "AA_code2", "AA_code3", "AA_code4", "AA_code5");

    /**
     * 各種アクションの変数スロット列名
     * @var string[] カラム名
     */
    static $SlotKeys = array("slot1", "slot2", "slot3", "slot4", "slot5");

    /**
     * アクティブアクションの内容の列名
     * @var string[] カラム名
     */
    static $aaKeys = array("c5AA1_description", "c6AA2_description", "c7AA3_description", "c8AA4_description", "c9AA5_description");

    /**
     * 名前付きパッシブアクションの名称の列名
     * @var string[] カラム名
     */
    static $paNameKeys = array("c10PA1_name", "c12PA2_name", "c14PA3_name", "c16PA4_name", "c18PA5_name");

    /**
     * 名前付きパッシブアクションの内容の列名
     * @var string[] カラム名
     */
    static $paKeys = array("c11PA1_description", "c13PA2_description", "c15PA3_description", "c17PA4_description", "c19PA5_description");

    /**
     * 名前なしパッシブアクションの内容の列名
     * @var string[] カラム名
     */
    static $plKeys = array("c20P1L_description", "c21P2L_description", "c22P3L_description", "c23P4L_description", "c24P5L_description");
    private $seq_Card; // ユニーク
    private $theme; // テーマ．未使用
    private $episode;
    private $chapter;
    private $kindCode; // カードタイプ
    private $cardNum; // エピソード上での通し番号
    private $tarotNum; // タロットカード番号
    private $artist; // イラストレーター番号
    private $cardUnion; // ユニオン番号
    private $E_name; // カード英語名
    private $rarity; // レアリティ番号
    private $cost; // マナコスト
    private $creatureType; // クリーチャー種族
    private $creatureAttribute; // 別ユニオンペナルティー
    private $ap;
    private $hp;
    private $kindHero; // ヒーロー職業．戦士，魔法師，魔剣士
    private $hasCE; // CEカードのseq_Card番号
    private $AA_code_basic;
    private $PA_named; // 名前付きパッシブアクション
    private $PA_code; // 名前無しパッシブアクション
    private $AA_code; // アクティブアクション
    private $destroyEffect; // 破壊時効果
    private $landingEffect; // 設置時効果
    private $status; // 不明
    private $E_nameJp; // カード日本語名
    private $creatureTypeJp; // 種族日本語名
    private $paNames; // PA名称
    private $paTexts; // PAテキスト
    private $plTexts; // PLテキスト
    private $aaTexts; // AAテキスト
    private $actions; // スキルテキスト
    // カードHTML
    private $cardHtml;
    // カードHTML小片
    private $cardHtmlPiece;
    // スキルHTML
    private $skillHtml;

    /**
     * エピソード
     * @return int
     */
    function getEpisode() {
        if (!is_null($this->episode))
            return $this->episode;
    }

    /**
     * チャプター
     * @return int
     */
    function getChapter() {
        if (!is_null($this->chapter))
            return $this->chapter;
    }

    /**
     * エピソードチャプター
     * @return string episodex-x
     */
    function getEpisodeChapter() {
        // 両情報が格納されている場合に限る
        if (!is_null($this->episode) && !is_null($this->chapter))
            return "episode" . $this->episode . "-" . $this->chapter;
    }

    /**
     * エピソードチャプター
     * この関数はファイル階層構造整理のため使用する
     * @return string "x-x"．または"na"
     */
    function getEpisodeChapterSimple() {
        // 両情報が格納されている場合はep-cp
        if (!is_null($this->episode) && !is_null($this->chapter))
            return $this->episode . "-" . $this->chapter;
        else
            return "na";
    }

    /**
     *  APx/HPx
     */
    function getApHp() {
        // HPが1以上の場合に限る
        if (($this->hp) > 0)
            return "AP" . $this->ap . "/HP" . $this->hp;
    }

    /**
     * カードタイプ日本語名
     * @return type
     */
    function getカードタイプ() {
        if (!empty($this->kindCode))
            return Card::$カードタイプ[$this->kindCode];
    }

    function get職業() {
        if (!empty($this->kindHero))
            return Card::$職業[$this->kindHero];
    }

    function get職業Html() {
        if (!empty($this->kindHero))
            return " [" . $this->get職業() . "]";
    }

    /**
     * ユニオン日本語名
     * @return type
     */
    function getユニオン() {
        if (!empty($this->cardUnion))
            return Card::$ユニオン[$this->cardUnion];
    }

    function getレアリティ() {
        if (!is_null($this->rarity))
            return Card::$レアリティ[$this->rarity];
    }

    function get種族() {
        if (!empty($this->creatureTypeJp))
            return $this->creatureTypeJp;
    }

    /**
     * スキル
     * @return string 
     */
    function getSkillHtml() {
        return $this->skillHtml;
    }

    private function setSkillHtml() {
        $html = "";
        if (is_array($this->actions)) {
            foreach ($this->actions as $action) {
                // 空文字列ならスキップする
                $action = trim($action);
                if ($action === "")
                    continue;
                else
                    $html .= $action . "<br/>\n";
            }
            $html = preg_replace("/\<br\/\>\n$/", "\n", $html);
        }
        $this->skillHtml = $html;
    }

    /**
     * カード単体ページ用HTMLデータ
     * @return string table要素
     */
    function getCardHtml() {
        return $this->cardHtml;
    }

    private function setCardHtml() {
        $html = Card::$TableHeader;
        $html .= "<tr><th>レアリティ</th><td>" . $this->getレアリティ() . "</td></tr>\n";
        $html .= "<tr><th>エピソード</th><td>" . $this->getEpisodeChapter() . "</td></tr>\n";
        $html .= "<tr><th>ユニオン</th><td>" . $this->getユニオン() . "</td></tr>\n";
        $html .= "<tr><th>カード名</th><td>" . $this->E_nameJp . "</td></tr>\n";
        $html .= "<tr><th>カードタイプ</th><td>" . $this->getカードタイプ() . $this->get職業Html() . "</td></tr>\n";
        $html .= "<tr><th>レベル</th><td>" . $this->cost . "</td></tr>\n";
        $html .= "<tr><th>種族</th><td>" . $this->get種族() . "</td></tr>\n";
        $html .= "<tr><th>AP/HP</th><td>" . $this->getApHp() . "</td></tr>\n";
        $html .= "<tr><th>別ユニオンペナルティー</th><td>" . $this->creatureAttribute . "</td></tr>\n";
        $html .= "<tr><th>スキル</th><td>" . $this->getSkillHtml() . "</td></tr>\n";
        // 以下3項目は省略する
        /*
          $html .= "<dt>フレーバーテキスト</dt><dd>".$this->getFravortext()."</dd>\n";
          $html .= "<dt>カードイラストレーター</dt><dd>".$this->getIllustrator()."</dd>\n";
          $html .= "<dt>イラストレーターのブログ</dt><dd>".$this->getIllustratorsLink()."</dd>\n";
         */
        $html .= Card::$TableFooter;

        $this->cardHtml = $html;
    }

    /**
     * 複数列の情報を配列に保存する
     * @param mixed $src DBの1レコード
     * @param array $code 保存先
     * @param string[] $keys 列名
     */
    private function columns2array($src, &$code, $keys) {
        foreach ($keys as $key) {
            $code[] = $src[$key];
        }
    }

    function __construct($dbRow) {
        $this->seq_Card = (int) $dbRow['seq_Card'];
        $this->theme = (int) $dbRow['theme'];
        $this->episode = $dbRow['episode'];
        if (isset($this->episode))
            $this->episode = (int) $this->episode;
        $this->chapter = $dbRow['chapter'];
        if (isset($this->chapter))
            $this->chapter = (int) $this->chapter;
        $this->kindCode = $dbRow['kindCode'];
        $this->cardNum = $dbRow['cardNum'];
        $this->tarotNum = $dbRow['tarotNum'];
        $this->artist = (int) $dbRow['artist'];
        $this->cardUnion = (int) $dbRow['cardUnion'];
        $this->E_name = $dbRow['E_name'];
        $this->rarity = (int) $dbRow['rarity'];
        $this->cost = $dbRow['cost'];
        $this->creatureType = (int) $dbRow['creatureType'];
        $this->creatureAttribute = (int) $dbRow['creatureAttribute'];
        $this->ap = $dbRow['AP'];
        $this->hp = $dbRow['HP'];
        $this->kindHero = $dbRow['kindHero'];
        $this->hasCE = (int) $dbRow['hasCE'];

        // レイドボスはスキル情報を出力しない
        $this->skillHtml = "";
        if ("BB" !== $this->kindCode) {
            $this->AA_code_basic = $dbRow['AA_code_basic'];
            // panamed, pacode, aacode
            $this->columns2array($dbRow, $this->PA_named, Card::$PA_namedKeys);
            $this->columns2array($dbRow, $this->PA_code, Card::$PA_codeKeys);
            $this->columns2array($dbRow, $this->AA_code, Card::$AA_codeKeys);
            // PA名称入力
            $this->columns2array($dbRow, $this->paNames, Card::$paNameKeys);
            // PAテキスト入力
            $this->columns2array($dbRow, $this->paTexts, Card::$paKeys);
            // PLテキスト入力
            $this->columns2array($dbRow, $this->plTexts, Card::$plKeys);
            // AAテキスト入力
            $this->columns2array($dbRow, $this->aaTexts, Card::$aaKeys);
            $this->getPAText();
            $this->getPLText();
            $this->getAAText();
            $this->setSkillHtml();
        }
        $this->destroyEffect = $dbRow['destroyEffect'];
        $this->landingEffect = $dbRow['landingEffect'];
        $this->status = (int) $dbRow['status'];

        $this->E_nameJp = $dbRow['c3Z_name'];
        $this->creatureTypeJp = $dbRow['c4typeText'];

        $this->setCardHtml();
        $this->setCardHtmlPiece();
    }

    /**
     * カードテキストの変数を解決する
     * @param string $text カードテキスト
     * @param array $slots 変数スロット
     */
    private function replaceActionText(&$text, $slots) {
        foreach ($slots as $key => $slot) {
            // テキスト中の変数は $1$ から開始するため $key には 1 を加算する
            $text = str_replace("\$" . ($key + 1) . "\$", "$slot", $text);
        }
    }

    /**
     * 名前付きパッシブアクション
     * @param PDO $pdo
     */
    private function getPAText() {

        foreach ($this->PA_named as $key => $panamed) {
            // 値の存在，または0より大きい値かを調べる
            if (empty($panamed) || $panamed < 0)
                break;
            // テキストにドルマークを含む場合は，$n$があるとみなし，展開する
            if (!(FALSE === strpos($this->paTexts[$key], "\$") )) {
                $pdo = new PDO("sqlite:" . DatabaseFilename);
                $sql = "select * from ACTION_PASSIVE where PA_id = " . $panamed;
                $stmt = $pdo->query($sql);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $slots = array();
                $this->columns2array($row, $slots, Card::$SlotKeys);
                $this->replaceActionText($this->paTexts[$key], $slots);
            }
            $this->actions[] = $this->paNames[$key];
            $this->actions[] = $this->paTexts[$key];
        }
    }

    /**
     * 名前なしパッシブアクション
     */
    private function getPLText() {

        foreach ($this->PA_code as $key => $pacode) {
            // 値の存在，または0より大きい値かを調べる
            if (empty($pacode) || $pacode < 0)
                break;
            // テキストにドルマークを含む場合は，$n$があるとみなし，展開する
            if (!(FALSE === strpos($this->plTexts[$key], "\$") )) {
                $pdo = new PDO("sqlite:" . DatabaseFilename);
                $sql = "select * from ACTION_PASSIVE where PA_id = " . $pacode;
                $stmt = $pdo->query($sql);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $slots = array();
                $this->columns2array($row, $slots, Card::$SlotKeys);
                $this->replaceActionText($this->plTexts[$key], $slots);
            }
            $this->actions[] = $this->plTexts[$key];
        }
    }

    /**
     * アクティブアクション
     */
    private function getAAText() {

        foreach ($this->AA_code as $key => $aacode) {
            // 値の存在，または0より大きい値かを調べる
            if (empty($aacode) || $aacode < 0)
                break;
            // スキル使用コスト情報を参照するため，必ずデータベースにアクセスする
            $pdo = new PDO("sqlite:" . DatabaseFilename);
            $sql = "select * from ACTION_ACTIVE where AA_id = " . $aacode;
            $stmt = $pdo->query($sql);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $slots = array();
            $this->columns2array($row, $slots, Card::$SlotKeys);
            $this->replaceActionText($this->aaTexts[$key], $slots);
            // 選択式マジックのように，コスト情報の有無を基に分岐する
            if (isset($row['cost']))
                $this->actions[] = $row['cost'] . ": " . $this->aaTexts[$key];
            else
                $this->actions[] = $this->aaTexts[$key];
        }
    }

    /**
     * 
     * @param int $ignoreCards 無視するseq_Card番号群
     * @param int $outputLimit 出力する上限seq_Card番号
     * @return bool 出力すべき情報ならTRUE
     */
    function isOutput($ignoreCards, $outputLimit) {
        // 上限値より大きい場合，出力しない
        if ($outputLimit < $this->seq_Card)
            return FALSE;
        // 該当番号であれば，出力しない
        foreach ($ignoreCards as $seq_Card) {
            if ($seq_Card === $this->seq_Card)
                return FALSE;
        }
        return TRUE;
    }

    /**
     * 索引用tr要素
     * @return string 
     */
    private function setCardHtmlPiece() {
        $html = "<tr>";
        $html .= "<td>" . $this->getEpisodeChapterSimple() . "</td>";
        $html .= "<td>" . $this->cardNum . "</td>";
        $html .= "<td>" . $this->getレアリティ() . "</td>";
        $html .= "<td>" . $this->getAnchoredName() ."(". $this->creatureAttribute . ")</td>";
        $html .= "<td>" . $this->cost . "</td>";
        $html .= "<td>" . $this->ap . "</td>";
        $html .= "<td>" . $this->hp . "</td>";
        $html .= "<td>" . $this->getカードタイプ() . "</td>";
        $html .= "<td>" . $this->getSkillHtml() . "</td>";
        $html .= "</tr>\n";
        $this->cardHtmlPiece = $html;
    }

    /**
     * インデクサに提供するカード情報
     * @return string tr要素
     */
    public function getCardHtmlPiece() {
        return $this->cardHtmlPiece;
    }

    /**
     * アンカー付きカード名
     * @return string <a href="root/ep-cp/id.html">name</a>
     */
    private function getAnchoredName() {
        // ../../ep-cp/id.html
        return "<a href=\"../../"
                . $this->getEpisodeChapterSimple() . "/"
                . $this->seq_Card . ".html\">"
                . $this->E_nameJp . "</a>";
    }

    /**
     * このカードがアイテムである場合，真
     * @return boolean
     */
    public function isItem() {
        if (0 === strpos($this->kindCode, "I")) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * このカードがマジックである場合，真
     * @return boolean
     */
    public function isMagic() {
        if (0 === strpos($this->kindCode, "M")) {
            return TRUE;
        }
        return FALSE;
    }

    public function putfile() {
        // ファイル名は ./ep/id.html
        $filename = OutputDir . $this->getEpisodeChapterSimple() . "/" . $this->seq_Card . ".html";
        $html = <<< EOT
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>{$this->E_nameJp}</title>
        <link rel="stylesheet" href="../common.css">
    </head>
    <body>
        <h1>{$this->E_nameJp}</h1>
        <p><a href="../">カードインデックスページへ戻る</a></p>
EOT;
        $html .= $this->getCardHtml();
        $html .= Copyright . "\n";
        $html .= <<< EOT
    </body>
</html>
EOT;
        file_put_contents($filename, $html);
    }

}

?>
