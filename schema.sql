create table User(
	userid varchar(15) not null unique,
	firstname varchar(15) not null,
	lastname varchar(15) not null,
	username not null unique,
	password not null,
	primary key(id)
);

create table Message(
	messageid varchar(15) not null,
	recipient_ids varchar(15) not null,
	user_id varchar(15) not null,
	subject text null,
	body text not null,
	date_sent datetime not null,
	primary key(messageid,user_id),
	foreign key(recipient_ids) references User(userid) on update cascade on delete cascade,
	foreign key(user_id) references User(userid) on update cascade on delete cascade
);

create table Message_read(
	readid varchar(15) not null,
	message_id varchar(15) not null,
	reader_id varchar(15) not null,
	date_read datetime not null,
	primary key(readid)
)