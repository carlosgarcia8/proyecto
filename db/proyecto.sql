drop table if exists usuarios cascade;
create table usuarios (
    id       bigserial    constraint pk_usuarios primary key,
    nick     varchar(100) not null constraint uq_usuarios_nick unique,
    password char(60)     not null,
    auth_key char(32)     not null,
    activo   bool         not null default true
);

drop table if exists posts cascade;
create table posts (
    id          bigserial    constraint pk_posts primary key,
    titulo      varchar(100) not null,
    votos       bigint       not null default 0,
    extension   varchar(20)  not null default 'jpg',
    usuario_id  bigint       constraint fk_posts_usuarios references usuarios(id)
        on delete set null on update cascade
);
