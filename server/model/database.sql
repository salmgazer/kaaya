create table if not exists user(
  user_id varchar(30) not null,
  fullname varchar(30) not null,
  email varchar(30) not null,
  username varchar(30) not null,
  phone varchar(20) not null,
  photo varchar(100) not null,
  type enum('ordinary', 'artisan'),
  primary key(user_id, phone, email)
);

create table if not exists artisan(
  artisan_id varchar(30) not null,
  location_longitude varchar(15) not null,
  location_latitude varchar(15) not null,
  primary key(artisan_id)
);

create table if not exists skill(
  skill_id int unsigned not null auto_increment,
  focus_id int not null,
  skill_name varchar(50) not null,
  primary key(skill_id)
);

create table if not exists artisan_has_skill(
  has_skill_id int unsigned not null auto_increment,
  skill_id int not null,
  user_id int not null,
  primary key(has_skill_id)
);

create table if not exists focus(
  focus_id int unsigned not null auto_increment,
  focus_name varchar(70) not null,
  primary key(focus_id, focus_name)
);

create table if not exists job(
  job_id int unsigned not null auto_increment,
  assigner_id varchar(30) not null,
  artisan_id varchar(30) not null,
  starting_price double not null,
  summary varchar(60) not null,
  description varchar(200),
  primary key(job_id)
);

create table if not exists user_has_job(
  has_job_id int unsigned not null auto_increment,
  assigner_id varchar(30) not null,
  artisan_id varchar(30) not null,
  job_id int unsigned not null,
  primary key(has_job_id)
);

create table if not exists user_has_focus(
  has_focus_id int unsigned not null auto_increment,
  user_id varchar(30) not null,
  focus_id int unsigned not null,
  primary key(has_focus_id)
);
