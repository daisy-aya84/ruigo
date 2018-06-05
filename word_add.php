<?php
  require_once('./functions.php');
  session_start();

  redirectIfNotLogin();
  $id = $_SESSION['user']['id'];
  $username = $_SESSION['user']['username'];
  $nichiji  = date('Y-m-d H:i:s');
  // POSTリクエストの場合
  if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $word = $_POST['word'];
    $word_huri = $_POST['word_huri'];

    if (empty($word) || empty($word_huri)){
      $_SESSION["error"] = "入力されてない項目があります";
      header("Location: word_add.php");
      return;
    }

    $pdo = connectDB();

    //すでに同じ言葉が登録されていたらerrorを出す！->dictionaryからwordをとってくる
    $sql = 'SELECT word FROM dictionary';
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $checks= $statement->fetchAll();

    //同じusernameが存在していたらerrorを出す
    foreach ($checks as $check) {
      if($word === $check['word']){
        $_SESSION["error"] = "すでにその言葉は存在します";
        header("Location: word_add.php");
        return;
      }
    }

    //DBにいろいろつっこむ
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

    $sql = "INSERT INTO word_ruigo (user_id, word_id, ruigo_id, created_at, modified_at) VALUES(:user_id, :word_id, :ruigo_id, :created_at, :modified_at)"; //word_idとruigo_idが0のやつはword_addから登録したやつ
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
      ':user_id' => $id,
      ':word_id' => $neko,
      ':ruigo_id' => $neko,
      ':created_at' => $nichiji,
      ':modified_at' => $nichiji,
    ]);

    header("Location: finish.html");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
	<title>Ruigo | 言葉の追加</title>
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

    <!-- Error Message -->
    <?php if(!empty($_SESSION['error'])): ?>
        <div>
          <!-- メッセージを表示 -->
            <pre><?php echo $_SESSION['error']; ?></pre>
          <!-- セッション変数 succcess の値を空に -->
            <?php $_SESSION['error'] = null; ?>
        </div>
    <?php endif; ?>

    <h2>新しい言葉を登録</h2>

    <div id="fm">
        <form action="" method="post">
          <p>追加する言葉:<input type="text" name="word" size="50" maxlength="50" value=""></p>
          <p>ふりがな（ひらがなで書いてください）：<input type="text" name="word_huri" size="50" maxlength="50" value=""></p>
          <!-- <p>類語：<input type="text" name="word" size="50" maxlength="50" value=""></p>
          <p>ふりがな：<input type="text" name="word_huri" size="50" maxlength="50" value=""></p> -->
          <input id="submit_button" type="submit" value="送信">
        </form>
    </div>

	</div>

  <div id ="footer">Ayano Masumoto</div>

</body>
</html>
