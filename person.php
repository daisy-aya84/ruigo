<?php
  require_once('./functions.php');
  session_start();

  $id=$_GET['id'];
  // DB接続
  $pdo = connectDB();

  $sql = 'SELECT username FROM users WHERE id = :id';
  $statement = $pdo->prepare($sql);
  $statement->execute([':id' => $id]);
  $username = $statement->fetch();

  $sql = 'SELECT users.username,dictionary.word FROM word_ruigo LEFT JOIN users ON word_ruigo.user_id = users.id LEFT JOIN dictionary ON word_ruigo.ruigo_id = dictionary.id_d WHERE users.id = :id';
  $statement = $pdo->prepare($sql);
  $statement->execute([':id' => $id]);
  $words = $statement->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <title><?php echo h($username['username']); ?> | RuiGo</title>
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

<div id="main">

<div id="all">

<h2><?php echo h($username['username']); ?></h2>

<hr>

<h3>追加した言葉</h3>

<?php foreach ($words as $word) :?>
  <ul>
    <li><?php echo h($word['word']) ?></li>
  </ul>
<?php endforeach; ?>
</div>
<div id ="footer" style="background-color: #908fb2; width: auto; margin: auto; color:#fff; text-align:center;">Ayano Masumoto</div>

</div>
</body>
</html>
