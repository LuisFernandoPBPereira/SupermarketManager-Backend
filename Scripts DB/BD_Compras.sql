#Criação do banco de dados, denominamos compras

CREATE DATABASE compras;

#Habilitamos a utilização

USE compras;

#Estrutura da tabela usuários
CREATE TABLE usuarios(
	id_usuario int not null auto_increment primary key,
    usuario    varchar(15) not null,
    senha      varchar(32) not null,
    dtcria     datetime default now(),
    estatus    char(01) default ''
);

#Vamos inserir um usuário padrão do sistema
INSERT INTO usuarios (usuario, senha)
values ("admin", md5("admin123"));

ALTER TABLE usuarios ADD COLUMN nome varchar(30) default '' after id_usuario,
					 ADD COLUMN tipo varchar(20) default '' after senha;

#Ao final fazemos um select para verificar o registro inserido
SELECT * FROM usuarios WHERE senha = md5("admin123");

#mudando a estrutura da tabela de usuário
ALTER TABLE usuarios DROP COLUMN id_usuario;
ALTER TABLE usuarios MODIFY usuario varchar(15) not null primary key;

#Estrutura da tabela de unidade de medidas
CREATE TABLE unid_medida(
	cod_unidade integer auto_increment primary key,
    sigla varchar(03) default '',
	descricao varchar(30) default '',
    dtcria datetime default now(),
    usucria varchar(15) default '',
    estatus char(01) default '',
    
    constraint foreign key fk_unimed_prod (usucria) references usuarios(usuario)    
);

#Estrutura da tabela de produtos
CREATE TABLE produtos(
	cod_produto integer auto_increment primary key,
    descricao varchar(30) default '',
    unid_medida integer default 0,
    estoq_minimo integer default 0,
    estoq_maximo integer default 0,
    dtcria datetime default now(),
    usucria varchar(15) default '',
    estatus char(01) default '',
    
    constraint foreign key fk_prod_unidmed (unid_medida) references unid_medida(cod_unidade),
    constraint foreign key fk_prod_usuarios (usucria) references usuarios(usuario)
);
select * from usuarios;















