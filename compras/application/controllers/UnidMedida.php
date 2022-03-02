<?php
defined('BASEPATH') OR exit('No direct script acess allowed');

class UnidMedida extends CI_Controller{
    
    public function inserir(){
        // Sigla e Descrição
        // recebidas via JSON e colocadas em variàveis
        // Retorno possíveis
        // 1 - Unidade cadastrada corretamente (Banco)
        // 2 - Faltou informar a sigla (FrontEnd)
        // 3 - Quantidade de caracteres da sigla é superior a 3 (FrontEnd)
        // 4 - Descrição não informada (FrontEnd)
        // 5 - Usuário não informado (FrontEnd)
        // 6 - Houve algum problema no insert da tabela (Banco)
        // 7 - Houve problema no salvamento do LOG, mas a unidade foi inclusa (LOG) 

        $json = file_get_contents("php://input");
        $resultado = json_decode($json);

        $sigla     = $resultado->sigla;
        $descricao = $resultado->descricao;
        $usuario   = $resultado->usuario;

        //Faremos uma validação para sabermops se todos os dados
        //foram enviados corretamente
        if (trim($sigla) == ''){
            $retorno = array("codigo" => 2,
                            "msg" => "Sigla não informada.");
        }elseif (strlen(trim($sigla)) > 3){
            $retorno = array("codigo" => 3,
                            "msg" => "Sigla pode conter no máximo 3 caracteres.");
        }elseif (trim($descricao) == ""){
            $retorno = array("codigo" => 4,
                            "msg" => "Descrição não informada.");
        }elseif (trim($usuario) == ""){
            $retorno = array("codigo" => 5,
                            "msg" => "Usuário não informado.");
        }else{
            //Realizo a instância da Model
            $this->load->model("m_unidmedida");

            //Atributo $retorno recebe array com informações
            $retorno = $this->m_unidmedida->inserir($sigla, $descricao, $usuario);
        }

        //Retorno no formato JSON
        echo json_encode($retorno);
    }

    public function consultar(){
        //Código, Sigla e Descrição
        //recebidos via JSON e colocados em variáveis
        //Retornos possíveis:
        //1 - Dados consultados corretamente (Banco)
        //2 - Quantidade de caracteres da sigla é superior a 3 (FrontEnd)
        //6 - Dados não encontrados (Banco)
        $json = file_get_contents("php://input");
        $resultado = json_decode($json);

        $codigo    = $resultado->codigo;
        $sigla     = $resultado->sigla;
        $descricao = $resultado->descricao;

        //Verifico somente a quantidade de caracteres da sigla, poder ter até 3
        //caracteres ou nenhum para trazer todas as siglas
        if(strlen(trim($sigla)) > 3){
            $retorno = array('codigo' => 2,
                             'msg' => 'Sigla pode conter no máximo 3 caracteres ou nenhum para todas');
        }else{
            //Realizo a instância da Model
            $this->load->model('m_unidmedida');

            //Atributo $retorno recebe array com informações
            //da consulta dos dados
            $retorno = $this->m_unidmedida->consultar($codigo, $sigla, $descricao);
        }
        //Retorno no formato JSON
        echo json_encode($retorno);
    }

    public function alterar(){
        //Código, Sigla e Descrição
        //recebidos via JSON e colocadas em variáveis
        //Retornos possíveis:
        //1 - Dado(s) alterado(s) corretamente (Banco)
        //2 - Faltou informar o código (FrontEnd)
        //3 - Quantidade de caracteres da sigla é superior a 3 (FrontEnd)
        //4 - Sigla ou Descrição não informadas, aí não tem o que alterar (FrontEnd)
        //5 - Usuário não informado (FrontEnd)
        //6 - Dados não encontrados (Banco)
        //7 - Houve problema no salvamento do LOG, mas a unidade foi inclusa (LOG)
        $json = file_get_contents('php://input');
        $resultado = json_decode($json);

        $codigo    = $resultado->codigo;
        $sigla     = $resultado->sigla;
        $descricao = $resultado->descricao;
        $usuario   = $resultado->usuario;

        //Faremos uma validação para sabermos se os dados
        //foram enviados corretamente

        if(trim($codigo) == ''){
            $retorno = array('codigo' => 2,
                             'msg' => 'Código não informado.');
        }elseif(strlen(trim($sigla)) > 3){
            $retorno = array('codigo' => 3,
                             'msg' => 'Sigla pode conter no máximo 3 caracteres.');
        }elseif(trim($descricao) == '' && trim($sigla) == ''){
            $retorno = array('codigo' => 4,
                             'msg' => 'Sigla e a Descrição não forma informadas;');
        }elseif(trim($usuario) == ''){
            $retorno = array('codigo' => 5,
                             'msg' => 'Usuário não informado');
        }else{
            //Realizo a instância da Model
            $this->load->model('m_unidmedida');

            //Atributo $retorno recebe array com informações
            //da validação do acesso
            $retorno = $this->m_unidmedida->alterar($codigo, $sigla, $descricao, $usuario);
        }

        //Retorno no formato JSON
        echo json_encode($retorno);
    }

    public function desativar(){
        //Código da unidade recebido via JSON e colocado em variável
        //Retornos possíveis:
        //1 - Unidade desativada corretamente (Banco)
        //2 - Código não informado
        //3 - Existem produtos cadastrados com essa unidade de medida
        //5 - Usuário não informado (FrontEnd)
        //6 - Dados não encontrados (Banco)
        //7 - Houve problema no salvametno do LOG, mas a unidade foi alterada (LOG)
        $json = file_get_contents('php://input');
        $resultado = json_decode($json);
        $usuario = $resultado->usuario;

        $codigo = $resultado->codigo;

        //Validação para tipo de usuário que deverá ser ADMINISTRADOR, COMUM ou VAZIO
        if(trim($codigo) == ''){
            $retorno = array('codigo' => 2,
                             'msg' => 'Código da unidade não informado');
        }elseif(trim($usuario) == ''){
            $retorno = array('codigo' => 5,
                             'msg' => 'Usuário não informado');
        }else{
            //Realizo a instância da Model
            $this->load->model('m_unidmedida');

            //Atributo $retorno recebe array com informações
            $retorno = $this->m_unidmedida->desativar($codigo, $usuario);
        }
        //Retorno no formato JSON
        echo json_encode($retorno);
    }
}
?>