<?php
// 定数宣言
const HAND_TYPE = array('グー', 'チョキ', 'パー');

const JANKEN_RESULT_MESSAGE = array (
    'WIN' => 'あなたのです勝ちです!',
    'LOSE' => 'あなたの負けです...',
    'DRAW' => 'あいこです',
);

const HAND_SELECT_MESSAGE = <<<EOD
あなたの手を選択して下さい:
'0' => 「グー」
'1' => 「チョキ」
'2' => 「パー」
EOD;

const RETRY_MESSAGE = <<<EOD
相手「もう一度しますか?」
'0' => 「はい」
'1' => 「いいえ」
EOD;


// 標準入力関数 && バリデーション実行
function selectHand() {
    // 選択メッセージ
    echo HAND_SELECT_MESSAGE . PHP_EOL;

    // 標準入力
    $userHand = trim(fgets(STDIN));

    // バリデーションチェック
    $check = check($userHand);

    if(!$check) {
        echo "※※ もう一度入力して下さい ※※" . PHP_EOL;
        return selectHand();
    }

    return (int)$userHand;
}


// バリデーション関数
function check($hand, $max = 2) {
    // 空チェック
    if(empty($hand) && $hand != 0) {
        return false;
    }
    // 数値チェック
    if(!is_numeric($hand) || $hand > $max) {
        return false;
    }
    return true;
}


// じゃんけん再戦関数
function jankenRetry() {
    // メッセージ表示
    echo PHP_EOL;
    echo RETRY_MESSAGE . PHP_EOL;
    
    // 標準入力 & バリデーション
    $retry_select = (int)trim(fgets(STDIN));
    $check = check($retry_select, 1);

    if(!$check) {
        echo "※※ もう一度入力して下さい ※※" . PHP_EOL;
        return jankenRetry();
    }

    // 再戦フラグ判定
    if($retry_select === 0) {
        return jankenStart();

    }elseif($retry_select === 1) {
        echo "相手「ありがとうございました!」" . PHP_EOL;
    }

}


// じゃんけん実行関数
function jankenStart() {
    // メッセージ表示
    global $draw_flg;
    echo  PHP_EOL;

    if($draw_flg) {
        echo "相手「 あいこで... 」" . PHP_EOL;
        echo PHP_EOL;
        $draw_flg = false;

    } else {
        echo "相手「 ジャン...ケン... 」" . PHP_EOL;
        echo PHP_EOL;
    }

    $cpuHand = mt_rand(0, 2);
    $userHand = selectHand();

    echo PHP_EOL;
    echo "結果: ";

    // 勝敗判定
    echo "あなた:" . HAND_TYPE[$userHand] . " vs " . "相手:" . HAND_TYPE[$cpuHand] . PHP_EOL;
    $result = ($userHand - $cpuHand + 3) % 3;

    switch($result) {
        case 0:
            echo JANKEN_RESULT_MESSAGE["DRAW"] . PHP_EOL;
            $draw_flg = true;
            return jankenStart();
            break;

        case 1:
            echo JANKEN_RESULT_MESSAGE["LOSE"] . PHP_EOL;
            break;

        case 2:
            echo JANKEN_RESULT_MESSAGE["WIN"] . PHP_EOL;
            break;
    }
    // 再戦確認
    jankenRetry();
}

// 実行
jankenStart();
