create table user
   (id varchar(15) not null unique,
	firstname varchar(50) not null,
	lastname varchar(50) not null,
	username varchar(50) not null unique,
	password char(64)not null,
	primary key(id));

create table message
   (id varchar(15) not null,
	recipient_ids varchar(15) not null,
	user_id varchar(15) not null,
	subject text,
	body text not null,
	date_sent datetime  not null,
	primary key(id,recipient_ids),
	foreign key(recipient_ids) references user(id) on update cascade on delete cascade,
	foreign key(user_id) references user(id) on update cascade on delete cascade);
	
create table message_read
   (id varchar(15) not null unique,
	message_id varchar(15) not null, 
	reader_id varchar(15) not null,
	read_date datetime not null,
	primary key(message_id,reader_id),
	foreign key(message_id) references message(id) on update cascade on delete cascade,
	foreign key(reader_id) references user(id) on update cascade on delete cascade);