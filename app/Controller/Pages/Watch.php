<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Model\Entity\Organization;
use \App\Model\Entity\Videos as EntityVideos;
use \App\Session\Admin\Login as SessionAdminLogin;
use \App\Model\Entity\Channel as EntityChannel;
use \App\Model\Entity\Comments as EntityComments;

class Watch extends Page{
    public static function setComment($request){
        $login = SessionAdminLogin::getLogin();

        if(isset($_POST['text']) && $_POST['text'] !== null && $_POST['text'] !== '' && !empty($_POST['text']) && $login !== null){
            $obUser = EntityChannel::getChannelByUser($login['user']);
            $text = $_POST['text'];
            $videoId = $_POST['videoId'];
            $name = $obUser->name;
            $user = $obUser->user;

            $date = date_create();
            $timestamp = date_timestamp_get($date);

            $obComments = new EntityComments();
            $obComments->videoId = $videoId;
            $obComments->text = $text;
            $obComments->time = $timestamp;
            $obComments->commentName = $name;
            $obComments->commentUsername = $user;
            $obComments->cadastrar();
        }else{
            $request->getRouter()->redirect('/watch/'.$_POST['videoId'].'?status=loginneeded');
        }
        $request->getRouter()->redirect('/watch/'.$videoId);
    }

    public static function setDeleteVideo($videoId,$request){
        $login = SessionAdminLogin::getLogin();
        $obUser = EntityChannel::getChannelByUser($login['user']);

        if($obUser == null or empty($obUser)){
            $request->getRouter()->redirect('?status=videonotfound');
        }else if($login == null){
            $request->getRouter()->redirect('/profile/'.$obUser->user.'/?status=loginneeded');
        }

        $obVideos = new EntityVideos;
        $obVideo = $obVideos->getVideoByLink($videoId);

        if($obUser->user == $login['user'] && $obVideo->channelUser == $obUser->user){
            $obVideos->excluir($obVideo->id);
        }else{
            $request->getRouter()->redirect('/profile/'.$obUser->user.'/?status=validationerror');
        }

        $request->getRouter()->redirect('/profile/'.$obUser->user);
    }

    public static function setDeleteComment($videoId,$commentId,$request){
        $login = SessionAdminLogin::getLogin();

        if($login !== null){
            $obUser = EntityChannel::getChannelByUser($login['user']);
            $obComments = EntityComments::getCommentById($commentId);
            if($obComments->id == $commentId && $obComments->videoId == $videoId && $obComments->commentUsername === $obUser->user){
                $obComments->excluir($obComments->id);
                $request->getRouter()->redirect('/watch/'.$obComments->videoId.'/?status=deleted');
            }else{
                $request->getRouter()->redirect('/?status=validationerror');
            }
        }else{
            $request->getRouter()->redirect('/?status=loginneeded');
        }
    }

    public static function setEditComment($videoId,$commentId,$request){
        $login = SessionAdminLogin::getLogin();

        if($login !== null){
            $obUser = EntityChannel::getChannelByUser($login['user']);
            $obComments = EntityComments::getCommentById($commentId);
            if($obComments->id == $commentId && $obComments->videoId == $videoId && $obComments->commentUsername === $obUser->user && $_POST['text'] !== null && $_POST['text'] !== '' && !empty($_POST['text'])){
                $date = date_create();
                $timestamp = date_timestamp_get($date);

                $text = $request->getPostVars()['text'];

                $obCommentEdited = new EntityComments();
                $obCommentEdited->id = $obComments->id;
                $obCommentEdited->videoId = $obComments->videoId;
                $obCommentEdited->text = $text;
                $obCommentEdited->commentName = $obComments->commentName;
                $obCommentEdited->commentUsername = $obComments->commentUsername;
                $obCommentEdited->time = $timestamp;
                $obCommentEdited->atualizar();
                $request->getRouter()->redirect('/watch/'.$obComments->videoId.'/?status=edited');
            }else{
                $request->getRouter()->redirect('/?status=validationerror');
            }
        }else{
            $request->getRouter()->redirect('/?status=loginneeded');
        }
    }

    public static function getCommentBox($videoId,$channelUser){
        $itens = '';
        $commentNumber = 0;

        $comments = EntityComments::getComments('videoId = "'.trim($videoId).'"');
        while($commentBox = $comments->fetchObject(EntityComments::class)){
            $date = date('d/m/y', $commentBox->time);
            $id = EntityChannel::getChannelByUser($commentBox->commentUsername)->id;
            $commentNumber++;

            //VERIFICA SE ESTÁ LOGADO, SE SIM, VERIFICA SE É O USUÁRIO QUE COMENTOU
            $login = SessionAdminLogin::getLogin();
            if($login !== null){
                $obUser = EntityChannel::getChannelByUser($login['user']);
                if($obUser->user === $commentBox->commentUsername){
                    $optionsComment = View::render('pages/watch/optionsOwnerComment', [
                        'videoId' => trim($videoId),
                        'commentId' => $commentBox->id,
                        'commentNumber' => $commentNumber
                    ]);
                }else{
                    $optionsComment = View::render('pages/watch/optionsViewerComment');
                }
            }else{
                $optionsComment = View::render('pages/watch/optionsViewerComment');
            }

            if($commentBox->edited == true){
                $posted = "editado";
            }else{
                $posted = "postado";
            }

            //VERIFICA O NOME DO CANAL COM O NOME DO COMENTÁRIO
            if($channelUser == $commentBox->commentUsername){
                $commentName = $commentBox->commentName.'  <i class="fa-solid fa-video"></i>';
            }else{
                $commentName = $commentBox->commentName;
            }

            $itens .= View::render('pages/watch/commentBox',[
                'commentName' => $commentName,
                'commentUser' => $commentBox->commentUsername,
                'posted' => $posted,
                'text' => $commentBox->text,
                'date' => $date,
                'id' => $id,
                'commentNumber' => $commentNumber,
                'videoId' => trim($videoId),
                'commentId' => $commentBox->id,
                'optionsComment' => $optionsComment
            ]);
        }

        return $itens;
    }

    /**
     * Método responsável por retornar o conteúdo (view) da nossa Home
     * @return string
     */
    public static function getWatch($codeVideo){
        //Organização
        $obOrganization = new Organization;

        if(isset($codeVideo) && $codeVideo != '' && $codeVideo != null){
            $obUser = EntityVideos::getVideoByLink($codeVideo);
            $title = $obUser->title;
            $description = $obUser->description;
            $channel = $obUser->channel;
            $channelUser = $obUser->channelUser;
            $thumbnail = $obUser->thumbnail;
            $video = $obUser->video;
            $date = $obUser->date;
            $musicId = $obUser->musicId;

            $obUserChannel = EntityChannel::getChannelByUser($channelUser);
            $channelId = $obUserChannel->id;

            $countComments = 0;
            $comments = EntityComments::getComments('videoId = "'.trim($video).'"');
            while($comments->fetchObject(EntityComments::class)){
                $countComments++;
            }
        }

        $login = SessionAdminLogin::getLogin();
        if($login !== null){
            $obUser = EntityChannel::getChannelByUser($login['user']);
            $user_id = $obUser->id;
        }else{
            $user_id = '';
        }

        $description = str_replace("\n", " <br>", $description);
        $pattern = '#((https?|ftp)://(\S*?\.\S*?))([\s)\[\]{},;"\':<]|\.\s|$)#i';
        $description = preg_replace($pattern, "<a href='$0' class='videoDescriptionLink' target='_blank'>$0</a>", $description);

        //VIEW DA HOME
        $content = View::render('pages/watch',[
            'title' => $title,
            'channel' => $channel,
            'channelUser' => $channelUser,
            'channelId' => $channelId,
            'description' => nl2br($description),
            'video' => $video,
            'thumbnail' => $thumbnail,
            'musicId' => $musicId,
            'commentBox' => self::getCommentBox($video,$channelUser),
            'countComments' => $countComments,
            'id' => $user_id,
        ]);

        //Título do vídeo com ellipsis
        $pageTitle = strlen($title) > 30 ? substr($title,0,30)."..." : $title;

        //RETORNA A VIEW DA PÁGINA
        return parent::getPage(
            //NOME DE ARQUIVOS CSS,JS...
            'watch',
            //TITLE DA PÁGINA
            $pageTitle.' - '.$obOrganization->name,
            //DESCRIÇÃO DA PÁGINA
            $obOrganization->description,
            //CONTEUDO DA PÁGINA
            $content
        );
    }

}