<?php

namespace App\Model\Entity;

use \WilliamCosta\DatabaseManager\Database;

class Comments{
    /**
     * ID da ação
     * @var integer
     */
    public $id;

    /**
     * ID do vídeo
     * @var string
     */
    public $videoId;

    /**
     * Texto do comentário
     * @var string
     */
    public $text;

    /**
     * Verifica se o texto já foi editado
     * @var boolean
     */
    public $edited = false;

    /**
     * Data de criação do comentário
     * @var string
     */
    public $time;

    /**
     * Nome de usuário do comentário
     * @var integer
     */
    public $commentName;

    /**
     * UserName de usuário do comentário
     * @var integer
     */
    public $commentUsername;

    /**
     * Método responsável por cadastrar a instancia atual no banco de dados
     * @return boolean
     */
    public function cadastrar(){
        //INSERE A INSTANCIA NO BANCO
        $this->id = (new Database('comments'))->insert([
            'videoId' => $this->videoId,
            'text' => $this->text,
            'edited' => $this->edited,
            'time' => $this->time,
            'commentName' => $this->commentName,
            'commentUsername' => $this->commentUsername
        ]);

        //SUCESSO
        return true;
    }

    /**
     * Método responsável por atualizar os dados do banco
     * @return boolean
     */
    public function atualizar(){
        return (new Database('comments'))->update('id = '.$this->id,[
            'videoId' => $this->videoId,
            'text' => $this->text,
            'edited' => true,
            'time' => $this->time,
            'commentName' => $this->commentName,
            'commentUsername' => $this->commentUsername
        ]);
    }

    /**
     * Método responsável por excluir um usuário do banco
     * @return boolean
     */
    public function excluir($id){
        return (new Database('comments'))->delete('id = '.$id);
    }

    /**
     * Método responsável por retornar uma instancia com base em seu id
     * @param integer $id
     * @return User
     */
    public static function getCommentById($id){
        return self::getComments('id = "'.$id.'"')->fetchObject(self::class);
    }

    /**
     * Método responsável por retornar Usuarios
     * @param string $where
     * @param string $order
     * @param string $limit
     * @param string $fields
     * @return PDOStatement
     */
    public static function getComments($where = null, $order = null, $limit = null, $fields = '*'){
        return (new Database('comments'))->select($where,$order,$limit,$fields);
    }
}