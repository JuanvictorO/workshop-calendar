create schema calendar default charset utf8;
use calendar;
create table event (
	id int auto_increment primary key,
    name varchar(100) not null,
    start datetime not null unique,
    end datetime not null unique
    /* Aqui vai entrar o ID_User no lugar de nome */
)engine = InnoDB;

create table nonOperatingDays (
	id int auto_increment primary key,
    start datetime not null unique
)engine = InnoDB;

select * from nonOperatingDays;
