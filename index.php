<?php
require_once('./functions.php');
session_start();

$id = $_SESSION['user']['id']; //idはユーザーのid
$username = $_SESSION['user']['username'];

//特に検索していない状態の時に全部の情報を出す
$pdo = connectDB();
$sql = "SELECT * FROM dictionary ";
$statement = $pdo->prepare($sql);
  $statement->execute();
  // $articles 連想配列に指定した記事が複数入っている状態↓
  $words = $statement->fetchAll();

  //wordとword_idとruigo_idのテーブルができているので、同じword_idの単語の個数を数えるようにする
  $sql = 'SELECT dictionary.word, dictionary.id_d,word_ruigo.word_id, word_ruigo.ruigo_id FROM word_ruigo LEFT JOIN users ON word_ruigo.user_id = users.id LEFT JOIN dictionary ON word_ruigo.ruigo_id = dictionary.id_d';
  $statement = $pdo->prepare($sql);
  $statement->execute();
  $nums= $statement->fetchAll();


  // 検索ワードに応じて出す情報を変える
  if ($_SERVER["REQUEST_METHOD"] === "POST") { //POSTから値を受け取った時

    // 送られた値を取得
    $keyword= $_POST['search']; //searchをkeywordに入れる
    // var_dump($keyword);

    // 入力値チェック： 未入力の項目があるか
    if (empty($keyword) ){
      $_SESSION["error"] = "入力されてない項目があります";
      header("Location: index.php");
      return;
    }

    $sql = "SELECT * FROM dictionary WHERE word LIKE '%$keyword%'";
    $statement = $pdo->prepare($sql);
      $statement->execute([
      ]);
      // $articles 連想配列に指定した記事が複数入っている状態↓
      $words = $statement->fetchAll(); //fetchAllでstatementが使えるようになる


  }

  function ruigoNum($id, $nums){ //類語の数を数える関数（関数の中の変数は、今まで使ってたやつも全部渡してあげないとダメ）
    $count = 0;
    foreach ($nums as $num) {
      if((int)$id == (int)$num['word_id']){
        $count ++;

        if((int)$num['id_d'] == (int)$num['word_id']){

          $count = $count-1;
      }
      }
    }
    return $count;
  }


?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="style.css">
  <title>RuiGo -みんなで作る類語辞書</title>
  <link rel="icon" type="image/png" href="RuiGo_icon_mini.png">
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script><script type="text/javascript" src="./footerFixed.js"></script>
</head>

<body>

<div id= "container">
  <div id="header"><a href="index.php"><img src="./RuiGo_header.png"></a>

    <div style="color:#fff; margin-left:auto; padding:3px;">
      <?php if(empty($id)){
        echo h(こんにちは！RuiGoへようこそ♪);
      }else{
        echo h($username)."さん、RuiGoへようこそ!"."";
      } ?>
    </div>

  </div>


  <div id="nav">

      <ul>
        <li><a href="./user_register.php">新規登録</a></li>
        <li><a href="./login.php">ログイン</a></li>
        <li><a href="./mypage.php">マイページ</a></li>
        <li><a href="./logout.php">ログアウト</a></li>
      </ul>

  </div>

<br>
  <p style="text-align:center;
    border: 4px double #ddd;
    margin: 2em 0;
    padding: 2em;background-color:#ffffff;
    width: 800px;
    margin: 0 auto;">
    RuiGoはみんなで作る類語辞書。<br>
    誰でもRuiGoを使うことができます。<br>
    さらに、RuiGOアカウントを取得すると、言葉を追加したり、自分の思う類語を追加できます。<br>
    みんなでRuiGoを大きくしましょう。<br>
  </p>

<br>
  <div class="search">

    <form action="index.php" method="post">

      <p>検索したい言葉を入力してください。</p>
      <input type="search" name="search" placeholder="言葉を入力（曖昧検索OK）" size="30">
      <input type="submit" name="submit" value="検索">
    </form>
    <br>
    <form action="word_add.php">
      <input id="submit_button" type="submit" value="言葉を追加">
    </form>
    <br>
  </div>

  <br>

<div id="all">
<h2 style="text-align:center;">言葉一覧</h2>
  <table class="word">
    <thead>
      <tr>
        <th>言葉</th>
        <th>ふりがな</th>
        <th>類語の件数</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($words as $word):?>
        <tr>
          <td><a href="wordruigo.php?id=<?php echo $word['id_d']; ?>"><?php echo h($word['word']);?></a></td>
          <td><?php echo h($word['word_huri']);?></td>
          <td>
            <?php
                echo ruigoNum($word['id_d'], $nums);
              ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>

<p class="pagetop"><a href="#wrap">▲</a></p>
<script>
$(document).ready(function() {
  var pagetop = $('.pagetop');
    $(window).scroll(function () {
       if ($(this).scrollTop() > 100) {
            pagetop.fadeIn();
       } else {
            pagetop.fadeOut();
            }
       });
       pagetop.click(function () {
           $('body, html').animate({ scrollTop: 0 }, 500);
              return false;
   });
});
</script>
<div id ="footer" style="background-color: #908fb2; width: auto; margin: auto; color:#fff; text-align:center;">Ayano Masumoto</div>

</div>
</body>
</html>
