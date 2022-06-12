<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Videos as EntityVideos;
use \App\Model\Entity\Organization;
use \App\Session\Admin\Login as SessionAdminLogin;
use \App\Model\Entity\Channel as EntityChannel;

/* use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception; */

class Upload extends Page{
    /**
     * Método responsável por retornar o formulário de cadastro de um novo usuario
     * @param Request $resquest
     * @return string
     */
    public static function getNewVideo($request){
        //CONTEÚDO DO FORMULÁRIO
        $content = View::render('pages/upload',[
        ]);

        //RETORNA A PÁGINA COMPLETA
        return parent::getPanel('upload','Publicar vídeo',$content);
    }

    public static function saveUpload($type,$keyName){
        // save video
        switch ($type) {
            case 'video':
                $video = $_FILES['video'];
                move_uploaded_file($video['tmp_name'],__FILE__.'resources/videos/'.$keyName);
            }
        return true;
    }

    
    /**
     * Método responsável por cadastrar um usuário no banco
     * @param Request $resquest
     * @return string
     */
    public static function setNewVideo($request){
        //ID ÚNICO DE THUMBNAIL E VÍDEO
        $m = microtime(true);
        $keyName = sprintf("%8x%05x\n",floor($m),($m-floor($m))*1000000);

        //SALVA O VÍDEO
        $newname = trim($keyName).'.mp4';                             //NOME DO ARQUIVO COM EXTENSÃO
        $target = 'resources/videos/'.$newname;                         //CAMINHO DO ARQUIVO
        move_uploaded_file($_FILES['video']['tmp_name'],$target);       //MOVE O ARQUIVO PARA O CAMINHO DESTINO

        //SALVA A THUMBNAIL
        $newname = trim($keyName).'.png';                             //NOME DO ARQUIVO COM EXTENSÃO
        $target = 'resources/thumbnail/'.$newname;                      //CAMINHO DO ARQUIVO
        move_uploaded_file($_FILES['thumbnail']['tmp_name'],$target);   //MOVE O ARQUIVO PARA O CAMINHO DESTINO

        //RESGATA O TIMESTAMP DO ENVIO DO VÍDEO
        $date = date_create();
        $timestamp = date_timestamp_get($date);

        //POSTVARS
        $postVars = $request->getPostVars();

        $login = SessionAdminLogin::getLogin();
        $obUser = EntityChannel::getChannelByUser($login['user']);

        $title = $postVars['title'] ?? '';
        $description = $postVars['description'] ?? '';
        $channel = $obUser->name;
        $channelUser = $obUser->user;
        $channelId = $obUser->id;
        $thumbnail = $keyName ?? '';
        $video = $keyName ?? '';

        //NOVA INSTANCIA DE USUÁRIO
        $obVideo = new EntityVideos;
        $obVideo->title = $title;
        $obVideo->description = $description;
        $obVideo->channel = $channel;
        $obVideo->channelUser = $channelUser;
        $obVideo->channelId = $channelId;
        $obVideo->thumbnail = $thumbnail;
        $obVideo->video = $video;
        $obVideo->date = $timestamp;
        $obVideo->cadastrar();

        //REDIRECIONA O USUÁRIO
        $request->getRouter()->redirect('/tcc/upload');
    }
}