mysql -u junejune0306 -p2022Wnsldj! junejune0306 -e "
create table userlist(
	seq int auto_increment primary key, 
	nick varchar(20) not null, 
	id varchar(20) not null, 
	pswd varchar(20) not null, 
	email varchar(500) not null, 
	signupDate datetime default current_timestamp
);
create table post(
	seq int auto_increment primary key, 
	type int not null, 
	nick varchar(20) not null, 
	id varchar(20) not null, 
	dt datetime default current_timestamp, 
	updt datetime, 
	title varchar(255), 
	content text, 
	parent int
);
create table views(
	seq int not null, 
	session char(26) not null, 
	dt datetime default current_timestamp
);
create table likes(
	seq int not null, 
	id varchar(20) not null, 
	status tinyint not null, 
	dt datetime default current_timestamp
);
"