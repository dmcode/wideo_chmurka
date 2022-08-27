set names utf8mb4 collate utf8mb4_unicode_ci;


# =============================================================================
# Rejestruje liczbę wyświetleń video
delimiter //
create procedure register_view(
    video_slug varchar(128) character set utf8mb4 collate utf8mb4_unicode_ci,
    number int unsigned
)
comment 'Rejestruje liczbę wyświetleń video'
    begin
        UPDATE library l LEFT JOIN video v ON (l.video_id=v.id)
        SET l.number_views = l.number_views + number
        WHERE v.slug = video_slug
        LIMIT 1;
    end //
delimiter ;
