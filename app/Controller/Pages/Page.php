<?php

namespace App\Controller\Pages;

use \App\Utils\View;
use \App\Controller\Pages;
use \App\Session\Admin\Login as SessionAdminLogin;
use \App\Model\Entity\Channel as EntityChannel;

class Page{

    /**
     * Método resposável por renderizar o código do svg alterando suas características
     * @param string $name
     * @param string $size
     * @param string $color
     * @param string $stroke
     * @param string $fill
     * @return string
     */
    public static function getSvg($name,$size,$color,$stroke,$fill){
        $itens = '';

        $size = ($size == null ? '24' : $size);
        $color = ($color == null ? 'white' : $color);
        $stroke = ($stroke == null ? '1' : $stroke);
        $fill = ($fill == null ? 'none' : $fill);

        $itens = View::getSvg($name, [
            'size' => $size,
            'color' => $color,
            'stroke' => $stroke,
            'fill' => $fill
        ]);

        return $itens;
    }

    /**
     * Método responsável por renderizar o topo da página
     * @return string
     */
    private static function getHeader(){
        return View::render('pages/header');
    }

    /**
     * Método responsável por renderizar o rodapé da página
     * @return string
     */
    private static function getFooter(){
        return View::render('pages/footer');
    }

    /**
     * Método responsável por renderizar o formulário de login de conta
     * @return string
     */
    private static function getLogin(){
        return View::render('pages/login');
    }

    /**
     * Método responsável por renderizar o formulário de registro de conta
     * @return string
     */
    private static function getRegister(){
        return View::render('pages/register');
    }

    public static function getSignButtons(){
        $itens = '';

        $login = SessionAdminLogin::getLogin();

        if($login !== null){
            $obUser = EntityChannel::getChannelByUser($login['user']);
            $id = $obUser->id;
        }else{
            $id = '';
        }

        if(!SessionAdminLogin::isLogged()){
            $itens = View::render('pages/home/signButtons', [
            ]);
        }else{
            $login = SessionAdminLogin::getLogin();
            $itens = View::render('pages/home/profileButton', [
                'user' => $login['user'],
                'id' => $id
            ]);
        }

        return $itens;
    }

    /**
     * Método responsável por retornar o toast com o status personalizado da página
     * @return string
     */
    public static function getStatus($status){
        $itens = '';

        if($status != '' or $status != null){
            $itens = View::render('pages/home/status', [
                'message' => $status
            ]);
        }

        return $itens;
    }

    /**
     * Método responsável por retornar o conteúdo (view) da nossa página genérica
     * @return string
     */
    public static function getPage($css,$title,$description,$content){

        $status = '';
        if(isset($_GET['status'])){
            switch($_GET['status']){
                case 'loginfailed':
                    $status = 'Email ou Senha incorreta(s)!';
                    break;
                case 'emailfailed':
                    $status = 'Email inválido!';
                    break;
                case 'emailused':
                    $status = 'Email já sendo utilizado!';
                    break;
                case 'loginneeded':
                    $status = 'Por favor, faça o login!';
                    break;
                case 'usernotfound':
                    $status = 'Usuário não encontrado!';
                    break;
                case 'videonotfound':
                    $status = 'Vídeo não encontrado!';
                    break;
                case 'validationerror':
                    $status = 'Erro de validação!';
                    break;
                case 'deleted':
                    $status = 'Deletado com sucesso!';
                    break;
                case 'edited':
                    $status = 'Editado com sucesso!';
                    break;
            }
        }

        return View::render('pages/page',[
            'css' => $css,
            'title' => $title,
            'description' => $description,
            'header' => self::getHeader(),
            'profileSettings' => self::getSignButtons(),
            'login' => self::getLogin(),
            'register' => self::getRegister(),
            'status' => self::getStatus($status),
            'content' => $content,
            'footer' => self::getFooter()
        ]);
    }

    /**
     * Método responsável por retornar o conteúdo (view) da nossa página de erro
     * @return string
     */
    public static function getErrorPage($css,$title,$description,$content){
        return View::render('pages/page',[
            'css' => $css,
            'title' => $title,
            'description' => $description,
            'header' => '',
            'content' => $content,
            'footer' => ''
        ]);
    }

    /**
     * Método responsável por renderizar a view do painel com conteúdos dinâmicos
     * @param string $title
     * @param string $content
     * @param string $currentModule
     * @return string
     */
    public static function getPanel($css,$title,$content){
        //RETORNA A PÁGINA RENDERIZADA
        return self::getPage($css,$title,'',$content);
    }
}