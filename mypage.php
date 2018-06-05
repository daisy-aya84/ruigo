
<?php
require_once('./functions.php');
session_start();

// ログインしていなかったら、ログイン画面にリダイレクトする
redirectIfNotLogin(); // ※ この関数はfunctions.phpに定義してある

$id = $_SESSION['user']['id']; //idはユーザーのid
$username = $_SESSION['user']['username'];


$pdo = connectDB();

//wordとword_id,user_id,ruigo_idだけが入っているテーブル
$sql = 'SELECT dictionary.word, dictionary.user_id, word_ruigo.ruigo_id FROM users LEFT JOIN dictionary ON users.id = dictionary.user_id LEFT JOIN word_ruigo ON word_ruigo.ruigo_id = dictionary.id_d WHERE dictionary.user_id = :id';
$statement = $pdo->prepare($sql);
$statement->execute([':id' => $id]);
$user_words= $statement->fetchAll();
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
        <li><a href=user_info.php>ユーザー情報</a></li>
        <li><a href="./logout.php">ログアウト</a></li>
      </ul>

  </div>

  <div id="all">

  <h2><?php echo $username; ?>のMy Page</h2>

  <hr>

  <h3>追加した言葉</h3>
  <?php foreach ($user_words as $user_word):?>

    <ul>
      <li><a href="wordruigo.php?id=<?php echo $user_word['ruigo_id']; ?>"><?php echo h($user_word['word']);?></a></li>
    </ul>
  <?php endforeach;?>

</div>
  <div id ="footer" style="background-color: #908fb2; width: auto; margin: auto; color:#fff; text-align:center;">Ayano Masumoto</div>

</body>
</html>
