create table TiposDeUtilizadores(
	id int(1) not null auto_increment primary key,
	tipo varchar(13) not null
);

insert into TiposDeUtilizadores(tipo) values ('administrador');
insert into TiposDeUtilizadores(tipo) values ('professor');

create table Utilizadores(
	id int not null auto_increment primary key,
	nome varchar(50) not null,
	email varchar(50) not null,
	password varchar(50) not null,
	idTipoUtilizador int(1) not null,
	foreign key(idTipoUtilizador) references TiposDeUtilizadores(id)
);
