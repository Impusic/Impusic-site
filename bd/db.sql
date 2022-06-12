create database impusic; /* Criação do Banco de Dados Impusic*/
use impusic; /* Seleciona o Banco de Dados Impusic*/

create table videos(
    id int not null auto_increment primary key, /* ID único do vídeo */
    title varchar(255) not null, /* Título do vídeo */
    description varchar(255) not null, /* Descrição do vídeo */
    channel varchar(255) not null, /* Nome da conta que publicou o vídeo */
    channeUser varchar(255) not null, /* Usuário único da conta que publicou o vídeo */
    thumbnail varchar(255) not null, /* ID de link personalizado para a thumbnail */
    video varchar(255) not null, /* ID de link personalizado do vídeo */
    date varchar(255) not null /* Data em que o vídeo foi publicado */
);

create table channel(
    id int not null auto_increment primary key, /* ID único da conta */
    user varchar(255) not null, /* Usuário único da conta */
    name varchar(255) not null, /* Nome de exibição do perfil */
    description varchar(255), /* Descrição da conta */
    email varchar(255) not null, /* Email do perfil */
    password varchar(255) not null, /* Senha Criptografada do perfil */
    date varchar(255) not null, /* Data em que a conta foi criada */
    location varchar(255), /* Localização personalizada do usuário */
    spotify varchar(255), /* Link para o Spotify do perfil */
    soundcloud varchar(255), /* Link para o Soundcloud do perfil */
    facebook varchar(255), /* Link para o Facebook do perfil */
    instagram varchar(255), /* Link para o Instagram do perfil */
    discord varchar(255) /* Link para o Discord do perfil */
);

create table follows(
    idFollow int not null primary key, /* ID da conta que está sendo seguido */
    idChannel int not null, /* ID da conta que está seguindo */
    date varchar(255) not null /* Data que a ação de Follow foi executado */
);