create table userinfo(
  user_id int(11) not null auto_increment primary key,
  name varchar(255) not null,
  password varchar(255) not null,
  created datetime not null,
  icon varchar(255) not null  default Null,
  back_img int not null default '0'
  Intoroduction varchar(255) not null default Null
);

create table tweets(
  author_id int(11) not null,
  tweets_id int(11) not null auto_increment primary key,
  content text,
  tweet_img varchar(255) default null,
  re_text varchar(255) default null,
  create_at datetime not null,
  modefied timestamp not null
)

create table replay_posts(
  reply_co_id int(11) not null auto_increment primary key,
  reply_id int(11) not null,
  reply_author_id int(11) not null,
  author_name varchar(255) not null,
  reply_content text,
  reply_img varchar(255) default null,
  re_create_at datetime not null,
  re_modefied timestamp not null
)
