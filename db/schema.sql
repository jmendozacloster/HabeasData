create database firmadorlite;
use firmadorlite;

create table person(
 id int not null auto_increment primary key,
 name varchar(255),
 phone varchar(255),
 email varchar(255),
 firma varchar(255),
 created_at datetime
);