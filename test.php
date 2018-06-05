<?php
  require_once('./functions.php');

  // DB接続
  $pdo = connectDB(); // ※ この関数はfunctions.phpに定義してある
  // 全記事(5記事文)を降順に取得するSQL文
  $sql = 'SELECT * FROM dictionary ORDER BY id DESC LIMIT 5';
  // SQLを実行
  $statement = $pdo->query($sql);
  // プレースメントフォルダが無いので，実行の表記が簡単
  $statement->execute();
  // $articles 連想配列に指定した記事が複数入っている状態↓
  $articles = $statement->fetchAll();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8" />
  <title>word追加確認</title>
  <link rel="stylesheet" href="./my.css">
</head>
<body>
<div id="all">
   <div>
     <h3>word一覧</h3>
     <table border="2">
       <thead>
         <tr>
           <th>ID</th>
           <th>word</th>
         </tr>
       </thead>
       <tbody>
         <?php foreach($articles as $article): ?>
           <tr>
             <td><?php echo h($article['id']);?></td>
             <td><a href="./article.php?id=<?php echo $article['id']; ?>"><?php echo h($article['word']); ?></a></td>
           </tr>
         <?php endforeach; ?>
       </tbody>
     </table>
    </div>

</div>
</body>
</html>
