<?php

require_once './vendor/autoload.php';

use Fairy\Poster;

try {
    $poster = new Poster('./images/poster.png');
    $poster->image('./images/avatar.jpg', 94, 52, 82, 82, true)
        ->image('./images/service.png', 40, 310, 665, 566)
        ->image('./images/mini.jpg', 518, 1070, 180, 180)
        ->text('白開水', 26, 220, 86, '150, 151, 150')
        ->text('我们感情深，帮忙助力行不行', 35, 30, 210, '255, 255, 255')
        ->text('单次4小时，专业保洁师为您解决全屋精细保洁', 24, 30, 270, '255, 255, 255')
        ->text('&#165; 0.00', 40, 50, 946, '255, 0, 0')
        ->text('原价 &#165;199', 20, 50, 990, '150, 151, 150')
        ->line(44, 980, 170, 980, '150, 151, 150', 2)
        ->output('./a.png');
} catch (\Exception $e) {
    file_put_contents('./error.log', $e->getMessage(), FILE_APPEND);
}
