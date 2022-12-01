<?php

namespace App\Model\Entity;

class Organization{

    /**
     * ID da organização
     * @var integer
     */
    public $id = 1;
    
    /**
     * Nome da organização
     * @var string
     */
    public $name = 'Impusic';

    /**
     * Site da organização
     * @var string
     */
    public $site = 'https://localhost/tcc/';

    /**
     * Descrição da organização
     * @var string
     */
    public $description = 'Impusic é uma plataforma digital de apoio ao crescimento de artistas e produtores de suas obras.';

}