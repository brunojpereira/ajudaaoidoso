<?php
include_once("php/class.php");
include_once("php/funcao_0.2.php");
include_once("conf/maysql.php");

  		if($n = isset($_POST['e'])?trocanome($_POST['e']):NULL){
	  		//$n = 'login';
			//echo "<p>".$n."</p>";
			$var = explode(" ", $n);
			for($i=0;$i<count($var);$i++){
					//var_dump($var);
				$busca = new consulta();
					$busca->campo 		= 'nome, url';
					$busca->tabela 		= 'aj_videos';
					$busca->parametro   = "pl_chave like '%".$var[$i]."%'";
				$exc_busca = select_db($busca);

				while ($linha = $exc_busca->fetch_object()) {
					echo  "<tr>
							<td scope='row'>".$linha->nome."</td>
							 <td><a href='".$linha->url."' target='_blank'>abrir o vídeo</a></td>
						   </tr>";
								
		  		} 
			};
			 		
  		}
 ?>