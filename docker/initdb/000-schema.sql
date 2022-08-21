set names utf8mb4 collate utf8mb4_unicode_ci;

create table user
(
    id int unsigned auto_increment primary key,
    email varchar(128) not null,
    password varchar(250) not null,
    is_active boolean not null default true,
    date_joined datetime not null default CURRENT_TIMESTAMP
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
    title varchar(100) default null
);

alter table video
    add constraint v_uidx_slug unique (slug)
;
