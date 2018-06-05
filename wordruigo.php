<?php
  require_once('./functions.php');
  session_start();

  $id=$_GET['id'];
  // DB接続
  $pdo = connectDB();
  // 以下4行、dictionaryをDBから取得し、変数$wordに格納
  $sql = 'SELECT dictionary.word,word_ruigo.word_id FROM dictionary LEFT JOIN word_ruigo ON word_ruigo.ruigo_id = dictionary.id_d WHERE dictionary.id_d = :id';
  $statement = $pdo->prepare($sql);
  $statement->execute([':id' => $id]);
  $word = $statement->fetch();

  // $user_id = $dictionary['user_id'];

  //word_ruigoテーブルのword_idにwordのidを入れる
  $sql = 'SELECT * FROM word_ruigo WHERE word_id = :word_id';
  $statement = $pdo->prepare($sql);
  $statement->execute([':word_id' => $id]);

  //その単語に登録されている類語の数の配列を作って？を作ろうとしたけど、必要なくなったよ！（33行目まで必要なくなりました）
  // $ids=[];
  // foreach ($statement->fetchAll() as $ruigo) {
  //   $ids[]= $ruigo['ruigo_id'];
  // }

  //ruigo_idとdictionaryテーブルのidが同じものを取ってきて$ruigoに入れる

  // $sql = 'SELECT * FROM dictionary WHERE id IN ('.substr(str_repeat(",?", count($ids)), 1).')';
  // $statement = $pdo->prepare($sql);
  // $statement->execute($ids);
  // $ruigos= $statement->fetchAll();
   //idsでruigo_idを持ってくることができるようになりました！

 //usernameを抽出
  $sql = 'SELECT users.id,users.username,dictionary.word, dictionary.word_huri, dictionary.id_d, word_ruigo.word_id FROM word_ruigo LEFT JOIN users ON word_ruigo.user_id = users.id LEFT JOIN dictionary ON word_ruigo.ruigo_id = dictionary.id_d WHERE word_ruigo.word_id= :id';
  $statement = $pdo->prepare($sql);
  $statement->execute([':id' => $id]);
  $users_ruigos= $statement->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <title><?php echo h($word['word']); ?> | RuiGo</title>
  <link rel="icon" type="image/png" href="RuiGo_icon_mini.png">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script><script type="text/javascript" src="./footerFixed.js"></script>
</head>
<body>

  <div id="header"><a href="index.php"><img src="./RuiGo_header.png"></a></div>

  <div id="container">

  <div id="nav">
      <ul>
        <li><a href=user_info.php>ユーザー情報</a></li>
        <li><a href="./logout.php">ログアウト</a></li>
      </ul>

  </div>

  <div id="all">

  <div id = "ruigo">

  <h1>
      「<?php echo h($word['word']); ?>」の類語
  </h1>

  <?php if((int)$word['word_id'] == (int)$id){

  }else{
    $moth = (int)$word['word_id'];

    $sql = 'SELECT dictionary.word FROM dictionary LEFT JOIN word_ruigo ON word_ruigo.ruigo_id = dictionary.id_d WHERE dictionary.id_d = :id';
    $statement = $pdo->prepare($sql);
    $statement->execute([':id' => $moth]);
    $mother= $statement->fetch();

    echo "「".'<a href="wordruigo.php?id='. $moth .'">'.h($mother['word']).'</a>'."」の類語として登録されました";
  } ?>

  <form action="ruigo_add.php" method="GET">
    <input type="hidden" name="id" value="<?php echo $id?>">
  <input id="submit_button" type="submit" value="類語を追加">
  </form>

</div>



  <table class="word">
    <thead>
      <tr>
        <th>類語</th>
        <th>ふりがな</th>
        <th>登録ユーザー</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users_ruigos as $user_ruigo):
        if((int)$user_ruigo['id_d']==(int)$user_ruigo['word_id']){
        }else{?>
        <tr>
          <td><?php echo h($user_ruigo['word']);?></a></td>
          <td><?php echo h($user_ruigo['word_huri']);?></a></td>
          <td><a href="person.php?id=<?php echo $user_ruigo['id']; ?>"><?php echo h($user_ruigo['username']);?></a></td>

        </tr>
      <?php }endforeach; ?>
    </tbody>

  </table>

</div>

</div>

<div id ="footer" style="background-color: #908fb2; width: auto; margin: auto; color:#fff; text-align:center;">Ayano Masumoto</div>

</html>
