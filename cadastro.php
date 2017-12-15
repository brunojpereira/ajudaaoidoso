<?php
session_start();
if(!$_SESSION['login']){echo "<script>window.location='login.php';</script>"; }

include_once("php/class.php");
include_once("php/funcao_0.2.php");
include_once("conf/maysql.php");
$retorno = NULL;
if($nome = isset($_POST['nome'])?trocanome($_POST['nome']):FALSE){ 
	$palavra = trocanome($_POST['palavra']);
	$url 	 = trocanome($_POST['url']);	
	
	$insere = new insercao();
    $insere->campo = 'nome, url, pl_chave, dat_cad';
    $insere->tabela = 'aj_videos';
    $insere->dados = "'".$nome."','".$url."','".$palavra."',now()";
	if($insere->insert_bd()) $retorno = "Vídeo ".$nome." Cadastrado com Sucersso";
	else $retorno = "Erro no Cadstro";
	
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
	<?php if($retorno) echo "<p style='aling:center; color:red;'>'".$retorno."'</p>"; ?>
		<form action="cadastro.php" method="post">
			<h1>Cadastro</h1>
						<div>
				<a href="cadastro.php">Cadastrar</a>
				<a href="lista.php">Lista/excluir</a>
			</div>
			<br />
			<br />
			<br />
			<br />
			<div>
				<input type="text" placeholder="Nome do Vídeo" name="nome" required="" id="nome" />
			</div>
			<div>
				<input type="text" placeholder="Palavras Chaves" name="palavra" required="" id="palavra" />
			</div>
			<div>
				<input type="text" placeholder="URL do Youtube" name="url" required="" id="url" />
			</div>
			<div>
				<input type="submit" value="Cadastrar" />
			</div>
		</form><!-- form -->
		
	</section><!-- content -->
</div><!-- container -->
</body>
  
    <script src="login/js/index.js"></script>

</body>
</html>
