<?php
namespace models;

/**
 * Classe Model de Prospect
 * 
 */

class Prospect{
    /**
     * Nome do Prospect
     * @var string
     */
    public $nome;

    /**
     * Email do Prospect
     * @var string
     */
    public $email;

    /**
     * Celular do Prospect
     * @var string
     */
    public $celular;

    /**
     * Função que carrega os atributos da classe Prospect
     * @param string $nome Nome do Prospect
     * @param string $email Email do Prospect
     * @param string $celular Celular do Prospect
     */
    public function addProspect($nome, $email, $celular){
        $this->cod_Prospect = $cod_Prospect;
        $this->nome = $nome;
        $this->email = $email;
    }
}



?>