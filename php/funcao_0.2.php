<?php
//retorna nome da imagem do candidato 
function retorna_imagem_cand($id_candidato){
  $consulta = new consulta();
  $consulta->campo      = 'imagem';
  $consulta->parametro  = 'id_candidato = '.$id_candidato;
  $consulta->tabela     =  'cur_candidato';
  $lista = select_db($consulta);
  $list = $lista->fetch_array();
  return $list['imagem'];
}

// Ferifica se já tem formação cadastrada
//Função não foi utilizada e não foi testada 
function ver_exi_formacao($id_candidato){
  $consulta = new consulta();
    $consulta->campo      = '1';
    $consulta->parametro  = "cur_candidato.id_candidato = ".$id_candidato;
    $consulta->tabela     = 'INNER JOIN cur_formacao_candidato ON cur_formacao_candidato.id_candidato = cur_candidato.id_candidato';
  $exc_consulta = $consulta->select_db();
  if($exc_consulta->num_rows) return 'true';
  else  return 'false';
}


// fecha a Vada de acordo com a data 
function fecha_vaga_aut(){
  $fecha = new  atualiza();
    $fecha->campo     = "status_vaga = 'F'";
    $fecha->tabela    = "cur_vaga";
    $fecha->parametro = "data_fim < NOW()";
    $fecha->update_bd();
}

//Remmovar arquivos 
  function rrmdir($dir) {
    foreach(glob($dir . '/*') as $file) {
      if   (is_dir($file)) rmdir($file);
      else unlink($file);
    }
    return true;
  }


//retorna lista de emails de candiatos selecionados
//retorna estatus de uma tabela 
function retorna_lista_email_selec($lista){
  $consulta = new consulta();
    $consulta->campo      = 'cur_candidato.email';
    $consulta->parametro  = "cur_vaga_candidato.id_vaga_candidato IN (".$lista.")";
    $consulta->tabela     = 'cur_candidato 
                              INNER JOIN cur_vaga_candidato ON cur_vaga_candidato.id_candidato = cur_candidato.id_candidato';
            $lista = $consulta->select_db();
            $retorna = NULL;
           while($list = $lista->fetch_object()){
                $retorna .= $list->email.",";
            }
            return substr($retorna,0,-1);
}

//retirar atributos repetidos de uma string, a string ser separada por , e sem espaço 
function remove_bd_repetido($string){
  $vet_string = preg_split("/[,]+/", $string);
  return implode(", ", array_unique($vet_string));
}

//verifica se candidato já está inscrito na vaga  
function verifica_vaga_inscrito($vaga,$candidato){
                $consulta = new consulta();
                                $consulta->campo      = 'id_vaga_candidato';
                                $consulta->parametro  = 'id_candidato = '.$candidato." AND id_vaga = ".$vaga;
                                $consulta->tabela     = 'cur_vaga_candidato';
                $lista = $consulta->select_db();
                if($list = $lista->fetch_array()){
                  return $list['id_vaga_candidato'];
                }else return false;
                
}

//Imprime lista de últimos empregos.
function pdf_lista_ult_empregos($id_cand){
          $busca = new consulta();
          $busca->campo       = "  id_emprego_ult, 
                                    nome, 
                                    telefone, 
                                    descricao,
                                    data_ini,
                                    data_fim";
          $busca->tabela      = 'cur_emprego_ult';
          $busca->parametro   = "id_candidato = ".$id_cand;
        $exc_busca = $busca->select_db();
        $retorna = NULL;
        $cont = 1;
        while ($linha = $exc_busca->fetch_object()) {
          if($cont > 1) $retorna .= "\n";
          $retorna .= $cont."º referência de Último emprego\n    Nome: ".$linha->nome."    Telefone: ".$linha->telefone."    Período: ". dtatela($linha->data_ini)." à ".dtatela($linha->data_fim)."\n    Descrição: ".$linha->descricao;
          $cont++;
        }
      return $retorna;
}


//Imprime lista de últimos empregos.
function imprime_lista_ult_empregos($id_cand){
          $busca = new consulta();
          $busca->campo       = "  id_emprego_ult, 
                                    nome, 
                                    telefone, 
                                    descricao,
                                    data_ini,
                                    data_fim";
          $busca->tabela      = 'cur_emprego_ult';
          $busca->parametro   = "id_candidato = ".$id_cand;
        $exc_busca = $busca->select_db();
        $retorna = NULL;
        while ($linha = $exc_busca->fetch_object()) {
          $retorna .= "<tr>
                    <th scope='row'>".$linha->nome."</th>
                     <td>".$linha->telefone."</td>
                     <td>".$linha->descricao."</td>
                     <td>". dtatela($linha->data_ini)." à ".dtatela($linha->data_fim)."</td>
                     <td></td>
                   </tr>";                  
              }
      return $retorna;
}
//lista de últimos empregos.
function lista_ult_empregos($id_cand){
  $busca = new consulta();
  $busca->campo       = "  id_emprego_ult, 
                            nome, 
                            telefone, 
                            descricao,
                            data_ini,
                            data_fim";
  $busca->tabela      = 'cur_emprego_ult';
  $busca->parametro   = "id_candidato = ".$id_cand;

  $exc_busca  = $busca->select_db();
  $retorna    = NULL;
  $valor      = 0;
  while ($linha = $exc_busca->fetch_object()) {
    $valor++;
    $retorna .= "<tr  class='bg-info'>
                <td><h2>Nome:</h2>&nbsp;&nbsp;&nbsp;".$linha->nome."</th>
                <td><h2>Telefone:</h2>&nbsp;&nbsp;&nbsp;".$linha->telefone."</td>
                <td><h2>Período:</h2>&nbsp;&nbsp;&nbsp;". dtatela($linha->data_ini)." à ".dtatela($linha->data_fim)."</td>
                <td width='20'><a class='btn btn-danger btn-xs' onclick=\"cancela_emp('".cript($linha->id_emprego_ult)."')\"><i class='fa fa-trash-o'></i> </a></td>
              </tr>";                  
  }
  $retorna .= "<tr><td colspan='4'><input type='hidden' id='refe_ulti_e' value='".$valor."'></td></tr>";
  return $retorna;
}

//função lista arquivos de candidato 
//dasativada em 16/08/2017
function lista_arquivo($cpf){
    /* $arquivo = opendir ("arquivos/".$cpf."/");
     $resposta = NULL;
     $cont = 1;
     while ($file = readdir ($arquivo)) {
        if (($file != ".") && ($file != "..") && ($file != "Thumbs.db") && ($file != "thumbnails") && ($file != "photothumb.db")){
            $resposta .= "<tr>
                    <th scope='row'>".$cont."º</th>
                     <td>".$file."</td>
                     <td>
                        <a href='arquivos/".$cpf."/".$file."' target='_blank' class='btn btn-info btn-xs'><i class='fa fa-pencil'></i> Abrir </a>
                        <button id='exclui_arquivo' onclick=\"exc_arquivo('".$file."')\" class='btn btn-danger btn-xs'><i class='fa fa-trash-o'></i> Excluir </button>
                    </td>
                   </tr>";
            $cont++;  
        }
    } 
    return $resposta;*/
    return NULL;
}

//função lista arquivos de candidato 
//desativada em 16/08/2017
function imprime_lista_arquivo($cpf){
  /*$scan = scandir("arquivos/".$cpf);
  if (count($scan) > 2){
    $arquivo = opendir ("arquivos/".$cpf."/");
    $resposta = NULL;
    $cont = 1;
    while ($file = readdir ($arquivo)) {
      if (($file != ".") && ($file != "..") && ($file != "Thumbs.db") && ($file != "thumbnails") && ($file != "photothumb.db")){
          $resposta .= "<tr>
                  <th scope='row'>".$cont."º</th>
                   <td>".$file."</td>
                   <td>
                      <a href='arquivos/".$cpf."/".$file."' target='_blank' class='btn btn-info btn-xs'><i class='fa fa-pencil'></i> Abrir </a>
                  </td>
                 </tr>";
          $cont++;  
      }
    } 
  }else $resposta = NULL;  
return $resposta;*/
return NULL;
}



//inserir na tabela cur_setor_candidato e cur_cargo_candidato
  function inset_checkbox_setor_cargo($tabela,$campo,$informacao,$cod){
    $limpa = new exclui();
      $limpa->tabela = $tabela;
      $limpa->parametro = "id_candidato = ".$cod;
      $limpa->delete_db();

    $novo = new insercao();
        $novo->campo  = $campo;
        $novo->tabela = $tabela;
        $novo->dados  = $informacao;
        
        if(!$novo->insert_bd_muitos()) return men_erro("Erro na tabela ".$tabela, 'd');
  }

  //*retorna os setores selecionados de um candidato
function pdf_setor($id_cand){
  $busca = new consulta();
    $busca->tabela      =   "cur_setor 
                              INNER JOIN cur_setor_candidato ON cur_setor_candidato.id_setor = cur_setor.id_setor and cur_setor_candidato.id_candidato = ".$id_cand;
    $busca->campo       = " cur_setor.desc_setor AS nomeSetor, cur_setor.id_setor,
                            case 
                              when cur_setor_candidato.id_setor_candidato IS NULL THEN ''
                              else 'checked'
                            end as 'ativo'";
    $busca->parametro  = "cur_setor.desc_setor";
    $retorna = NULL;
    $exc_busca = $busca->select_db_2();
    while ($vet = $exc_busca->fetch_object()) {
      $retorna .= $vet->nomeSetor.", ";
    }
  return substr($retorna,0,-2);
}


//*retorna os setores selecionados de um candidato
function imprime_setor($id_cand){
  $busca = new consulta();
    $busca->tabela      =   "cur_setor 
                              INNER JOIN cur_setor_candidato ON cur_setor_candidato.id_setor = cur_setor.id_setor and cur_setor_candidato.id_candidato = ".$id_cand;
    $busca->campo       = " cur_setor.desc_setor AS nomeSetor, cur_setor.id_setor,
                            case 
                              when cur_setor_candidato.id_setor_candidato IS NULL THEN ''
                              else 'checked'
                            end as 'ativo'";
    $busca->parametro  = "cur_setor.desc_setor";
    $retorna = NULL;
    $exc_busca = $busca->select_db_2();
    while ($vet = $exc_busca->fetch_object()) {
      $retorna .= "<div class='col-md-3 col-sm-12 col-xs-12 form-group'>
                    ".$vet->nomeSetor."
                    </div>";
    }
  return $retorna;
}

//*carrega checkboxx para setor
function gera_checkbox_setor($id_cand, $nome){
  $busca = new consulta();
    $busca->tabela      =   "cur_setor 
                              LEFT JOIN cur_setor_candidato ON cur_setor_candidato.id_setor = cur_setor.id_setor and cur_setor_candidato.id_candidato = ".$id_cand;
    $busca->campo       = " cur_setor.desc_setor AS nomeSetor, cur_setor.id_setor,
                            case 
                              when cur_setor_candidato.id_setor_candidato IS NULL THEN ''
                              else 'checked'
                            end as 'ativo'";
    $busca->parametro  = "cur_setor.desc_setor";
    $retorna = NULL;
    $exc_busca = $busca->select_db_2();
    while ($vet = $exc_busca->fetch_object()) {
      $retorna .= "<div class='col-md-3 col-sm-12 col-xs-12 form-group'>
                    <input type='checkbox' name='".$nome."[]' value='".$vet->id_setor."' ".$vet->ativo."  class='flat'/> ".$vet->nomeSetor."
                    </div>";
    }
  return $retorna;
}

//*carrega checkboxx para setor
function gera_checkbox_setor_imprimi($id_setor, $nome){
  $busca = new consulta();
    $busca->tabela      =   "cur_setor";                              
    $busca->campo       = " cur_setor.desc_setor AS nomeSetor, cur_setor.id_setor,
                            case 
                              when cur_setor.id_setor IN (".$id_setor.") THEN 'checked'
                              else ''
                            end as 'ativo'";
    $busca->parametro  = "cur_setor.desc_setor";
    $retorna = NULL;
    $exc_busca = $busca->select_db_2();
    while ($vet = $exc_busca->fetch_object()) {
      $retorna .= "<div class='col-md-3 col-sm-12 col-xs-12 form-group'>
                    <input type='checkbox' name='".$nome."[]' value='".$vet->id_setor."' ".$vet->ativo."  class='flat'/> ".
                    $vet->nomeSetor."
                    </div>";
    }
  return $retorna;
}

//*restorna lista de cargos 
function pdf_cargo($id_cand){
  $busca = new consulta();
    $busca->tabela      =   "cur_cargo 
                            INNER JOIN cur_cargo_candidato ON cur_cargo_candidato.id_cargo = cur_cargo.id_cargo and cur_cargo_candidato.id_candidato =".$id_cand;
    $busca->campo       = " cur_cargo.nome AS nomeCargo, cur_cargo.id_cargo,
                              case 
                                when cur_cargo_candidato.id_cargo_candidato IS NULL THEN ''
                                else 'checked'
                              end as 'ativo'";
    $busca->parametro  = "cur_cargo.nome";
    $retorna = NULL;
    $exc_busca = $busca->select_db_2();
    while ($vet = $exc_busca->fetch_object()) {
      $retorna .= $vet->nomeCargo.", ";
    }
  return substr($retorna,0,-2);
}

//atualiza a data de acesso 
function atu_data_acesso($candidato){
  $atualiza_cad = new atualiza();
    $atualiza_cad->campo      = "data_ace = '".date('Y-m-d H:i:s')."'";
    $atualiza_cad->parametro  = "id_candidato = ".$candidato;
    $atualiza_cad->tabela     = 'cur_candidato';
    if($atualiza_cad->update_bd())return TRUE;
    else return FALSE;            
}


//*restorna lista de cargos 
function imprime_cargo($id_cand){
  $busca = new consulta();
    $busca->tabela      =   "cur_cargo 
                            INNER JOIN cur_cargo_candidato ON cur_cargo_candidato.id_cargo = cur_cargo.id_cargo and cur_cargo_candidato.id_candidato =".$id_cand;
    $busca->campo       = " cur_cargo.nome AS nomeCargo, cur_cargo.id_cargo,
                              case 
                                when cur_cargo_candidato.id_cargo_candidato IS NULL THEN ''
                                else 'checked'
                              end as 'ativo'";
    $busca->parametro  = "cur_cargo.nome";
    $retorna = NULL;
    $exc_busca = $busca->select_db_2();
    while ($vet = $exc_busca->fetch_object()) {
      $retorna .= "<div class='col-md-2 col-sm-12 col-xs-12 form-group'>
                    ".$vet->nomeCargo."
                    </div>";
    }
  return $retorna;
}

//*carrega checkboxx para cargos
function gera_checkbox_cargo($id_cand, $nome){
  $busca = new consulta();
    $busca->tabela      =   "cur_cargo 
                            left join cur_cargo_candidato ON cur_cargo_candidato.id_cargo = cur_cargo.id_cargo and cur_cargo_candidato.id_candidato =".$id_cand;
    $busca->campo       = " cur_cargo.nome AS nomeCargo, cur_cargo.id_cargo,
                              case 
                                when cur_cargo_candidato.id_cargo_candidato IS NULL THEN ''
                                else 'checked'
                              end as 'ativo'";
    $busca->parametro  = "cur_cargo.nome";
    $retorna = NULL;
    $exc_busca = $busca->select_db_2();
    while ($vet = $exc_busca->fetch_object()) {
      $retorna .= "<div class='col-md-2 col-sm-12 col-xs-12 form-group'>
                    <input type='checkbox' name='".$nome."[]' value='".$vet->id_cargo."' ".$vet->ativo."  class='flat'/> ".$vet->nomeCargo."
                    </div>";
    }
  return $retorna;
}

//*carrega checkboxx para cargos imprimi
function gera_checkbox_cargo_imprimi($id_cargos, $nome){
  $busca = new consulta();
    $busca->tabela      =   "cur_cargo";
    $busca->campo       = " cur_cargo.nome AS nomeCargo, cur_cargo.id_cargo,
                              case 
                                when cur_cargo.id_cargo IN (".$id_cargos.") THEN 'checked'
                                else ''
                              end as 'ativo'";
    $busca->parametro  = "cur_cargo.nome";
    $retorna = NULL;
    $exc_busca = $busca->select_db_2();
    while ($vet = $exc_busca->fetch_object()) {
      $retorna .= "<div class='col-md-2 col-sm-12 col-xs-12 form-group'>
                    <input type='checkbox' name='".$nome."[]' value='".$vet->id_cargo."' ".$vet->ativo."  class='flat'/> ".
                    $vet->nomeCargo."
                    </div>";
    }
  return $retorna;
}



#retorna lista de formação
function lista_formacao($id_cand){
          $busca = new consulta();
          $busca->campo       = " cur_formacao_candidato.id_formacao_candidato, 
                                  cur_formacao_nivel.nome AS nivel, 
                                  cur_formacao_area.nome AS area,  
                                  cur_formacao_candidato.completo,
                                  cur_formacao_candidato.prev_conclusao,
                                  cur_formacao_candidato.curso,
                                  cur_formacao_candidato.instituicao";
          $busca->tabela      = " cur_formacao_candidato 
                                  INNER JOIN cur_formacao_nivel ON cur_formacao_nivel.id_formacao_nivel = cur_formacao_candidato.id_formacao_nivel
                                  INNER JOIN cur_formacao_area ON cur_formacao_area.id_formacao_area = cur_formacao_candidato.id_formacao_area";
          $busca->parametro   = "cur_formacao_candidato.id_candidato= ".$id_cand;
        $exc_busca = $busca->select_db();
        $retorna = NULL;
        $curso   = NULL;
        $valor   = 0;
        while ($linha = $exc_busca->fetch_object()) {
            $valor++;
            if($linha->curso == 'NULL') $curso = $linha->area;
            else $curso = $linha->curso;
          $retorna .= "<tr  class='bg-info'>
                    <td><h2>Escolaridade:</h2>&nbsp;&nbsp;&nbsp;".$linha->nivel."</th>
                     <td><h2>Curso:</h2>&nbsp;&nbsp;&nbsp;".$curso."</td>
                     <td><h2>Formação Completa:</h2>&nbsp;&nbsp;&nbsp;".$linha->completo."</td>
                     <td><h2>Instituição:</h2>&nbsp;&nbsp;&nbsp;".$linha->instituicao."</td>
                     <td width='20'><a class='btn btn-danger btn-xs' onclick=\"cancela_for('".cript($linha->id_formacao_candidato)."')\" title='Remover curso do cadastro'><i class='fa fa-trash-o'></i> </a>                      
                    </td>
                   </tr>";                          
      }
      $retorna .= "<tr><td colspan='4'><input type='hidden' id='ver_formacao_e' value='".$valor."'></td></tr>";
      return $retorna;
}

#pdf lista de formação
function pdf_lista_formacao($id_cand){
          $busca = new consulta();
          $busca->campo       = " cur_formacao_candidato.id_formacao_candidato, 
                                  cur_formacao_nivel.nome AS nivel, 
                                  cur_formacao_candidato.completo,
                                  cur_formacao_candidato.prev_conclusao,                                  
                                    CASE WHEN cur_formacao_candidato.curso = 'NULL' THEN cur_formacao_area.nome
                                      ELSE  cur_formacao_candidato.curso 
                                    END AS nomecurso,
                                  cur_formacao_candidato.instituicao";
          $busca->tabela      = " cur_formacao_candidato 
                                  INNER JOIN cur_formacao_nivel ON cur_formacao_nivel.id_formacao_nivel = cur_formacao_candidato.id_formacao_nivel
                                  INNER JOIN cur_formacao_area ON cur_formacao_area.id_formacao_area = cur_formacao_candidato.id_formacao_area";
          $busca->parametro   = "cur_formacao_candidato.id_candidato= ".$id_cand;
        $exc_busca = $busca->select_db();
        $retorna = NULL;
        $cont = 1;
        while ($linha = $exc_busca->fetch_object()) {
          if($cont > 1) $retorna .= "\n";
          $retorna .= $cont."º Formação\n    Escolaridade: ".$linha->nivel."    Nome do Curso: ".$linha->nomecurso."\n    Completo: ".$linha->completo."    Previsão: ".$linha->prev_conclusao."\n    Instituição: ".$linha->instituicao;   
          $cont++;
        }
      return $retorna;
}

#imprimi lista de formação
function imprime_lista_formacao($id_cand){
          $busca = new consulta();
          $busca->campo       = " cur_formacao_candidato.id_formacao_candidato, 
                                  cur_formacao_nivel.nome AS nivel, 
                                  cur_formacao_candidato.completo,
                                  cur_formacao_candidato.prev_conclusao,                                  
                                    CASE WHEN cur_formacao_candidato.curso = 'NULL' THEN cur_formacao_area.nome
                                      ELSE  cur_formacao_candidato.curso 
                                    END AS nomecurso,
                                  cur_formacao_candidato.instituicao";
          $busca->tabela      = " cur_formacao_candidato 
                                  INNER JOIN cur_formacao_nivel ON cur_formacao_nivel.id_formacao_nivel = cur_formacao_candidato.id_formacao_nivel
                                  INNER JOIN cur_formacao_area ON cur_formacao_area.id_formacao_area = cur_formacao_candidato.id_formacao_area";
          $busca->parametro   = "cur_formacao_candidato.id_candidato = ".$id_cand;
        $exc_busca = $busca->select_db();
        $retorna = NULL;
        while ($linha = $exc_busca->fetch_object()) {          
          $retorna .= "<tr>
                    <th scope='row'>".$linha->nivel."</th>
                     <td>".$linha->nomecurso."</td>
                     <td>".$linha->completo."</td>
                     <td>".$linha->prev_conclusao."</td>
                     <td>".$linha->instituicao."</td>
                   </tr>";                  
              }
      return $retorna;
}


//carrega cursos adicionais do candidato para PDF
function pdf_curso_candidato($id_cand){
          $busca = new consulta();
          $busca->campo       = "  id_curso_adicional, 
                                    nome, 
                                    carga_h, 
                                    instituicao,
                                    descricao";
          $busca->tabela      = 'cur_curso_adicional';
          $busca->parametro   = "id_candidato = ".$id_cand;
        $exc_busca = $busca->select_db();
        $retorna = NULL;
        $cont = 1;
        while ($linha = $exc_busca->fetch_object()) {
          if($cont > 1) $retorna .= "\n";
          $retorna .= $cont."º Curso\n    Nome do Curso: ".$linha->nome."    Carga Horária: ".$linha->carga_h."\n    Instituição: ".$linha->instituicao."\n    Descrição: ".$linha->descricao;   
          $cont++;
        }

      return $retorna;
}

//carrega cursos adicionais do candidato adm 
function imprime_curso_candidato($id_cand){
          $busca = new consulta();
          $busca->campo       = "  id_curso_adicional, 
                                    nome, 
                                    carga_h, 
                                    instituicao,
                                    descricao";
          $busca->tabela      = 'cur_curso_adicional';
          $busca->parametro   = "id_candidato = ".$id_cand;
        $exc_busca = $busca->select_db();
        $retorna = NULL;
        while ($linha = $exc_busca->fetch_object()) {
          $retorna .= "<tr>
                    <th scope='row'>".$linha->nome."</th>
                     <td>".$linha->carga_h."</td>                     
                     <td>".$linha->instituicao."</td>
                     <td>".$linha->descricao."</td>
                   </tr>";                  
              }
      return $retorna;
}

//carrega cursos adicionais do candidato
function carrega_curso_candidato($id_cand){
  $busca = new consulta();
  $busca->campo       = "  id_curso_adicional, 
                            nome, 
                            carga_h";
  $busca->tabela      = 'cur_curso_adicional';
  $busca->parametro   = "id_candidato = ".$id_cand;
  $exc_busca = $busca->select_db();
  $retorna = NULL;
  $valor = 0;
  while ($linha = $exc_busca->fetch_object()) {
    $valor++;
    $retorna .= "<tr  class='bg-info'>
                  <td><h2>Nome do Curso:</h2>&nbsp;&nbsp;&nbsp;".$linha->nome."</th>
                  <td><h2>Carga Horária:</h2>&nbsp;&nbsp;&nbsp;".$linha->carga_h."</td>
                  <td width='20'><a class='btn btn-danger btn-xs' onclick=\"cancela_cur('".cript($linha->id_curso_adicional)."')\"><i class='fa fa-trash-o'></i> </a></td>
                </tr>";                  
  }
  $retorna .= "<tr><td colspan='4'><input type='hidden' id='ver_curso_e' value='".$valor."'></td></tr>";
  return $retorna;
}


//carrega referencias PR e PE
function referencias_PR_PE($id_cand){
$busca = new consulta();
$busca->campo       = "  id_referencia, 
                          nome, 
                          telefone";
$busca->tabela      = 'cur_referencia';
$busca->parametro   = "id_candidato = ".$id_cand;
$exc_busca = $busca->select_db();
$retorna  = NULL;
$valor    = 0;
while ($linha = $exc_busca->fetch_object()) {
  $valor++;
  $retorna .= "<tr  class='bg-info'>
                <td><h2>Nome da Referência:</h2>&nbsp;&nbsp;&nbsp;".$linha->nome."</th>
                <td><h2>Telefone:</h2>&nbsp;&nbsp;&nbsp;".$linha->telefone."</td>
                <td width='20'><a class='btn btn-danger btn-xs' onclick=\"cancela('".cript($linha->id_referencia)."')\"><i class='fa fa-trash-o'></i> </a></td>
              </tr>";                  
    }
$retorna .= "<tr><td colspan='4'><input type='hidden' id='ver_ref_e' value='".$valor."'></td></tr>";
return $retorna;
}

//carrega referencias PR e PE
function pdf_referencias_PR_PE($id_cand){
          $busca = new consulta();
          $busca->campo       = "  id_referencia, 
                                    nome, 
                                    telefone, 
                                    descricao,
                                    case 
                                      WHEN referencia_tipo = 'PE' THEN 'Pessoal' 
                                      else 'Profissional'
                                      end AS 'referencia'";
          $busca->tabela      = 'cur_referencia';
          $busca->parametro   = "id_candidato = ".$id_cand;
        $exc_busca = $busca->select_db();
        $retorna = NULL;
        $cont = 1;
        while ($linha = $exc_busca->fetch_object()) {
          if($cont > 1) $retorna .= "\n";
          $retorna .= $cont."º referêcia\n    Nome: ".$linha->nome."   Telefone: ".$linha->telefone."    Tipo: ".$linha->referencia."\n    Descrição: ".$linha->descricao;    
          $cont++;              
              }
      return $retorna;
}

//carrega referencias PR e PE
function imprime_referencias_PR_PE($id_cand){
          $busca = new consulta();
          $busca->campo       = "  id_referencia, 
                                    nome, 
                                    telefone, 
                                    descricao,
                                    case 
                                      WHEN referencia_tipo = 'PE' THEN 'Pessoal' 
                                      else 'Profissional'
                                      end AS 'referencia'";
          $busca->tabela      = 'cur_referencia';
          $busca->parametro   = "id_candidato = ".$id_cand;
        $exc_busca = $busca->select_db();
        $retorna = NULL;
        while ($linha = $exc_busca->fetch_object()) {
          $retorna .= "<tr>
                    <th scope='row'>".$linha->nome."</th>
                     <td>".$linha->telefone."</td>
                     <td>".$linha->descricao."</td>
                     <td>".$linha->referencia."</td>
                     <td></td>
                   </tr>";                  
              }
      return $retorna;
}

//carrega valores de salário 
function salario($valor){
$recebe = NULL;
    $recebe .= "<option value='0.00' " ; $recebe .= (!$valor)?'selected ':NULL; $recebe .=">Selecione..</option>";
    $recebe .= "<option value='1000.00' " ; $recebe .= ('1000.00' == $valor)?'selected ':NULL; $recebe .=">R$ 1.000,00</option>";
    $recebe .= "<option value='2000.00' " ; $recebe .= ('2000.00' == $valor)?'selected ':NULL; $recebe .=">R$ 2.000,00</option>";
    $recebe .= "<option value='3000.00' " ; $recebe .= ('3000.00' == $valor)?'selected ':NULL; $recebe .=">R$ 3.000,00</option>";
    $recebe .= "<option value='4000.00' " ; $recebe .= ('4000.00' == $valor)?'selected ':NULL; $recebe .=">R$ 4.000,00</option>";  
    $recebe .= "<option value='5000.00' " ; $recebe .= ('5000.00' == $valor)?'selected ':NULL; $recebe .=">R$ 5.000,00</option>";  
    $recebe .= "<option value='7500.00' " ; $recebe .= ('7500.00' == $valor)?'selected ':NULL; $recebe .=">R$ 7.500,00</option>";  
    $recebe .= "<option value='10000.00' " ; $recebe .= ('3000.00' == $valor)?'selected ':NULL; $recebe .=">R$ 10.000,00</option>";      
return $recebe;
}

//Carrega a tag <selec> conendo os estados da página atu_curriculo
function estado($uf){
$recebe = NULL;
    $recebe .= "<option value='' " ; $recebe .= (!$uf)?'selected ':NULL; $recebe .=">Selecione..</option>";
    $recebe .= "<option value='AC' " ; $recebe .= ('AC' == $uf)?'selected ':NULL; $recebe .=">Acre</option>";
    $recebe .= "<option value='AL' " ; $recebe .= ('AL' == $uf)?'selected ':NULL; $recebe .=">Alagoas</option>";
    $recebe .= "<option value='AP' " ; $recebe .= ('AP' == $uf)?'selected ':NULL; $recebe .=">Amapá</option>";
    $recebe .= "<option value='AM' " ; $recebe .= ('AM' == $uf)?'selected ':NULL; $recebe .=">Amazonas</option>";
    $recebe .= "<option value='BA' " ; $recebe .= ('BA' == $uf)?'selected ':NULL; $recebe .=">Bahia</option>";
    $recebe .= "<option value='CE' " ; $recebe .= ('CE' == $uf)?'selected ':NULL; $recebe .=">Ceará</option>";
    $recebe .= "<option value='DF' " ; $recebe .= ('DF' == $uf)?'selected ':NULL; $recebe .=">Distrito Federal</option>";
    $recebe .= "<option value='ES' " ; $recebe .= ('ES' == $uf)?'selected ':NULL; $recebe .=">Espírito Santo</option>";
    $recebe .= "<option value='GO' " ; $recebe .= ('GO' == $uf)?'selected ':NULL; $recebe .=">Goiás</option>";
    $recebe .= "<option value='MA' " ; $recebe .= ('MA' == $uf)?'selected ':NULL; $recebe .=">Maranhão</option>";
    $recebe .= "<option value='MT' " ; $recebe .= ('MT' == $uf)?'selected ':NULL; $recebe .=">Mato Grosso</option>";
    $recebe .= "<option value='MS' " ; $recebe .= ('MS' == $uf)?'selected ':NULL; $recebe .=">Mato Grosso do Sul</option>";
    $recebe .= "<option value='MG' " ; $recebe .= ('MG' == $uf)?'selected ':NULL; $recebe .=">Minas Gerais</option>";
    $recebe .= "<option value='PA' " ; $recebe .= ('PA' == $uf)?'selected ':NULL; $recebe .=">Pará</option>";
    $recebe .= "<option value='PB' " ; $recebe .= ('PB' == $uf)?'selected ':NULL; $recebe .=">Paraíba</option>";
    $recebe .= "<option value='PR' " ; $recebe .= ('PR' == $uf)?'selected ':NULL; $recebe .=">Paraná</option>";
    $recebe .= "<option value='PE' " ; $recebe .= ('PE' == $uf)?'selected ':NULL; $recebe .=">Pernambuco</option>";
    $recebe .= "<option value='PI' " ; $recebe .= ('PI' == $uf)?'selected ':NULL; $recebe .=">Piauí</option>";
    $recebe .= "<option value='RJ' " ; $recebe .= ('RJ' == $uf)?'selected ':NULL; $recebe .=">Rio de Janeiro</option>";
    $recebe .= "<option value='RN' " ; $recebe .= ('RN' == $uf)?'selected':NULL; $recebe .=">Rio Grande do Norte</option>";
    $recebe .= "<option value='RS' " ; $recebe .= ('RS' == $uf)?'selected':NULL; $recebe .=">Rio Grande do Sul</option>";
    $recebe .= "<option value='RO' " ; $recebe .= ('RO' == $uf)?'selected':NULL; $recebe .=">Rondônia</option>";
    $recebe .= "<option value='RR' " ; $recebe .= ('RR' == $uf)?'selected':NULL; $recebe .=">Roraima</option>";
    $recebe .= "<option value='SC' " ; $recebe .= ('SC' == $uf)?'selected':NULL; $recebe .=">Santa Catarina</option>";
    $recebe .= "<option value='SP' " ; $recebe .= ('SP' == $uf)?'selected':NULL; $recebe .=">São Paulo</option>";
    $recebe .= "<option value='SE' " ; $recebe .= ('SE' == $uf)?'selected':NULL; $recebe .=">Sergipe</option>";
    $recebe .= "<option value='TO' " ; $recebe .= ('TO' == $uf)?'selected':NULL; $recebe .=">Tocantins</option>";
    
return $recebe;
}

//Função para retornar status da vaga 
function men_status_vaga($tipo){
    switch ($tipo) {
      case 'A': $tipo_men = 'success'; $mensagem = 'Aberto'; //Verde
              break;
      case 'C': $tipo_men = 'danger'; $mensagem = 'Finalizado';  //vermelho 
              break;
      case 'F': $tipo_men = 'warning'; $mensagem = 'Fechado';  //Amarelo 
              break;  
      default: $tipo_men = 'info'; $mensagem = '';  //Azul claro 
              break;
    }
  return "<span class='label label-".$tipo_men."'>".$mensagem."</span>"; 
}

//Função para retornar status do email
function men_status_email($tipo){
    switch ($tipo) {
      case 'S': $tipo_men = 'success'; $mensagem = 'Ligado'; //Verde
              break;
      case 'N': $tipo_men = 'danger'; $mensagem = 'Desligado';  //vermelho 
              break; 
      default: $tipo_men = 'info'; $mensagem = '';  //Azul claro 
              break;
    }
  return "<span class='label label-".$tipo_men."'>".$mensagem."</span>"; 
}

//Função para retornar mensagem de erro formatada, a função recebe a mesagem e o tipo de erro
function men_erro($mensagem,$tipo){
    switch ($tipo) {
      case 's': $tipo_men = 'success'; //Verde
              break;
      case 'i': $tipo_men = 'info'; //Azul 
              break;
      case 'w': $tipo_men = 'warning'; //Amarelo
              break;
      case 'd': $tipo_men = 'error';//'danger'; //Vermelho
              break;      
      default: $tipo_men =  'error';//'danger'; //Vemelho
              break;
    }
  return "<div class='alert alert-".$tipo_men." alert-dismissible fade in' role='alert'><button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>×</span></button><strong>".$mensagem."</div>";

   // return  "<script type='text/javascript'>alert('lfg'); c_men('Aviso','".$tipo_men."', '".$mensagem."'); </<script>";
}

//Função para retornar campo <option> da teg <select>, 
//Recebe nome da tabela, nome do campo do "value" e nome do campo que será mostrado na tela 
function ret_camp_option($tabela, $value, $nome, $selec){
  $consulta_setor = new consulta();
    $consulta_setor->campo    = $value.",".$nome;
    $consulta_setor->tabela   = $tabela;
    $consulta_setor->parametro  = $nome;
  $exc_consulta_setor = select_db_2($consulta_setor);
  $retorna = "<option value=''></option>";
  foreach ($exc_consulta_setor as $key => $e) {
    if(($selec) && ($selec == $e[$value])) $retorna .= "<option value='".cript($e[$value])."' selected>".$e[$nome]."</option>";
    else $retorna .= "<option value='".cript($e[$value])."'>".$e[$nome]."</option>";
  }
  return $retorna;
}

//Função para retornar campo <option> da teg <select>, 
//Recebe nome da tabela, nome do campo do "value" e nome do campo que será mostrado na tela o campo nome será um WHERE
function ret_camp_option_area_for($tabela, $value, $nome, $selec, $param){
  $consulta_setor = new consulta();
    $consulta_setor->campo    = $value.",".$nome;
    $consulta_setor->tabela   = $tabela;
    $consulta_setor->parametro  = $param;
  $exc_consulta_setor = $consulta_setor->select_db();
  $retorna = "<option value=''></option>";
  foreach ($exc_consulta_setor as $key => $e) {
    if(($selec) && ($selec == $e[$value])) $retorna .= "<option value='".cript($e[$value])."' selected>".$e[$nome]."</option>";
    else $retorna .= "<option value='".cript($e[$value])."'>".$e[$nome]."</option>";
  }
  return $retorna;
}

//direciona para a página de erro 
function pagina_erro(){
  echo "<script>window.location.href = 'erro.php';</script>";
}

//direciona para a página de login 
function pagina_login(){
  echo "<script>window.location.href = 'login.php';</script>";
}

//função verifica permissão da página para o usuário 
function verifica_perm_pagina($pagina,$permissao){
  $consulta = new consulta();
    $consulta->campo      = 'ativo';
    $consulta->tabela     = 'cur_menu';
    $consulta->parametro  = "nome_pagina = '".$pagina."' AND permissao like '%".$permissao."%'";
  $exc_consulta = select_db($consulta);
  if($exc_consulta->num_rows) return true;
  else return pagina_erro();
}


//retorna estatus de uma tabela 
function retorna_status($id,$tabela){
                $consulta = new consulta();
                                $consulta->campo      = 'status';
                                $consulta->parametro  = 'id = '.$id;
                                $consulta->tabela     = $tabela;
                $lista = select_db($consulta);
                $list = $lista->fetch_array();
                return $list['status'];
}

//recebe o id pessoa e retorna o superior  
function retorna_superior($id){
                $consulta = new consulta();
                                $consulta->campo      = 'id_superior';
                                $consulta->parametro  = "id = ".$id;
                                $consulta->tabela     = 'pessoa';
                $lista  = select_db($consulta);
                $list = $lista->fetch_array();
                return $list['id_superior'];
}


//rebe um numero de erro do mysql e retorna uma mensagem, cadastrada na tanela de erros
function erro_bd($numero){
	return "<p class='alert-danger'>Erro no Banco de Dados, numero: ".$numero."</p>";
	/*
                $consulta = new consulta();
                                $consulta->campo        = 'mensagem';
                                $consulta->parametro    = 'numero = '.$numero;
                                $consulta->tabela       = 'ci_errosbd';
                $lista = select_db($consulta);
                $list = $lista->fetch_array();
                if($list['mensagem'])return "<p class='alert-danger'>".$list['mensagem'].". Numero do erro:".$numero."</p>";
                else return "<p class='alert-danger'>Erro no Banco de Dados, numero: ".$numero."</p>";*/
}

//recebe o id e tabela e retorna o compo nome da mesma 
function retorna_nome_vaga($id){
  if(!$id)return NULL; 
  $consulta = new consulta();
                  $consulta->campo		  = 'nome';
                  $consulta->parametro	= 'id_vaga = '.$id;
                  $consulta->tabela	    = 'cur_vaga';
  $lista = select_db($consulta);
  $list = $lista->fetch_array();
  return $list['nome'];
}




function servernamber($valor){
    $valor = substr($valor, -3, 1);
    switch($valor){
        case 0:
            $valor = 3546115;
            break;
        case 1:
            $valor = 2678742;
            break;
        case 2:
            $valor = 6321597;
            break;
        case 3:
            $valor = 2154755;
            break;
        case 4:
            $valor = 3145452;
            break;
        case 5:
            $valor = 4715718;
            break;
        case 6:
            $valor = 1357456;
            break;
        case 7:
            $valor = 2456329;
            break;
        case 8:
            $valor = 3351286;
            break;
        case 9:
            $valor = 4486212;
            break;

        default:
            echo "Erro";

    }
    return $valor;

}
//echo servernamber("gegerger1ft") . "<br>";
function invertnum($valor){
    $strin = array(0=>"a",1=>"c",2=>"m",3=>"j",4=>"x",5=>"p",6=>"q",7=>"t",8=>"e",9=>"r");
    $valor = $strin[$valor];
return $valor;
}
function desinvertnum($valor){
    $strin = array("a"=>0,"c"=>1,"m"=>2,"j"=>3,"x"=>4,"p"=>5,"q"=>6,"t"=>7,"e"=>8,"r"=>9);
    $valor = $strin[$valor];
return $valor;
}

function cript($valor){   
    $vl = substr(date("s"),1,1) . invertnum(str_replace(".","2", substr(gettimeofday("s"),-1))) . invertnum(str_replace(".","2", substr(gettimeofday("s"),-2, -1)));

    if($valor < 1000){
        $valor = $valor * servernamber($vl);
        $tip   = "4";
    }
    else if($valor > 999999999){
        $valor = $valor;
        $tip   = "7";
    }
    else{
        $valor = $valor + servernamber($vl);
        $tip   = "9";
    }

    $valor = "$valor";

    
    if(isset($valor[1]))$valor[1]  =  invertnum($valor[1]);
    if(isset($valor[3]))$valor[3]  =  invertnum($valor[3]);
    if(isset($valor[7]))$valor[7]  =  invertnum($valor[7]);
    if(isset($valor[4]))$valor[4]  =  invertnum($valor[4]);
    if(isset($valor[6]))$valor[6]  =  invertnum($valor[6]);
    if(isset($valor[10]))$valor[10] =  invertnum($valor[10]);
    if(isset($valor[11]))$valor[11] =  invertnum($valor[11]);
    if(isset($valor[13]))$valor[13] =  invertnum($valor[13]);
    if(isset($valor[15]))$valor[15] =  invertnum($valor[15]);
    if(isset($valor[17]))$valor[17] =  invertnum($valor[17]);
    if(isset($valor[21]))$valor[21] =  invertnum($valor[21]);
    if(isset($valor[22]))$valor[22] =  invertnum($valor[22]);
    if(isset($valor[23]))$valor[23] =  invertnum($valor[23]);
    if(isset($valor[27]))$valor[27] =  invertnum($valor[27]);
    if(isset($valor[30]))$valor[30] =  invertnum($valor[30]);
    if(isset($valor[32]))$valor[32] =  invertnum($valor[32]);
    if(isset($valor[33]))$valor[33] =  invertnum($valor[33]);


    $valor =  "$valor$tip$vl";
    $valor =  $valor  = str_replace(" ","",$valor );
    return $valor;
}
function dcript($valor, $erro){
    if($valor == null){echo $erro; exit;}
    $val = substr($valor, -3, 3);
    $va = substr($valor, -4, 1);
    $valor = substr($valor, 0, -4);
    $valor = "$valor";
    if(isset($valor[1]))$valor[1] =  desinvertnum($valor[1]);
    if(isset($valor[3]))$valor[3] =  desinvertnum($valor[3]);
    if(isset($valor[7]))$valor[7] =  desinvertnum($valor[7]);
    if(isset($valor[4]))$valor[4] =  desinvertnum($valor[4]);
    if(isset($valor[6]))$valor[6] =  desinvertnum($valor[6]);
    if(isset($valor[10]))$valor[10] =  desinvertnum($valor[10]);
    if(isset($valor[11]))$valor[11] =  desinvertnum($valor[11]);
    if(isset($valor[13]))$valor[13] =  desinvertnum($valor[13]);
    if(isset($valor[15]))$valor[15] =  desinvertnum($valor[15]);
    if(isset($valor[17]))$valor[17] =  desinvertnum($valor[17]);
    if(isset($valor[21]))$valor[21] =  desinvertnum($valor[21]);
    if(isset($valor[22]))$valor[22] =  desinvertnum($valor[22]);
    if(isset($valor[23]))$valor[23] =  desinvertnum($valor[23]);
    if(isset($valor[27]))$valor[27] =  desinvertnum($valor[27]);
    if(isset($valor[30]))$valor[30] =  desinvertnum($valor[30]);
    if(isset($valor[32]))$valor[32] =  desinvertnum($valor[32]);
    if(isset($valor[33]))$valor[33] =  desinvertnum($valor[33]);
    
    if($va == 4){
        $valor = $valor / servernamber($val);
    }
    else if($va == 7){
        $valor = $valor;
    }
    else{
        $valor = $valor - servernamber($val);
    }
    $valor = str_ireplace(",","",number_format($valor));

return $valor;
}
 /*
$crip = cript(9999999);
echo "$crip <br>";
$dcrip = dcript($crip);
echo "<br>$dcrip <br>";
*/
//Formato com duas casa decimais
function formatomoeda($valor){
	$valor = number_format($valor , 2, ',', '.');
	return $valor;
}
//formata CPF ou CNPJ para print
function mcpfcnpj($cpf){
	$tamanho = strlen($cpf);
	if ( $tamanho == 11){
		$l1 = substr($cpf, 0, 3);
		$l2 = substr($cpf, 3, 3);
		$l3 = substr($cpf, 6, 3);
		$l4 = substr($cpf, 9, 2);
		return "CPF: $l1.$l2.$l3-$l4";
	}
	else if ( $tamanho == 14){
		$l1 = substr($cpf, 0, 2);
		$l2 = substr($cpf, 2, 3);
		$l3 = substr($cpf, 5, 3);
		$l4 = substr($cpf, 8, 4);
		$l5 = substr($cpf, 12, 2);
		return "CNPJ: $l1.$l2.$l3/$l4-$l5";
	}
	else return "CPF ou CNPJ incorreto";
	}


//valida CPF
function validaCPF($cpf) {
 
    // Verifica se um número foi informado
    if(empty($cpf)) {
        return false;
    }
 
    // Elimina possivel mascara
    //$cpf = ereg_replace('[^0-9]', '', $cpf);
    //$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
     
    // Verifica se o numero de digitos informados é igual a 11 
    if (strlen($cpf) != 11) {
        return false;
    }
    // Verifica se nenhuma das sequências invalidas abaixo 
    // foi digitada. Caso afirmativo, retorna falso
    else if ($cpf == '00000000000' || 
        $cpf == '11111111111' || 
        $cpf == '22222222222' || 
        $cpf == '33333333333' || 
        $cpf == '44444444444' || 
        $cpf == '55555555555' || 
        $cpf == '66666666666' || 
        $cpf == '77777777777' || 
        $cpf == '88888888888' || 
        $cpf == '99999999999') {
        return false;
     // Calcula os digitos verificadores para verificar se o
     // CPF é válido
     } else {   
         
        for ($t = 9; $t < 11; $t++) {
             
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
 
        return true;
    }
}

//fun��o numero de diaas entre duas datas
function dataemnumdias($data_inicial,$data_final){


// Usa a fun��o strtotime() e pega o timestamp das duas datas:
$time_inicial = strtotime($data_inicial);
$time_final = strtotime($data_final);

// Calcula a diferen�a de segundos entre as duas datas:
$diferenca = $time_final - $time_inicial; // 19522800 segundos

// Calcula a diferen�a de dias
$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
// Exibe uma mensagem de resultado:
return $dias;
}


//FUN��O DIA �TIL
function diasemana($data) {
	$ano =  substr("$data", 0, 4);
	$mes =  substr("$data", 5, -3);
	$dia =  substr("$data", 8, 9);

	$diasemana = date("w", mktime(0,0,0,$mes,$dia,$ano) );

	switch($diasemana) {
		case"0": return FALSE;
		case"6": return FALSE;
		default: return TRUE;
	}

	return FALSE;
}

//verifica feriados no ano
function dias_feriados($ano)
{
  //$ano  = 2017;

  $pascoa     = easter_date($ano); // Limite de 1970 ou ap�s 2037 da easter_date PHP consulta http://www.php.net/manual/pt_BR/function.easter-date.php
  $dia_pascoa = date('j', $pascoa);
  $mes_pascoa = date('n', $pascoa);
  $ano_pascoa = date('Y', $pascoa);

  $feriados = array(
    // Tatas Fixas dos feriados Nacionail Basileiras
    mktime(0, 0, 0, 1,  1,   $ano), // Confraterniza��o Universal - Lei n� 662, de 06/04/49
    mktime(0, 0, 0, 4,  21,  $ano), // Tiradentes - Lei n� 662, de 06/04/49
    mktime(0, 0, 0, 5,  1,   $ano), // Dia do Trabalhador - Lei n� 662, de 06/04/49
    mktime(0, 0, 0, 11,  30,  $ano), // Anivesario de muzambinho
    mktime(0, 0, 0, 12,  24,  2012), //
    mktime(0, 0, 0, 12,  31,  2012), //
    mktime(0, 0, 0, 9,  7,   $ano), // Dia da Independ�ncia - Lei n� 662, de 06/04/49
    mktime(0, 0, 0, 10,  12, $ano), // N. S. Aparecida - Lei n� 6802, de 30/06/80
    mktime(0, 0, 0, 11,  2,  $ano), // Todos os santos - Lei n� 662, de 06/04/49
    mktime(0, 0, 0, 11, 15,  $ano), // Proclama��o da republica - Lei n� 662, de 06/04/49
    mktime(0, 0, 0, 12, 25,  $ano), // Natal - Lei n� 662, de 06/04/49

    // These days have a date depending on easter
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 48,  $ano_pascoa),//2�feria Carnaval
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 47,  $ano_pascoa),//3�feria Carnaval
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa - 2 ,  $ano_pascoa),//6�feira Santa
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa     ,  $ano_pascoa),//Pascoa
    mktime(0, 0, 0, $mes_pascoa, $dia_pascoa + 60,  $ano_pascoa),//Corpus Cirist
  );

  sort($feriados);

  return $feriados;
}

//BLOQUEAR SQLINJECT
function trocanome($nome){
//if($nome)return addslashes($nome);
//else 
  return $nome;
}

//M�S ESCRITO  PARA M�S NUMERO
function mesDia($mm){
switch($mm){  case "Janeiro": $me = "01";     break;
              case "Fevereiro": $me = "02";   break;
              case "Mar�o": $me = "03";       break;
              case "Abril": $me = "04";       break;
              case "Maio": $me = "05";        break;
              case "Junho": $me = "06";       break;
              case "Julho": $me = "07";       break;
              case "Agosto": $me = "08";      break;
              case "Setembro": $me = "09";    break;
              case "Outubro": $me = "10";     break;
              case "Novembro": $me = "11";    break;
              case "Dezembro": $me = "12";    break;

}
return $me;
}

//M�S NUMERO PARA M�S ESCRITO
function diaMes($mm){
 switch($mm){ case "01": $me = "Janeiro";     break;
              case "02": $me = "Fevereiro";   break;
              case "03": $me = "Mar�o";       break;
              case "04": $me = "Abril";       break;
              case "05": $me = "Maio";        break;
              case "06": $me = "Junho";       break;
              case "07": $me = "Julho";       break;
              case "08": $me = "Agosto";      break;
              case "09": $me = "Setembro";    break;
              case "10": $me = "Outubro";     break;
              case "11": $me = "Novembro";    break;
              case "12": $me = "Dezembro";    break;

}
return $me;
}

//MOSTRA M�S E ANO
function dtatelapres($data){
          $data = str_replace("-","",$data);
          $dia = substr($data, -2);
          $mes = substr($data, -5, -3);
          $ano = substr($data,0, 4);
           $me = diaMes($mes);
        $dat = "$me de $ano";
          return $dat;
}

//MOSTRA ANO
function dtatelaRpres($data){
         $data = str_replace("-","",$data);
          $dia = substr($data, -2);
          $mes = substr($data, -5, -3);
          $ano = substr($data,0, 4);
           $me = diaMes($mes);
        $dat = "$ano";
          return $dat;


}

//FORMATA DATA COM O M�S ESCRITO PARA PRINT
function dtatelamostra($data){
		  $data = str_replace("-","",$data);
          $dia = substr($data, -2);
          $mes = substr($data, 4, 2);
          $ano = substr($data,0, 4);
          	if(checkdate($mes,$dia,$ano)) {
           $me = diaMes($mes);
        $dat = "$dia de $me de $ano";

          return $dat;
	}
	else {
		return 1;
	}

}

// FORMATA DATA PARA PTINT
function dtatela($data){
  $data = str_replace("-","",$data);
  $dia = substr($data, 6, 2);
  $mes = substr($data, 4, 2);
  $ano = substr($data,0, 4);
  if(checkdate($mes,$dia,$ano)) {
    $dat = "$dia/$mes/$ano";
    return $dat;
	}
	else {
		return '';
	}

}

//RETORNA ANO
function dtarpre($data){
          $dia = substr($data, -2);
          $mes = substr($data, -5, -3);
          $ano = substr($data,0, 4);
          	if(checkdate($mes,$dia,$ano)) {
        $dat = "$ano";
          return $dat;
	}
	else {
		return 1;
	}

}

// FORMATA DATA PARA O BD
function dtafor($data){
          $dia = substr($data, 0, 2);
          $mes = substr($data, -7, -5);
          $ano = substr($data,  -4);
          	if(checkdate($mes,$dia,$ano)) {
        $dat = "$ano-$mes-$dia";
          return $dat;
	}
	else {
		return false;
	}

}

//fun��o para colocar ifen
function dtapcal($data){
          $dia = substr($data, 0, 2);
          $mes = substr($data, -7, -5);
          $ano = substr($data,  -4);
          	if(checkdate($mes,$dia,$ano)) {
        $dat = "$ano-$mes-$dia";
          return $dat;
	}
	else {
		return 1;
	}

}

//Formata CPF 
function formata_pdf($cpf){
          $cpf1 = substr($cpf,0,3);
          $cpf2 = substr($cpf,3,3);
          $cpf3 = substr($cpf,6,3);
          $cpf4 = substr($cpf,9,2);
          return $cpf1.".".$cpf2.".".$cpf3."-".$cpf4;

}

function formata_pdf_db($cpf){
          return str_replace("-", "", str_replace(".", "", $cpf));

}

function dtconvertbd($data){
		  $data = str_replace("-","",$data);
          $dia = substr($data, -2);
          $mes = substr($data, 4, 2);
          $ano = substr($data,0, 4);
          $dat = "$ano-$mes-$dia";
          return $dat;

}


// RETORNA O PRIMEIRO ARQUIVO
function gafoto($idga){


$dn = opendir ("galeria/$idga/");

while ($file = readdir ($dn)) {
       if (($file != ".") && ($file != "..") && ($file != "Thumbs.db")){
           return $file ;


       }
}
closedir($dn);

}

//RETORNA IDADE APARTIR DE UMA DATA
function anivr($data){
        if(!$data) return NULL;
        $data = str_replace("-","",$data);
        $dia = substr($data, -2);
        $mes = substr($data, -5, -3);
        $ano = substr($data,0, 4);
        $a = date("Y");
        $m = date("m");
        $d = date("d");
        $anv = $a - $ano;
        if ($m < $mes) $anv -=1;
        if ($m == $mes){
            if ($d < $dia) $anv -=1;
        }
        return $anv;
}

//RETORNA M�S E ANO PARA PRINT
function datapres($data){
        $data = str_replace("-","",$data);
        $dia = substr($data, -2);
        $mes = substr($data, -5, -3);
        $ano = substr($data,0, 4);
        $anv = "$mes/$ano";
        return $anv;
}

//RETORNA ANO PARA PRINT
function datapre($data){
        $data = str_replace("-","",$data);
        $dia = substr($data, -2);
        $mes = substr($data, -5, -3);
        $ano = substr($data,0, 4);
        $anv = "$ano";
        return $anv;
}

//RETORNA DATA E HORA PARA O PRINT
function dtahoraela($data){
          $data = str_replace("-","",$data);
          $dia = substr($data,6,2);
          $mes = substr($data,4,2);
          $ano = substr($data,0,4);
          $hora = substr($data,8,9);

          //return $data;
          return "$dia/$mes/$ano $hora";
       // $dat = "$dia/$mes/$ano";
        //  return $dat;
}
//RETORNA DATA E HORA PARA O PRINT
function dtahoraela2($data){
          $data     = str_replace("-","",$data);
          $dia      = substr($data,6,2);
          $mes      = substr($data,4,2);
          $ano      = substr($data,0,4);
          $hora     = substr($data,8,2);
          $minuto   = substr($data,11,2);

          //return $data;
          return "$dia/$mes/$ano ás $hora:$minuto:00 Horas";
       // $dat = "$dia/$mes/$ano";
        //  return $dat;
}

//calcula data, dias e valores
function calcular2($dchec, $ndia, $valor){
if(($dchec == "") || ($ndia == "") || ($valor == "")) return utf8_encode("
									Prencha os campos Data, Bom Para e Valor do Cheque");

//converte a a data em numero de dias
$in = dtapcal($dchec);
$fi = dtapcal($ndia);
	if($in == 1)return utf8_encode("Data do Cheque Inválida");
	if($fi == 1)return utf8_encode("Data do Bom Para Inválida");
$ndia = dataemnumdias($in,$fi);

//a data final
$dt = dtapcal($dchec);

$dt = date('d/m/Y', strtotime("+$ndia days",strtotime($dt)));
$wh = 0;
While($wh == 0){
$wh = 1;
//varifica os feriados
//$ano_=date("Y");
$ano_= substr($dt, -4);
foreach(dias_feriados($ano_) as $a)
{
 $dat = date("d/m/Y",$a);

	if($dat == $dt)
	{$ndia++;
	$dt = dtapcal($dt);
	$dt = date('d/m/Y', strtotime("+1 days",strtotime($dt)));

	}

}

	//verifica dia da semna
	$dtm = dtapcal($dt);
	$funh = diasemana($dtm);
	if ($funh > 0){
		$ndia = $ndia + $funh;
		$wh = 0;

		$dt = dtapcal($dchec);
	$dt = date('d/m/Y', strtotime("+$ndia days",strtotime($dt)));
	}
}

$dtfinal = $dt;
//verifica a taxa de juro

$juro = $ndia * 0.002;

//valor ezato do cheque
$valor = $valor / (1 - $juro);

/*/verifica limite
	$datt = date("ymd");
	$ve = mysql_query("select limite from cliente where id = $idcli");
		$l = mysql_fetch_row($ve);
	$ver = mysql_query("select sum(valor) from cheque where datavenc >= $datt and idcli = $idcli");
		$li = mysql_fetch_row($ver);
		if ($l[0] <= ($li[0] + $valor)) $lim = "Ultrapassou o limite de $l[0]";
		else $lim = "Esta dentro do Limite de $l[0]";
		//return $lim;*/


//verifica o valor

$val = $valor * $juro;
$valcli = $valor - $val;
$ju = $juro*100;
$dtven = dtafor($dtfinal);
$val = formatomoeda($val);
$valcli = formatomoeda($valcli);
$valor = formatomoeda($valor);


/*$ret = utf8_encode("
		Valor do Cheque: $valor<br />
		Data de Pagamento: $dtfinal<br />
		Data p/ dep cheque: $dchec<br />
		N de Dias: $ndia<br />
		Juros cobrado: %$ju<br />
		Valor do Juro: R/$ $val </br />
		Valor do Cliente: R/$ $valcli");*/


		$ret[0] = $valor;
		$ret[1] = $dtfinal;
		$ret[2] = $dchec;
		$ret[3] = $ndia;
		$ret[4] = $ju;
		$ret[5] = $val;
		$ret[6] = $valcli;

return array($ret[0],$ret[1],$ret[2],$ret[3],$ret[4],$ret[5],$ret[6]);

 }



?>