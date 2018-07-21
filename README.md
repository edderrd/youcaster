YouCaster
==========

<p align="left">
    <a href="https://laravel.com"><img src="https://img.shields.io/badge/laravel-5.6.27-blue.svg" alt="laravel"></a>
</p>


Download and listen your favorite youtube videos as podcasts

![YouCaster screenshot](/screenshot.png)

## Getting Started

### Prerequisites

- [Laravel 5.6](https://laravel.com/docs/5.6)
- [Node + NPM](https://nodejs.org)
- [Composer](https://getcomposer.org/)
- [Youtube-dl](https://rg3.github.io/youtube-dl/) (download youtube videos)
- [ffmpeg](https://www.ffmpeg.org/) (convert videos to mp3)
- [redis](https://redis.io/) (cache and queues)
- [pusher](https://pusher.com/) (websockets and download progress)
- [sparkpost](https://www.sparkpost.com/) (emails)

### Installing

```
git clone git@gitlab.com:edderrd/youcaster.git
cd youcaster
composer install
cp .env{.example,}
cp Envoy.blade.php{.example,}
php artisan key:generate
npm install
```

#### Config files

Configure your `.env` with your settings

Database configuration
```
DB_USERNAME=
DB_PASSWORD=
```

Pusher (realtime websockets)
```
PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
```

Youtube-dl and ffmpeg paths
```
YOUTUBE_DL_BIN_PATH=/usr/local/bin/youtube-dl
YOUTUBE_DL_FFMPEG=/usr/local/bin/ffmpeg
```

sparkpost (mail) key and email from (sending domain should match on your sparkpost configuration)
```
SPARKPOST_SECRET=
MAIL_FROM_ADDRESS=
```

Finally migrate your database
```
php artisan migrate
```

## Deployment

In order to deploy correctly you need access to your need to have ssh access and correct permissions on destination host and folder

```
envoy run deploy
```

**Note:** In order to run queues you need to refer to [laravel supervisor configuration](https://laravel.com/docs/5.6/queues#supervisor-configuration).

**Note 2:** make sure you have [redis](https://redis.io/), [ffmpeg](https://www.ffmpeg.org/) and [youtube-dl](https://rg3.github.io/youtube-dl/) on destination server.

## Authors

* **Edder Rojas** - *Initial work* - [edderrd](https://github.com/edderrd)
