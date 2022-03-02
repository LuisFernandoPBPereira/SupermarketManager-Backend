<?php
defined('BASEPATH') OR exit('No direct script acess allowed');

class M_acesso extends CI_Model {

    public function validalogin($usuario, $senha){
        //Atributo retorno recebe o resultado do SELECT Realizado
        //na tabela de usuários, lembrando da função MD5()
        $retorno = $this->db->query("SELECT * FROM usuarios
                                     WHERE usuario = '$usuario'
                                       AND senha   = md5('$senha')
                                       AND estatus = ''");

        //Verificaa se a quantidade de linhas trazidas na consulta
        //é superior a 0, isso quer dizer que foi encontrado o
        //usuário e senha passados pela Controller.

        //Criando um arrau com dois elementos para retorno do resultado
        //1 - Codigo da mensagem
        //2 - Descrição da mensagem


        //Verificando se usuário está desativado na hora do login
        $retorno_desativado = $this->db->query("SELECT * FROM usuarios
                                     WHERE usuario = '$usuario'
                                       AND senha   = md5('$senha')
                                       AND estatus = 'D'");

        //Verificando se o usuário está correto
        $retorno_usuario = $this->db->query("SELECT * FROM usuarios
                                             WHERE usuario = '$usuario'");

        //Verificando se a senha está correta
        $retorno_senha = $this->db->query("SELECT * FROM usuarios
                                           WHERE senha = md5('$senha')");

        //Estruturas de decisão conforme as verificações acima
        if($retorno->num_rows() > 0){
            $dados = array('codigo' => 1,
                        'msg' => 'Usuário correto');
        }
        elseif($retorno_desativado->num_rows() > 0){
            $dados = array('codigo' => 4,
                           'msg' => 'Usuário desabilitado para acesso');
        }
        elseif($retorno_usuario->num_rows() == 0){
            $dados = array('codigo' => 5,
                           'msg' => 'Usuário incorreto');
        }
        elseif($retorno_senha->num_rows() == 0){
            $dados = array('codigo' => 6,
                           'msg' => 'Senha incorreta');
        }

        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if

        return $dados;
    }
}

?>