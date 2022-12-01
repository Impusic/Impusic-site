<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Channel as EntityChannel;
use \App\Model\Entity\Follows as EntityFollows;
use \App\Model\Entity\Videos as EntityVideos;
use \App\Model\Entity\Organization;
use \App\Session\Admin\Login as SessionAdminLogin;

class Profile extends Page{
    /**
     * Método responsável por retornar o valor relativo de um Unix Timestamp
     * @param string $ts
     * @return string
     */
    public static function time2str($ts) {
        if(!ctype_digit($ts))
            $ts = strtotime($ts);

            $diff = time() - $ts;
            if($diff == 0)
                return 'now';
            elseif($diff > 0){
                $day_diff = floor($diff / 86400);
                    if($day_diff == 0){
                        if($diff < 60) return 'just now';
                        if($diff < 120) return '1 minute ago';
                        if($diff < 3600) return floor($diff / 60) . ' minutes ago';
                        if($diff < 7200) return '1 hour ago';
                        if($diff < 86400) return floor($diff / 3600) . ' hours ago';
                    }
                    if($day_diff == 1) return 'Yesterday';
                    if($day_diff < 7) return $day_diff . ' days ago';
                    if($day_diff < 31) return ceil($day_diff / 7) . ' weeks ago';
                    if($day_diff < 60) return 'last month';

                    return date('F Y', $ts);
            }
        else{
            $diff = abs($diff);
            $day_diff = floor($diff / 86400);
            if($day_diff == 0){
                if($diff < 120) return 'in a minute';
                if($diff < 3600) return 'in ' . floor($diff / 60) . ' minutes';
                if($diff < 7200) return 'in an hour';
                if($diff < 86400) return 'in ' . floor($diff / 3600) . ' hours';
            }
            if($day_diff == 1) return 'Tomorrow';
            if($day_diff < 4) return date('l', $ts);
            if($day_diff < 7 + (7 - date('w'))) return 'next week';
            if(ceil($day_diff / 7) < 4) return 'in ' . ceil($day_diff / 7) . ' weeks';
            if(date('n', $ts) == date('n') + 1) return 'next month';

            return date('F Y', $ts);
        }
    }

    /**
     * Método resposável por retornar a caixa de vídeo postado pelo usuário
     * @param string $obUser
     * @return mixed
     */
    private static function getVideoCard($obUser){
        $itens = '';
        $videoCard = '';

        $results = EntityVideos::getVideos('channelId = '.$obUser->id,'id DESC');
        $idNumber = 0;

        

        $login = SessionAdminLogin::getLogin();
        while($obVideo = $results->fetchObject(EntityVideos::class)){
            $idNumber++;

            //VERIFICA SE ESTÁ LOGADO, SE SIM, VERIFICA SE É O USUÁRIO QUE COMENTOU
            if($login !== null){
                $obUser = EntityChannel::getChannelByUser($login['user']);
                if($obUser->user === $obVideo->channelUser){
                    $optionsVideo = View::render('pages/watch/optionsOwnerVideo', [
                        'idNumber' => $idNumber,
                        'video' => $obVideo->video
                    ]);
                }else{
                    $optionsVideo = View::render('pages/watch/optionsViewerVideo', [
                        'idNumber' => $idNumber,
                    ]);
                }
            }else{
                $optionsVideo = View::render('pages/watch/optionsViewerVideo', [
                    'idNumber' => $idNumber,
                ]);
            }

            $time = self::time2str($obVideo->date);
            $itens .= View::render('pages/profile/videoCard',[
                'idNumber' => $idNumber,
                'videoTitle' => $obVideo->title,
                'channel' => $obVideo->channel,
                'channelUser' => $obVideo->channelUser,
                'thumbnail' => $obVideo->thumbnail,
                'optionsVideo' => $optionsVideo,
                'time' => $time,
                'link' => trim($obVideo->video)
            ]);
        } 

        return $itens;
    }

    public static function setFollow($user,$request){
        $login = SessionAdminLogin::getLogin();
        $obUser = EntityChannel::getChannelByUser($user);
        $obUserLogged = EntityChannel::getChannelByUser($login['user']);

        if($obUser == null or empty($obUser)){
            $request->getRouter()->redirect('?status=usernotfound');
        }else if($login == null){
            $request->getRouter()->redirect('/profile/'.$obUser->user.'/?status=loginneeded');
        }else if($obUserLogged == null or empty($obUserLogged)){
            $request->getRouter()->redirect('/profile/'.$obUser->user);
        }

        $obFollows = new EntityFollows;
        $obFollows->idFollow = $obUserLogged->id;
        $obFollows->idUser = $obUser->id;
        $obFollows->cadastrar();

        $request->getRouter()->redirect('/profile/'.$obUser->user);
    }

    public static function setUnfollow($user,$request){
        $login = SessionAdminLogin::getLogin();
        $obUser = EntityChannel::getChannelByUser($user);
        $obUserLogged = EntityChannel::getChannelByUser($login['user']);

        if($obUser == null or empty($obUser)){
            $request->getRouter()->redirect('');
        }else if($obUserLogged == null or empty($obUserLogged)){
            $request->getRouter()->redirect('/profile/'.$obUser->user);
        }

        $obFollows = new EntityFollows;
        $idUser = EntityFollows::getFollows('idUser = '.$obUser->id);
        while($obFollows = $idUser->fetchObject(EntityFollows::class)){
            if($obUserLogged->id == $obFollows->idFollow){
                $obFollows->excluir($obFollows->id);
            }
        }

        $request->getRouter()->redirect('/profile/'.$obUser->user);
    }

    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     * @return string
     */
    public static function getProfile($user){
        //Organização
        $obOrganization = new Organization;

        $login = SessionAdminLogin::getLogin();
        $obUser = EntityChannel::getChannelByUser($user);
        if($login !== null){
            $obUserLogged = EntityChannel::getChannelByUser($login['user']);
            $obUserLoggedId = $obUserLogged->id;
        }else{
            $login['user'] = 0;
            $obUserLoggedId = 0;
        }

        // ADICIONA ELLIPSIS SE A DESCRIÇÃO FOR MAIOR QUE 35C
        /* $minDesc = $obUser->description;
        $pageTitle = strlen($minDesc) > 35 ? substr($minDesc,0,35)."..." : $minDesc; */

        // ADICIONA VEJA MAIS SE A DESCRIÇÃO FOR MAIOR QUE 35C
        /* if($obUser->description !== null && strlen($obUser->description) > 35){
            $seeMore = 'Veja Mais.';
        }else{
            $seeMore = '';
        } */


        $sizeDescription = 50;
        if(strlen($obUser->description) > $sizeDescription){
            $accountDescription = substr($obUser->description, 0 , $sizeDescription-3).'...';
            $seeMore = 'Veja Mais.';
        }else{
            $accountDescription = $obUser->description;
            $seeMore = '';
        }

        $user_id = ($obUser->id == null ? 'default' : $obUser->id);

        $spotify_link = ($obUser->spotify == null ? '' : $obUser->spotify);
        $spotify_svg = ($obUser->spotify == null ? '' : parent::getSvg('spotify',null,null,null,'white'));
        
        $soundcloud_link = ($obUser->soundcloud == null ? '' : $obUser->soundcloud);
        $soundcloud_svg = ($obUser->soundcloud == null ? '' : parent::getSvg('soundcloud',null,null,null,'white'));
        
        $instagram_link = ($obUser->instagram == null ? '' : $obUser->instagram);
        $instagram_svg = ($obUser->instagram == null ? '' : parent::getSvg('instagram',null,null,null,'white'));
        
        $facebook_link = ($obUser->facebook == null ? '' : $obUser->facebook);
        $facebook_svg = ($obUser->facebook == null ? '' : parent::getSvg('facebook',null,null,null,'white'));
        
        $discord_link = ($obUser->discord == null ? '' : $obUser->discord);
        $discord_svg = ($obUser->discord == null ? '' : parent::getSvg('discord',null,null,null,'white'));

        $idFollows = EntityFollows::getFollows('idFollow = '.$obUser->id);
        $follows = 0;
        while($obFollows = $idFollows->fetchObject(EntityFollows::class)){
            $follows++;
        }

        $idUser = EntityFollows::getFollows('idUser = '.$obUser->id);
        $following = 0;
        while($obFollows = $idUser->fetchObject(EntityFollows::class)){
            $following++;
        }

        $videos = EntityVideos::getVideos('channelId = '.$obUser->id);
        $videosCount = 0;
        while($obVideos = $videos->fetchObject(EntityChannel::class)){
            $videosCount++;
        }

        // VERIFICA SE O SEU USUÁRIO SEGUE O PERFIL QUE ESTÁ SENDO EXIBIDO
        $idUser = EntityFollows::getFollows('idUser = '.$obUser->id);
        $follow = false;
        while($obFollows = $idUser->fetchObject(EntityFollows::class)){
            if($obUserLoggedId == $obFollows->idFollow){
                $follow = true;
            }else{
                if($follow !== true){
                    $follow = false;
                }
            }
        }

        // VERIFICA QUAL É O PAPER BUTTON QUE DEVE SER EXIBIDO
        if($obUser->user == $login['user']){
            //PAPER BUTTON DE EDITAR PERFIL
            $paperButton = View::render('pages/profile/editContainer', [
            ]);
        }else if($obUser->user !== $login['user'] && $follow == true){
            //PAPER BUTTON DE SEGUINDO (COM OPÇÃO PARA DEIXAR DE SEGUIR)
            $paperButton = View::render('pages/profile/followingContainer', [
                'user' => $obUser->user
            ]);
        }else{
            //PAPER BUTTON DE SEGUIR (COM OPÇÃO PARA SEGUIR)
            $paperButton = View::render('pages/profile/followContainer', [
                'user' => $obUser->user
            ]);
        }
        
        //VIEW DA HOME
        $content = View::render('pages/profile',[
            'name' => $obUser->name,
            'id' => $user_id,
            'accountDescription' => nl2br($accountDescription),
            'allAccountDescription' => nl2br($obUser->description),
            'seeMore' => $seeMore,
            'paperButton' => $paperButton,
            'follows' => $follows,
            'following' => $following,
            'videosCount' => $videosCount,
            'spotify_link' => $spotify_link,
            'spotify_svg' => $spotify_svg,
            'soundcloud_link' => $soundcloud_link,
            'soundcloud_svg' => $soundcloud_svg,
            'instagram_link' => $instagram_link,
            'instagram_svg' => $instagram_svg,
            'facebook_link' => $facebook_link,
            'facebook_svg' => $facebook_svg,
            'discord_link' => $discord_link,
            'discord_svg' => $discord_svg,
            'videoCard' => self::getVideoCard($obUser)
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage(
            //NOME DE ARQUIVOS CSS,JS...
            'profile',
            //TITLE DA PÁGINA
            $obUser->name.' - '.$obOrganization->name,
            //DESCRIÇÃO DA PÁGINA
            $obOrganization->description,
            //CONTEUDO DA PÁGINA
            $content
        );
    }

    public static function getSettings($request){
        //Organização
        $obOrganization = new Organization;

        $login = SessionAdminLogin::getLogin();
        $obUser = EntityChannel::getChannelByUser($login['user']);

        $user_name = ($obUser->name == null ? '' : $obUser->name);
        $user_id = ($obUser->id == null ? 'default' : $obUser->id);
        $user_description = ($obUser->description == null ? '' : $obUser->description);
        $user_location = ($obUser->location == null ? '' : $obUser->location);
        $user_spotify = ($obUser->spotify == null ? '' : $obUser->spotify);
        $user_soundcloud = ($obUser->soundcloud == null ? '' : $obUser->soundcloud);
        $user_instagram = ($obUser->instagram == null ? '' : $obUser->instagram);
        $user_facebook = ($obUser->facebook == null ? '' : $obUser->facebook);
        $user_discord = ($obUser->discord == null ? '' : $obUser->discord);
        
        //VIEW DA HOME
        $content = View::render('pages/profile/settings',[
            'svg-camera' => parent::getSvg('camera',45,null,0.5,null),
            'svg-spotify' => parent::getSvg('spotify',null,null,null,'gray'),
            'svg-discord' => parent::getSvg('discord',null,null,null,'gray'),
            'svg-instagram' => parent::getSvg('instagram',null,null,null,'gray'),
            'svg-facebook' => parent::getSvg('facebook',null,null,null,'gray'),
            'svg-soundcloud' => parent::getSvg('soundcloud',null,null,null,'gray'),
            'name' => $user_name,
            'id' => $user_id,
            'description' => $user_description,
            'location' => $user_location,
            'spotify' => $user_spotify,
            'soundcloud' => $user_soundcloud,
            'instagram' => $user_instagram,
            'facebook' => $user_facebook,
            'discord' => $user_discord,
        ]);

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage(
            //NOME DE ARQUIVOS CSS,JS...
            'settings',
            //TITLE DA PÁGINA
            'Editar usuário - '.$obOrganization->name,
            //DESCRIÇÃO DA PÁGINA
            'Bem-vindos ao RiftMaker.com - Análise as estatísticas de invocadores, melhores campeões, ranking competitivo, times de Clash, Profissionais e muito mais',
            //CONTEUDO DA PÁGINA
            $content
        );
    }

    public static function setSettings($request){
        //Organização
        $obOrganization = new Organization;

        $login = SessionAdminLogin::getLogin();
        $obUser = EntityChannel::getChannelByUser($login['user']);

        $postVars = $request->getPostVars();

        if(isset($_FILES['inputIcon']) && $_FILES['inputIcon']['name'] !== '' or $_FILES['inputIcon'] !== '' or $_FILES['inputIcon'] !== null){
            $newname = 'profile_'.trim($obUser->id).'.png';
            $target = 'resources/icons/'.$newname;
            move_uploaded_file($_FILES['inputIcon']['tmp_name'],$target);
        }

        if(isset($_FILES['inputBanner']) && $_FILES['inputBanner']['name'] !== '' or $_FILES['inputBanner'] !== '' or $_FILES['inputBanner'] !== null){
            $newname = 'profile_'.trim($obUser->id).'.png';
            $target = 'resources/banners/'.$newname;
            move_uploaded_file($_FILES['inputBanner']['tmp_name'],$target);
        }

        $name = ($postVars['name'] == null ? $obUser->name : $postVars['name']);
        $description = ($postVars['description'] == null ? $obUser->description : $postVars['description']);
        $location = ($postVars['location'] == null ? $obUser->location : $postVars['location']);
        $spotify = ($postVars['spotify'] == null ? $obUser->spotify : $postVars['spotify']);
        $soundcloud = ($postVars['soundcloud'] == null ? $obUser->soundcloud : $postVars['soundcloud']);
        $instagram = ($postVars['instagram'] == null ? $obUser->instagram : $postVars['instagram']);
        $facebook = ($postVars['facebook'] == null ? $obUser->facebook : $postVars['facebook']);
        $discord = ($postVars['discord'] == null ? $obUser->discord : $postVars['discord']);

        $obUser->name = $name;
        $obUser->description = $description;
        $obUser->location = $location;
        $obUser->spotify = $spotify;
        $obUser->soundcloud = $soundcloud;
        $obUser->instagram = $instagram;
        $obUser->facebook = $facebook;
        $obUser->discord = $discord;
        $obUser->atualizar();

        $request->getRouter()->redirect('/profile/'.$login['user']);
    }
}