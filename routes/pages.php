<?php

use \App\Http\Response;
use \App\Controller\Pages;

//ROTA HOME
$obRouter->get('/',[
    function(){
        return new Response(200,Pages\Home::getHome());
    }
]);

//ROTA WATCH
$obRouter->get('/watch',[
    function($request){
        return new Response(200,Pages\Watch::getWatch(''));
    }
]);

//ROTA WATCH Dinamica
$obRouter->get('/watch/{codeVideo}',[
    function($codeVideo){
        return new Response(200,Pages\Watch::getWatch($codeVideo));
    }
]);

//ROTA DE CADASTRO DE UM NOVO USUARIO
$obRouter->get('/upload',[
    function($request){
        return new Response(200,Pages\Upload::getNewVideo($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO USUARIO (POST)
$obRouter->post('/upload',[
    function($request){
        return new Response(200,Pages\Upload::setNewVideo($request));
    }
]);

//ROTA DE CADASTRO DE UM NOVO USUARIO (POST)
$obRouter->post('/register',[
    function($request){
        return new Response(200,Pages\Channel::setNewChannel($request));
    }
]);

//ROTA DE LOGIN DE UM USUARIO (POST)
$obRouter->post('/login',[
    function($request){
        return new Response(200,Pages\Channel::setLogin($request));
    }
]);

//ROTA DE LOGOUT DE UM USUARIO (POST)
$obRouter->post('/logout',[
    function($request){
        return new Response(200,Pages\Channel::setLogout($request));
    }
]);
//ROTA DE LOGOUT DE UM USUARIO (GET)
$obRouter->get('/logout',[
    function($request){
        return new Response(200,Pages\Channel::setLogout($request));
    }
]);

//ROTA HOME
$obRouter->get('/profile/{user}',[
    function($user){
        return new Response(200,Pages\Profile::getProfile($user));
    }
]);

//ROTA DE ATUALIZAÇÃO DE UM NOVO USUARIO
$obRouter->get('/settings/profile',[
    function($request){
        return new Response(200,Pages\Profile::getSettings($request));
    }
]);

//ROTA DE ATUALIZAÇÃO DE UM NOVO USUARIO (POST)
$obRouter->post('/settings/profile',[
    function($request){
        return new Response(200,Pages\Profile::setSettings($request));
    }
]);

//ROTA DE ATUALIZAÇÃO DE UM NOVO USUARIO
$obRouter->get('/follow/{user}',[
    function($user,$request){
        return new Response(200,Pages\Profile::setFollow($user,$request));
    }
]);

//ROTA DE ATUALIZAÇÃO DE UM NOVO USUARIO
$obRouter->get('/unfollow/{user}',[
    function($user,$request){
        return new Response(200,Pages\Profile::setUnfollow($user,$request));
    }
]);

//ROTA DE ADICIONAR UM COMENTÁRIO EM UM VÍDEO
$obRouter->post('/comment',[
    function($request){
        return new Response(200,Pages\Watch::setComment($request));
    }
]);

//ROTA DE DELETAR UM COMENTÁRIO EM UM VÍDEO
$obRouter->get('/comment/delete/{videoId}/{commentId}',[
    function($videoId,$commentId,$request){
        return new Response(200,Pages\Watch::setDeleteComment($videoId,$commentId,$request));
    }
]);
//ROTA DE EDITAR UM COMENTÁRIO EM UM VÍDEO
$obRouter->post('/comment/edit/{videoId}/{commentId}',[
    function($videoId,$commentId,$request){
        return new Response(200,Pages\Watch::setEditComment($videoId,$commentId,$request));
    }
]);

//ROTA DE DELETAR UM COMENTÁRIO EM UM VÍDEO
$obRouter->get('/video/delete/{videoId}',[
    function($videoId,$request){
        return new Response(200,Pages\Watch::setDeleteVideo($videoId,$request));
    }
]);

//ROTA DE BIBLIOTECA DO USUÁRIO
$obRouter->get('/library',[
    function($request){
        return new Response(200,Pages\Library::getLibrary($request));
    }
]);

//ROTA DE ATUALIZAÇÃO DE UM NOVO USUARIO (POST)
/* $obRouter->post('/searchMusic',[
    function($request){
        return new Response(200,Pages\Profile::searchMusic($request));
    }
]); */


//ROTA Teste Show
/* $obRouter->get('/show',[
    function(){
        return new Response(200,Pages\Show::getShow());
    }
]); */

/* //ROTA DISCORD
$obRouter->get('/discord',[
    'middlewares' => [
        'cache'
    ],
    function(){
        return new Response(200,Pages\Socials::Discord());
    }
]);

//ROTA ERRO
$obRouter->get('/error',[
    'middlewares' => [
        'cache'
    ],
    function($request){
        return new Response(200,Pages\Error::getError($request));
    }
]); */





//ROTA DEPOIMENTOS
/* $obRouter->get('/depoimentos',[
    'middlewares' => [
        'cache'
    ],
    function($request){
        return new Response(200,Pages\Testimony::getTestimonies($request));
    }
]); */

//ROTA DEPOIMENTOS (INSERT)
/* $obRouter->post('/depoimentos',[
    function($request){
        return new Response(200,Pages\Testimony::insertTestimony($request));
    }
]); */








//ROTA DINÂMICA
/* $obRouter->get('/pagina/{idPagina}/{acao}',[
    function($idPagina,$acao){
        return new Response(200,'Página '.$idPagina.' = '.$acao);
    }
]); */