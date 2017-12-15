<?php
ini_set('display_errors',1);
ini_set('display_startup_erros',1);
error_reporting(E_ALL);
//include("../php/funcao_0.2.php");

function conect(){
//$endereco   = strtolower($_SERVER ['REQUEST_URI']);
//$categoria  = "adm";
$arquivo = fopen ("conf/conf/config.txt","r");
    $cont = 1;
    $parametro = 0;
    while(!feof($arquivo)) {
        $array=explode(" ",fgets($arquivo));
        switch ($array[0]) {
                    case 'servidor':    $servidor   = rtrim($array[1]);
                        break;
                    case 'usuario':     $usuario    = rtrim($array[1]);
                        break;
                    case 'senha':       $senha      = rtrim($array[1]);
                        break;
                    case 'banco':       $banco      = rtrim($array[1]);
                        break;
                    default:  exit; echo "ERRO - BD";
                        break;
                }        
    }
//echo $servidor."<br />".$usuario."<br />".$senha."<br />".$banco."<br />";

$mysql = new mysqli($servidor, $usuario, $senha, $banco);
    if (mysqli_connect_errno()) {
        die(utf8_encode('Não foi possível conectar-se ao banco de dados: ' . mysqli_connect_error()));
    }
    $mysql->set_charset("utf8");
   
    return $mysql;
}

//conect();

function insert_bd($query){
    $conect = conect();
    $sql = "INSERT INTO ".$query->tabela." (".$query->campo.") VALUES(".$query->dados.")";
    echo $sql;
    if($conect->query($sql)or die(erro_bd($conect->errno))){$conect->close();return true;}
    else{$conect->close();return false;}
}
function insert_bd_return_id($query){
    $conect = conect();
    $sql = "INSERT INTO ".$query->tabela."(".$query->campo.") VALUES(".$query->dados.")";
    echo $sql;
    if($conect->query($sql)or die(erro_bd($conect->errno))){$id = $conect->insert_id; $conect->close();return $id;}
    else{$conect->close();return false;}
}
function insert_bd_verifica($query){
    $conect = conect();
    $sql = "INSERT INTO ".$query->tabela."(".$query->campo.") SELECT ".$query->dados." FROM DUAL   
            WHERE NOT EXISTS (SELECT 1 FROM ".$query->tabela." WHERE ".$query->verifica.")";
    echo $sql;
    if($conect->query($sql)or die(erro_bd($conect->errno))){$ret = $conect->affected_rows; $conect->close();return $ret;}
    else{$conect->close();return false;}
}
function update_bd($query){
    $conect = conect();
    $sql = "UPDATE ".$query->tabela." SET ".$query->campo." WHERE ".$query->parametro;
    echo $sql;
    if ($conect->query($sql)or die(erro_bd($conect->errno))){$conect->close();return true;}
    else{$conect->close();return false;}
}

function select_db($query){
    $conect = conect();
    $sql = "SELECT ".$query->campo." FROM ".$query->tabela." WHERE ".$query->parametro;
    //echo $sql;
    $result = $conect->query($sql) or die(erro_bd($conect->errno));
    $conect->close();
    return $result;
}

function select_db_2($query){
    $conect = conect();
    $sql = "SELECT ".$query->campo." FROM ".$query->tabela." ORDER BY ".$query->parametro;
    //echo $sql;
    $result = $conect->query($sql) or die(erro_bd($conect->errno));
    $conect->close();
    return $result;
}

function delete_db($query){
    $conect = conect();
    $sql = "DELETE  FROM ".$query->tabela." WHERE ".$query->parametro;
    //echo $sql;
    $result = $conect->query($sql) or die(erro_bd($conect->errno));
    $conect->close();
    return $result;
}

function exec_procedure($query){
    $conect = conect();
    $sql = "CALL ".$query->nome."(".$query->variavel.")";
    //echo $sql;
    if($conect->query($sql)or die(erro_bd($conect->errno))){$conect->close();return true;}
    else{ $conect->close();return false;}    
}
?>