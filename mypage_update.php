<?php
mb_internal_encoding("utf8");

//セッションスタート
session_start();

//DB接続・try catch文。DBに接続できなければエラーメッセージを表示
try{
    $pdo = new PDO("mysql:dbname=phpmid;host=localhost;","root","");
}catch(PDOExpection $e){
    die("<p>申し訳ございません。現在サーバーが混み合っており一時的にアクセスが出来ません。<br>しばらくしてから再度ログインをしてください。</p>
<a href='http://localhost/login_mypage/login.php'>ログイン画面へ</a>"
    );
}

//prepared statement（update）でSQLをセット
$stmt = $pdo->prepare("update login_mypage set name=?, mail=?, password=?, comments=? where id=?");

//bindValueメソッドでパラメータをセット
$stmt->bindValue(1,$_POST['name']);
$stmt->bindValue(2,$_POST['mail']);
$stmt->bindValue(3,$_POST['password']);
$stmt->bindValue(4,$_POST['comments']);
$stmt->bindValue(5,$_POST['id']);

//executeでクエリを実行
$stmt->execute();

//prepared statement（更新された情報をDBからselect文で取得）でSQLをセット
$stmt = $pdo->prepare("select * from login_mypage where mail=? && password=?");

//bindValueメソッドでパラメータをセット
$stmt->bindValue(1,$_POST['mail']);
$stmt->bindValue(2,$_POST['password']);

//executeでクエリを実行
$stmt->execute();

//データベースを切断
$pdo = NULL;

//fetch・while文でデータ取得し、sessionに代入
while($row=$stmt->fetch()){
    $_SESSION['id']=$row['id'];
    $_SESSION['name']=$row['name'];
    $_SESSION['mail']=$row['mail'];
    $_SESSION['password']=$row['password'];
    $_SESSION['picture']=$row['picture'];
    $_SESSION['comments']=$row['comments'];
}

//mypage.phpへリダイレクト
header('Location:http://localhost/login_mypage/mypage.php');

?>