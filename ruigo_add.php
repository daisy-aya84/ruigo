<?php
  require_once('./functions.php');
  session_start();

  redirectIfNotLogin();
  $id = $_SESSION['user']['id'];
  $username = $_SESSION['user']['username'];

  $nichiji  = date('Y-m-d H:i:s');

  // URLに含まれている記事のIDを取得
  $word_id = $_GET['id'];

  // POSTリクエストの場合
  if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $word = $_POST['ruigo'];
    $word_huri = $_POST['ruigo_huri'];

    if (empty($word) || empty($word_huri)){
      $_SESSION["error"] = "入力されてない項目があります";
      header("Location: ruigo_add.php");
      return;
    }

    $pdo = connectDB();
    $sql = "INSERT INTO dictionary (user_id, word,word_huri, created_at, modified_at) VALUES(:user_id, :word,:word_huri, :created_at, :modified_at)";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
      ':user_id' => $id,
      ':word' => $word,
      ':word_huri' => $word_huri,
      ':created_at' => $nichiji,
      ':modified_at' => $nichiji,
    ]);
    $neko = $pdo->lastInsertId();

    $sql = "INSERT INTO word_ruigo (user_id, word_id, ruigo_id, created_at, modified_at) VALUES(:user_id, :word_id, :ruigo_id, :created_at, :modified_at)";
    $state = $pdo->prepare($sql);
    $ruigo = $state->execute([
      ':user_id' => $id,
      ':word_id' => $word_id,
      ':ruigo_id' => $neko,
      ':created_at' => $nichiji,
      ':modified_at' => $nichiji,
    ]);

    $toyo = (int)$word_id;

    $sql= 'SELECT dictionary.word FROM dictionary LEFT JOIN word_ruigo ON word_ruigo.ruigo_id = dictionary.id_d WHERE dictionary.id_d = :id';
    $statement = $pdo->prepare($sql);
    $statement->execute([':id' => $toyo]);
    $mother= $statement->fetch();



    // if (!$result) {
    //     print_r($pdo->errorInfo());
    //     die('Database Error');
    // }

    header("Location: finish.html");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>RuiGo | 類語の追加</title>
	<link rel="stylesheet" href="style.css">
  <link rel="icon" type="image/png" href="RuiGo_icon_mini.png">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script><script type="text/javascript" src="./footerFixed.js"></script>
</head>
<body>

  <div id="header"><a href="index.php"><img src="./RuiGo_header.png"></a></div>

  <div id="nav">
      <ul>
        <li><a href=user_info.php>ユーザー情報</a></li>
        <li><a href="./logout.php">ログアウト</a></li>
      </ul>

  </div>

	<div id="all">

    <?php if(!empty($_SESSION['error'])): ?>
        <div>
          <!-- メッセージを表示 -->
            <pre><?php echo $_SESSION['error']; ?></pre>
          <!-- セッション変数 succcess の値を空に -->
            <?php $_SESSION['error'] = null; ?>
        </div>
    <?php endif; ?>
<div id="fm">
    <h2>新しい類語を登録</h2>
        <form action="" method="post">
          <p>追加する類語: <input type="text" name="ruigo" size="50" maxlength="50" value=""></p>
          <p>ふりがな（ひらがなで書いてください）: <input type="text" name="ruigo_huri" size="50" maxlength="50" value=""></p>
          <input id="submit_button" type="submit" value="送信">
        </form>

</div>

</div>

<div id ="footer">Ayano Masumoto</div>

</body>
</html>
