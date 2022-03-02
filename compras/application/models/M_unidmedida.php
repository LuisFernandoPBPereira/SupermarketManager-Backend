<?php
defined('BASEPATH') OR exit('No direct script acess allowed');

class M_unidmedida extends CI_Model{
    public function inserir($sigla, $descricao, $usuario){
        
        //Montando a query para consulta da verficação
        $sql2 = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
        
        //Acionando a classe para a consulta
        $this->db->query($sql2);

        $retorno = $this->db->query($sql2);

        if($retorno->num_rows() > 0){
        
            $sql = "INSERT INTO unid_medida(sigla, descricao, usucria)
                        values('$sigla', '$descricao', '$usuario')";
            
            $this->db->query($sql);

            //Verificar se a inserção ocorreu com sucesso
            if($this->db->affected_rows() > 0){
                //Fazemos a inserção no LOG  na nuvem
                //Fazemos a instância da model M_log
                $this->load->model('m_log');

                //Fazemos a chamada do método de iserção de LOG
                $retorno_log = $this->m_log->inserir_log($usuario, $sql);

                if($retorno_log['codigo'] == 1){
                    $dados = array('codigo' => 1,
                                'msg' => 'Unidade de medida cadastrada corretamente');
                }else{
                    $dados = array('codigo' => 7,
                                'msg' => 'Houve algum problema no salvamento do Log, porém,
                                            Unidade de Medida cadastrada corretamente');
                }

            }else{
                $dados = array('codigo' => 6,
                            'msg' => 'Houve algum problema na inserção na tabela de unidade de medida');
            }
        }else{
            $dados = array('codigo' => 8,
                           'msg' => 'O usuário selecionado para inserção não existe ou está incorreto.');
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }

    public function consultar($codigo, $sigla, $descricao){
        //------------------------------------------------
        //Função que servirá para quatro tipos consulta:
        // * Para todas as unidades de medida;
        // * Para uma determinada sigla de unidade;
        // * Para um código de unidade de medida;
        // * Para descrição da unidade de medida;
        //------------------------------------------------

        //Query para consultar dados de acordo com parâmetros passados
        $sql = "SELECT * FROM unid_medida WHERE estatus = '' ";

        if($codigo != '' && $codigo != 0){
            $sql = $sql . "AND cod_unidade = '$codigo' ";
        }

        if($sigla != ''){
            $sql = $sql . "AND sigla LIKE '$sigla' ";
        }

        if($descricao != ''){
            $sql = $sql . "AND descricao LIKE '%descricao%' ";
        }

        $retorno = $this->db->query($sql);

        //Verificar se a consulta ocorreu com sucesso
        if($retorno->num_rows() > 0){
            $dados = array('codigo' => 1,
                           'msg' => 'Consulta efetuada com sucesso',
                           'dados' => $retorno->result());
        }else{
            $dados = array('codigo' => 6,
                           'msg' => 'Dados não encontrados');
        }
        //Envia o aray $dados com as informações tratadas
        //acima pela etrutura de decisão if
        return $dados;
    }

    public function alterar($codigo, $sigla, $descricao, $usuario){
        //Query de atualização dos dados
        if(trim($sigla) != '' && trim($descricao) != ''){
            $sql = "UPDATE unid_medida SET sigla = '$sigla', descricao = '$descricao'
                    WHERE cod_unidade = $codigo";
        }elseif(trim($sigla) != ''){
            $sql = "UPDATE unid_medida SET sigla = '$sigla' WHERE cod_unidade = $codigo";
        }else{
            $sql = "UPDATE unid_medida SET descricao = '$descricao' WHERE cod_unidade = $codigo";
        }

        $this->db->query($sql);

        //Verificar se a atualização ocorreu com sucesso
        if($this->db->affected_rows() > 0){
            //Fazemos a inserção no LOG na nuvem
            //Fazemos a instância da model M_log
            $this->load->model('m_log');

            //Fazemos a chamada do método de inserção do LOG
            $retorno_log = $this->m_log->inserir_log($usuario, $sql);

            if($retorno_log['codigo'] == 1){
                $dados = array('codigo' => 1,
                               'msg' => 'Unidade de medida atualizada corretamente');
            }else{
                $dados = array('codigo' => 7,
                               'msg' => 'Houve algum problema no salvamento do LOG, porém,
                                         unidade de medida cadastrada corretamente');
            }
        }else{
            $dados = array('codigo' => 6,
                           'msg' => 'Houve algum problema na atualização na tabela de unidade de medida');
        }
        //Envia o array $dados com as informações tratadas
        //acima pela estrutura de decisão if
        return $dados;
    }

    public function desativar($codigo, $usuario){
        //Há necessidade de verificar se existe algum produto com
        //essa unidade de medida já cadastrado, se tiver não podemos
        //desativar essa unidade

        $sql = "SELECT * FROM produtos WHERE unid_medida = '$codigo' AND estatus = '' ";

        $retorno = $this->db->query($sql);

        //verificar se a consulta trouxe algum produto
        if($retorno->num_rows() > 0){
            //Não posso fazer a desativação
            $dados = array('codigo' => 3,
                           'msg' => 'Não podemos desativar, existem produtos com essa unidade de medida cadastrado(s).'); 
        }else{
            //Query de atualização dos dados
            $sql2 = "UPDATE unid_medida SET estatus = 'D' WHERE cod_unidade = '$codigo'";

            $this->db->query($sql2);

            //verificar se a atualização ocorreu com sucesso
            if($this->db->affected_rows() > 0){
                //Fazemos a inserção no Log na nuvem
                //Fazemos a instância da model M_log
                $this->load->model('m_log');

                //Fazemos a chamada do método de inserção do Log
                $retorno_log = $this->m_log->inserir_log($usuario, $sql2);

                if($retorno_log['codigo'] == 1){
                    $dados = array('codigo' => 1,
                                   'msg' => 'Unidade de medida DESATIVADA corretamente');
                }else{
                    $dados = array('codigo' => 8,
                                   'msg' => 'Houve algum problema no salvamento do Log, porém,
                                   unidade de medida desativada corretamente');
                }
            }
            //Envia o array $dados com as informações tratadas
            //acima nela estruturada de decisão if
            return $dados;
        }
    }
}
?>