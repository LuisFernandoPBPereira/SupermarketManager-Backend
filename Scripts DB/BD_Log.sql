#Estrutura da tabela de log

CREATE TABLE log(
	id_log int auto_increment primary key,
    usuario varchar(15) not null,
    comando varchar(500) default '',
	dtcria timestamp default current_timestamp
);