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
	<?php if($retorno) echo "<p style='aling:center; color:red;'>".$retorno."</p>"; ?>
		<form action="lista.php" method="post">
			<h1>Lista</h1>
						<div>
				<a href="cadastro.php">Cadastrar</a>
				<a href="lista.php">Lista/excluir</a>
			</div>
			<br />
			<br />
			<br />
			<br />
			<div>
				<input type="text" placeholder="parte do nome do vídeo" name="busca" required="" id="busca" />
			</div>
			
			<div>
				<input type="submit" value="Buscar" />
			</div>
		</form><!-- form -->
		<table border='1' width='100%'>
		<?php
		  if($n = isset($_POST['busca'])?trocanome($_POST['busca']):NULL){
	  		//$n = 'login';
			$var = explode(" ", $n);
			for($i=0;$i<count($var);$i++){
				$busca = new consulta();
					$busca->campo 		= 'nome, url, id_video';
					$busca->tabela 		= 'aj_videos';
					$busca->parametro   = "pl_chave like '%".$var[$i]."%'";
				$exc_busca = $busca->select_db();

				while ($linha = $exc_busca->fetch_object()) {
					echo  "	<tr>
								<td><a href='lista.php?e=".cript($linha->id_video)."' >Excluir</a></td>
								<td scope='row'>".$linha->nome."</td>
							</tr>";
								
		  		} 
			};
			 		
  		}
		if($exclui = isset($_GET['e'])?dcript($_GET['e'],'Erro para excluir vídeo'):FALSE){
			$ex = new exclui();
			$ex->tabela = 'aj_videos';
			$ex->parametro = "id_video = ".$exclui;
			if($ex->delete_db()) echo "Vídeo excluído com Sucersso";
			else echo "Erro ao excluir Vídeo";
		}
		?>
		</table>
	</section><!-- content -->
</div><!-- container -->
</body>
  
    <script src="login/js/index.js"></script>

</body>
</html>
