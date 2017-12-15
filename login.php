<?php
session_start();
unset ($_SESSION['login']);
include_once("php/class.php");
include_once("php/funcao_0.2.php");
include_once("conf/maysql.php");

if($login = isset($_POST['login'])?trocanome($_POST['login']):FALSE){ 
	$senha = trocanome($_POST['senha']);
	if(($login == 'admin') && (sha1($senha) == '40bd001563085fc35165329ea1ff5c5ecbdbbeef')){
		$_SESSION['login'] = $login;
		echo "<script>window.location='cadastro.php';</script>";
	}
	echo "<p style='aling:center; color:red;'>Erro no login</p>";
}
?>

<!DOCTYPE html>
<html >
<head>
  <meta charset="UTF-8">
  <title>Prog Idoso</title>
  
  
  
      <link rel="stylesheet" href="login/css/style.css">

  
</head>

<body>
  <body>
<div class="container">
	<section id="content">
		<form action="login.php" method="post">
			<h1>Admin</h1>
			<div>
				<input type="text" placeholder="Login" name="login" required="" id="login" />
			</div>
			<div>
				<input type="password" placeholder="Senha" name="senha" required="" id="senha" />
			</div>
			<div>
				<input type="submit" value="logar" />
				<!-- <a href="#">Lost your password?</a>
				<a href="#">Register</a> -->
			</div>
		</form><!-- form -->
		<div class="button">
			<!-- <a href="#">Download source file</a> -->
		</div><!-- button -->
	</section><!-- content -->
</div><!-- container -->
</body>
  
    <script src="login/js/index.js"></script>

</body>
</html>
