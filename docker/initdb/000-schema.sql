set names utf8mb4 collate utf8mb4_unicode_ci;

create table user (
    id int unsigned auto_increment primary key,
    email varchar(128) not null,
    password varchar(128) not null,
    is_active boolean not null default true,
    date_joined datetime not null default CURRENT_TIMESTAMP
);
