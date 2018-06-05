
<?php
require_once('./functions.php');
session_start();

// ログインしていなかったら、ログイン画面にリダイレクトする
redirectIfNotLogin(); // ※ この関数はfunctions.phpに定義してある

$id = $_SESSION['user']['id'];
$username = $_SESSION['user']['username'];

$pdo = connectDB();
$sql = "SELECT * FROM dictionary　 WHERE user_id = :target_user_id";
$statement = $pdo->prepare($sql);
  $statement->execute([
    ':target_user_id' => $id,
  ]);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <title><?php echo $username; ?> | RuiGo</title>
  <link rel="icon" type="image/png" href="RuiGo_icon_mini.png">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script><script type="text/javascript" src="./footerFixed.js"></script>
</head>
<body>

  <div id="header"><a href="index.php"><img src="./RuiGo_header.png"></a></div>

  <div id="nav">
      <ul>
        <li><a href=mypage.php>マイページ</a></li>
        <li><a href="./logout.php">ログアウト</a></li>
      </ul>

  </div>

<div id="all">
  <h2>あなたのユーザーネーム</h2>
  <?php echo($username)?>

</div>
  <div id ="footer" style="background-color: #908fb2; width: auto; margin: auto; color:#fff; text-align:center;">Ayano Masumoto</div>

</body>
</html>
