<?php
$accessToken = getenv('LINE_CHANNEL_ACCESS_TOKEN');


//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$jsonObj = json_decode($json_string);

$type = $jsonObj->{"events"}[0]->{"message"}->{"type"};
//メッセージ取得
$text = $jsonObj->{"events"}[0]->{"message"}->{"text"};
//ReplyToken取得
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};
//userId取得
$userId =  $jsonObj->{"events"}[0]->{"source"}->{"userId"};

//メッセージ以外のときは何も返さず終了
if($type != "text"){
	exit;
}

//返信データ作成
/*if($text == '質問'){
  $response_format_text = [
    "type" => "template",
    "altText" => "こんにちわ 何かご質問あありますか？（はい／いいえ）",
    "template" => [
        "type" => "confirm",
        "text" => "こんにちわ 何かご質問あありますか？",
        "actions" => [
            [
              "type" => "message",
              "label" => "はい",
              "text" => "はい"
            ],
            [
              "type" => "message",
              "label" => "いいえ",
              "text" => "いいえ"
            ]
        ]
    ]
  ];
}else */if ($text == 'はい') {
  $response_format_text = [
    "type" => "template",
    "altText" => "質問の前に最寄り駅を選択してください",
    "template" => [
      "type" => "buttons",
      //"thumbnailImageUrl" => "https://" . $_SERVER['SERVER_NAME'] . "/img1.jpg",
      "title" => "質問の前に最寄り駅を選択してください",
      "text" => "質問の前に最寄り駅を選択してください",
      "actions" => [
          [
            "type" => "message",
            "label" => "郡山",
            "text" =>  "郡山"//$userId//
          ],
          [
            "type" => "message",
            "label" => "福島",
            "text" => "福島"
          ],
          [
            "type" => "message",
            "label" => "福島南",
            "text" => "福島南"
          ],
          [
            "type" => "message",
            "label" => "他の場所",
            "text" => "他の場所です"
          ]
      ]
    ]
  ];
  
}else if ($text == '福島' || '郡山' || '福島南'){
  //$response_format_text = ['contentType'=>1,"toType"=>1,"text"=>"質問を入力してください"];
 //$response_format_text = ["type" => "message",
 $response_format_text = $response_format_text = [
    "type" => "template",
    "altText" => "質問ボタンから質問を入力してください（OK／戻る）",
    "template" => [
        "type" => "confirm",
        "text" => "質問ボタンから質問を入力してください",
        "actions" => [
            [
              "type" => "message",
              "label" => "OK",
              "text" => "OK"
            ],
            [
              "type" => "message",
              "label" => "戻る",
              "text" => "戻る"
            ]
        ]
    ]
  ];
 //"text"=>"質問ボタンから質問を入力してください"];
}else if ($text == 'いいえ') {
  exit;
}else {
  $response_format_text = [
    "type" => "template",
    "altText" => "こんにちわ 何かご質問はありますか？（はい／いいえ）",
    "template" => [
        "type" => "confirm",
        "text" => "こんにちわ 何かご質問はありますか？",
        "actions" => [
            [
              "type" => "message",
              "label" => "はい",
              "text" => "はい"
            ],
            [
              "type" => "message",
              "label" => "いいえ",
              "text" => "いいえ"
            ]
        ]
    ]
  ];
}

$post_data = [
	"replyToken" => $replyToken,
	"messages" => [$response_format_text]
	];

$ch = curl_init("https://api.line.me/v2/bot/message/reply");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'Content-Type: application/json; charser=UTF-8',
    'Authorization: Bearer ' . $accessToken
    ));
$result = curl_exec($ch);
curl_close($ch);
