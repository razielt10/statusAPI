create database statusapi;
use statusapi;
create table menssajes(
   id bigint not null auto_increment,
   email varchar(300) not null,
   status varchar(120) not null,
   created_at datetime,
   code varchar(200),
   activated integer not null default 1,
   deleting integer not null default 0,
   CONSTRAINT statusapi_pkey_id PRIMARY KEY (id)
);
