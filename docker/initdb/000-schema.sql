set names utf8mb4 collate utf8mb4_unicode_ci;

create table user
(
    id int unsigned auto_increment primary key,
    email varchar(128) not null,
    password varchar(250) not null,
    is_active boolean not null default true,
    date_joined datetime not null default CURRENT_TIMESTAMP,
    last_login datetime default null
)
    character set utf8mb4 collate utf8mb4_unicode_ci
    comment 'Konta użytkowników'
;

alter table user
    add constraint u_uidx_email unique (email)
;


create table video
(
    id int unsigned auto_increment primary key,
    slug varchar(128) not null,
    created_at datetime not null default CURRENT_TIMESTAMP,
    updated_at datetime default null,
    duration integer not null default 0,
    res_w smallint not null default 0,
    res_h smallint not null default 0,
    size integer unsigned not null default 0,
    codec_name varchar(20) default null,
    format_name varchar(20) default null
)
    character set utf8mb4 collate utf8mb4_unicode_ci
    comment 'Repozytorium wideo'
;

alter table video
    add constraint v_uidx_slug unique (slug)
;


create table library
(
    id int unsigned auto_increment primary key,
    user_id int unsigned not null,
    video_id int unsigned not null,
    visibility ENUM('private', 'protected', 'public') default 'private',
    thumb varchar(128) default null,
    title varchar(100) default null,
    description varchar(1000) default null
)
    character set utf8mb4 collate utf8mb4_unicode_ci
    comment 'Biblioteka wideo użytkownika'
;

alter table library
    add index l_idx_user_id  (user_id),
    add constraint l_uidx_user_video unique (user_id, video_id),
    add constraint l_fk_user foreign key (user_id)
        references user(id) on delete cascade,
    add index l_idx_video_id  (video_id),
    add constraint l_fk_video foreign key (video_id)
        references video(id) on delete cascade
;
