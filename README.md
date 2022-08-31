# Wideo Chmurka
Projekt prostego serwisu do nagrywania pulpitu i publikowania wideo wykonany w technologii PHP 8 (Slim 4), HTML 5, JavaScript, Mysql.

## Szybki start

Uruchomienie projektu wymaga zainstalowania:
* docker
* plugin docker compose
* git
* git lfs


```bash
$ git clone git@github.com:dmcode/wideo_chmurka.git
$ cd wideo_chmurka
$ cp .env.default .env
$ docker compose build
$ docker compose up
```

Aplikacja będzie dostępna pod adresem: http://localhost:8000


## Wymagania w przypadku ręcznej konfiguracji środowiska

 * Mysql 8.0 (niższe wersje nie testowane)
 * PHP 8.1 (niższe wersje nie testowane)
 * Włączone moduły PHP: pdo_mysql
 * Zainstalowany ffmpeg


## Screenshots

![Podgląd strony głównej](./screenshot.jpeg)
