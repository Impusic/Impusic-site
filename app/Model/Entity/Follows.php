<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Follows{
    /**
     * ID da ação
     * @var integer
     */
    public $id;

    /**
     * ID do usuário
     * @var integer
     */
    public $idFollow;

    /**
     * ID de quem ta sendo seguido
     * @var integer
     */
    public $idUser;

    /**
     * Método responsável por cadastrar a instancia atual no banco de dados
     * @return boolean
     */
    public function cadastrar(){
        //INSERE A INSTANCIA NO BANCO
        $this->id = (new Database('follows'))->insert([
            'idFollow' => $this->idFollow,
            'idUser' => $this->idUser
        ]);

        //SUCESSO
        return true;
    }

    /**
     * Método responsável por atualizar os dados do banco
     * @return boolean
     */
    public function atualizar(){
        return (new Database('follows'))->update('id = '.$this->id,[
            'idFollow' => $this->idFollow,
            'idUser' => $this->idUser
        ]);
    }

    /**
     * Método responsável por excluir um usuário do banco
     * @return boolean
     */
    public function excluir($id){
        return (new Database('follows'))->delete('id = '.$id);
    }

    /**
     * Método responsável por retornar Usuarios
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getFollows($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('follows'))->select($where,$order,$limit,$fields);
    }
}