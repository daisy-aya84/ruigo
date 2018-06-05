<?php
  require_once('./functions.php');
  session_start();


  // POSTリクエストの場合
  if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // 送られた値を変数に格納
    $username = $_POST['username'];
    $passwd = $_POST['passwd'];
    $passwd_confirmation = $_POST['passwd_confirmation'];
    $nichiji  = date('Y-m-d H:i:s');

    // 未入力の項目があるか
    if (empty($username) || empty($passwd) || empty($passwd_confirmation)) {
        $_SESSION["error"] = "入力されていない項目があります";
        header("Location: user_register.php");
        return;
    }

    // パスワードとパスワード確認が一致しているか
    if ($passwd !== $passwd_confirmation) {
        $_SESSION["error"] = "パスワードが一致しません";
        header("Location: user_register.php");
        return;
    }


    $pdo = connectDB();
    //同じusernameがないかどうか確認するためにとってくる
    $sql = "SELECT username FROM users";
    $statement = $pdo->prepare($sql);
    $statement->execute();
    $checks= $statement->fetchAll();

    //同じusernameが存在していたらerrorを出す
    foreach ($checks as $check) {
      if($username === $check['username']){
        $_SESSION["error"] = "すでにそのユーザー名は存在します";
        header("Location: user_register.php");
        return;
      }
    }

    $sql = "INSERT INTO users (username, passwd, created_at, modified_at) VALUES(:username, :passwd,:created_at, :modified_at)";
    $statement = $pdo->prepare($sql);
    $result = $statement->execute([
        ':username' => $username,
        ':passwd' => crypt($passwd),
        ':created_at' => $nichiji,
        ':modified_at' => $nichiji
    ]);




    if (!$result) {
        die('Database Error');
    }

    // セッションにメッセージを格納
    $_SESSION["success"] = "登録が完了しました。ログインしてください。";
    // ログイン画面に遷移
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8" />
  <link rel="stylesheet" href="style.css">
	<title>新規登録 | RuiGo</title>
  <link rel="icon" type="image/png" href="RuiGo_icon_mini.png">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script><script type="text/javascript" src="./footerFixed.js"></script>
</head>
<body>

  <div id="header"><a href="index.php"><img src="./RuiGo_header.png"></a></div>

	<div id="all">

<h2>新規登録</h2>

    <!-- セッション変数(successやerror)に値が入っている場合の処理
    ログインに成功した．または失敗した理由を表示 -->
      <!-- Success Message -->
      <?php if(!empty($_SESSION['success'])): ?>
          <div class="alert alert-success" role="success">
            <!-- メッセージを表示 -->
              <pre><?php echo $_SESSION['success']; ?></pre>
            <!-- セッション変数 succcess の値を空に -->
              <?php $_SESSION['success'] = null; ?>
          </div>
      <?php endif; ?>
      <!-- Error Message -->
      <?php if(!empty($_SESSION['error'])): ?>
          <div>
            <!-- メッセージを表示 -->
              <pre><?php echo $_SESSION['error']; ?></pre>
            <!-- セッション変数 succcess の値を空に -->
              <?php $_SESSION['error'] = null; ?>
          </div>
      <?php endif; ?>

    <form action="" method="POST">
        <p>ユーザー名（英数字で記入）
            <input type="text" name="username">
        </p>
        <p>パスワード
            <input type="password" name="passwd">
        </p>
        <p>パスワード再入力
            <input type="password" name="passwd_confirmation">
        </p>
        <input style="margin:5px;" type="submit" value="ログイン">
    </form>
	</div>

  <div id ="footer" style="background-color: #908fb2; width: auto; margin: auto; color:#fff; text-align:center;">Ayano Masumoto</div>

</body>
</html>
