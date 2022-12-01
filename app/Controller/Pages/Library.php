<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Channel as EntityChannel;
use \App\Model\Entity\Follows as EntityFollows;
use \App\Model\Entity\Videos as EntityVideos;
use \App\Model\Entity\Organization;
use \App\Session\Admin\Login as SessionAdminLogin;

class Library extends Page{
    public static function getLibrary($request){
        //Organização
        $obOrganization = new Organization;

        $login = SessionAdminLogin::getLogin();
        /* if($login == null){
            return false;
        } */
        $obUser = EntityChannel::getChannelByUser($login['user']);
        
        //VIEW DA HOME
        $content = View::render('pages/library',[

        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage(
            //NOME DE ARQUIVOS CSS,JS...
            'library',
            //TITLE DA PÁGINA
            'Biblioteca - '.$obOrganization->name,
            //DESCRIÇÃO DA PÁGINA
            'Bem-vindos ao RiftMaker.com - Análise as estatísticas de invocadores, melhores campeões, ranking competitivo, times de Clash, Profissionais e muito mais',
            //CONTEUDO DA PÁGINA
            $content
        );
    }
}