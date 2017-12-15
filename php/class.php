<?php
//clsses para utilizar acesso ao banco de dados;
    class consulta{
        public  $campo;
        public  $tabela;
        public  $parametro;

        function select_db(){
            $conect = conect();
            $sql = "SELECT ".$this->campo." FROM ".$this->tabela." WHERE ".$this->parametro;
            //echo $sql;
            $result = $conect->query($sql) or die(erro_bd($conect->errno));
            $conect->close();
            return $result;
        }

        function select_db_2(){
            $conect = conect();
            $sql = "SELECT ".$this->campo." FROM ".$this->tabela." ORDER BY ".$this->parametro;
            //echo $sql;
            $result = $conect->query($sql) or die(erro_bd($conect->errno));
            $conect->close();
            return $result;
        }
    }
    class atualiza{
        public  $campo;
        public  $tabela;
        public  $parametro;

        public function update_bd(){
            $conect = conect();
            $sql = "UPDATE ".$this->tabela." SET ".$this->campo." WHERE ".$this->parametro;
            //echo $sql;
            if ($conect->query($sql)or die(erro_bd($conect->errno))){$conect->close();return true;}
            else{$conect->close();return false;}
        }
    }
    class insercao{
        public $campo;
        public $tabela;
        public $dados;

        function insert_bd(){
            $conect = conect();
            $sql = "INSERT INTO ".$this->tabela." (".$this->campo.") VALUES(".$this->dados.")";
            //echo $sql;
            if($conect->query($sql)or die(erro_bd($conect->errno))){$conect->close();return true;}
            else{$conect->close();return false;}
        }

        function insert_bd_muitos(){
            $conect = conect();
            $sql = "INSERT INTO ".$this->tabela." (".$this->campo.") VALUES".$this->dados;
            //echo $sql;
            if($conect->query($sql)or die(erro_bd($conect->errno))){$conect->close();return true;}
            else{$conect->close();return false;}
        }

        function insert_bd_return_id(){
            $conect = conect();
            $sql = "INSERT INTO ".$this->tabela."(".$this->campo.") VALUES(".$this->dados.")";
            //echo $sql;
            if($conect->query($sql)or die(erro_bd($conect->errno))){$id = $conect->insert_id; $conect->close();return $id;}
            else{$conect->close();return false;}
        }
    }
    class exclui{
        public $tabela;
        public $parametro;

        public function delete_db(){
        $conect = conect();
        $sql = "DELETE  FROM ".$this->tabela." WHERE ".$this->parametro;
        //echo $sql;
        $result = $conect->query($sql) or die(erro_bd($conect->errno));
        $conect->close();
        return $result;
}
    }
    class insercao_verifica{
        public $campo;
        public $tabela;
        public $dados;
        public $verifica;

        public function insert_verifica(){
            $conect = conect();
            $sql = "INSERT INTO ".$this->tabela."(".$this->campo.") SELECT ".$this->dados." FROM DUAL   
                    WHERE NOT EXISTS (SELECT 1 FROM ".$this->tabela." WHERE ".$this->verifica.")";
            //echo $sql;
            if($conect->query($sql)or die(erro_bd($conect->errno))){$ret = $conect->affected_rows; $conect->close();return $ret;}
            else{$conect->close();return false;}
        }

        public function insert_verifica_ret_id(){
            $conect = conect();
            $sql = "INSERT INTO ".$this->tabela."(".$this->campo.") SELECT ".$this->dados." FROM DUAL   
                    WHERE NOT EXISTS (SELECT 1 FROM ".$this->tabela." WHERE ".$this->verifica.")";
            //echo $sql;
            if($conect->query($sql)or die(erro_bd($conect->errno))){
                //$ret = $conect->affected_rows; 
                $id = $conect->insert_id;
                $conect->close();
                return $id;
            }
            else{$conect->close();return false;}
        }
    }

    class view{
        public  $nome;
        public  $campo;
        public  $tabela;

        public function criar_view(){
            $conect = conect();
            $conect->query("DROP VIEW  IF EXISTS ".$this->nome);
            $sql = "CREATE VIEW ".$this->nome." AS SELECT ".$this->campo." FROM ".$this->tabela;
            //echo $sql;
            if ($conect->query($sql)or die(erro_bd($conect->errno))){$conect->close();return true;}
            else{$conect->close();return false;}

        }
    }


    class sessao_login{
        public $nome;
        public $id;
    }
    class pagina{
        public $titulo;
        Public $subtitulo;
        public $conteudo;
        public $mensagem;
    }
    class menu{
        public $titulo;
        public $conteudo;
    }

    class email{
        public  $e_candidato;
        public  $contato;
        public  $mensagem;
        public  $headers;
        public  $codigo;


        public function email_via(){
            $solicitacao = new consulta();
              $solicitacao->campo       = 'codigo, titulo, conteudo, imagem, ativo, adm, candidato';
              $solicitacao->tabela      = 'cur_email';
              $solicitacao->parametro   = "codigo = '".$this->codigo."'";

              $exc_solicitacao = $solicitacao->select_db();

            $ml = $exc_solicitacao->fetch_object();
            $this->contato      = $ml->titulo." - enviado em ".date('d/m/Y H:i:s');
            $this->mensagem     = $ml->conteudo."<br />".$ml->imagem; //."<p><img src='".$ml->imagem."' />"; 

                $this->headers  = "MIME-Version: 1.0\r\n";
                $this->headers .= "Content-type: text/html; charset=utf-8" . "\r\n";
                $this->headers .= "X-Mailer: PHP/" . phpversion ();

            if($ml->ativo == 'S'){
                if($ml->adm == 'S'){
                    if(mail(email::buscar_email_adm(),$this->contato,$this->mensagem,$this->headers)) ;//echo "SUCESSO A !!";
                    else echo email::buscar_email_adm().",".$this->contato.",".$this->mensagem.",".$this->headers;
                }
                if($ml->candidato == 'S'){
                    if(!$this->e_candidato) $this->e_candidato = email::buscar_email_candidato();
                    if(mail($this->e_candidato,$this->contato,$this->mensagem,$this->headers)) ;//echo "SUCESSO C !!";
                    else echo $this->e_candidato.",".$this->contato.",".$this->mensagem.",".$this->headers;
                }
            }
           
            //mail($this->e_candidato, $this->contato, $this->mensagem,$this->headers);
            
            //return $para.", ".$contato.", ".$mensagem.", ".$headers;
            //return $this->contato."<br />".$this->headers;
            //return $this->para."<br />".$this->headers;
            //else echo "<script>alert (\"Email foi enviado !!!!\");</script>";
        }

        public function buscar_email_adm(){ 
            $consulta = new consulta();
                    $consulta->campo      = 'email';
                    $consulta->parametro  = 'id_administrador';
                    $consulta->tabela     = 'cur_administrador';
            $lista = $consulta->select_db_2();
            $retorna = NULL;
           while($list = $lista->fetch_object()){
                $retorna .= $list->email.",";
            }
            return substr($retorna,0,-1);
        }

        public function buscar_email_candidato(){ 
            $consulta = new consulta();
                    $consulta->campo      = 'email';
                    $consulta->parametro  = "envia_email = 'S'";
                    $consulta->tabela     = 'cur_candidato';
            $lista = $consulta->select_db_2();
            $retorna = NULL;
           while($list = $lista->fetch_object()){
                $retorna .= $list->email.",";
            }
            return substr($retorna,0,-1);
        }
        
    }

?>