# 生成海报

基于 GD 库，生成海报！

安装
------------

```bash
composer require cshaptx4869/poster
```

示例
-------

> run.php

```php
<?php

require_once './vendor/autoload.php';

use Fairy\Poster;

try {
    $poster = new Poster('./images/bg.png');
    $poster->image('./images/avatar.jpg', 94, 52, 82, 82, true)
        ->image('./images/service.png', 40, 310, 665, 566)
        ->image('./images/mini.jpg', 518, 1070, 180, 180)
        ->font('白開水', 26, 220, 86, '150, 151, 150')
        ->font('我们感情深，帮忙助力行不行', 35, 30, 210, '255, 255, 255')
        ->font('单次4小时，专业保洁师为您解决全屋精细保洁', 24, 30, 270, '255, 255, 255')
        ->font('&#165; 0.00', 40, 50, 946, '255, 0, 0')
        ->font('原价 &#165;199', 20, 50, 990, '150, 151, 150')
        ->line(44, 980, 170, 980, '150, 151, 150', 2)
        ->output();
    exit();
} catch (\Exception $e) {
    file_put_contents('./error.log', $e->getMessage(), FILE_APPEND);
}
```

> 方法说明

- new Poster()

```php
/**
 * 生成一张背景图
 * @param string $backgroundImage 背景图片
 * @throws \Exception
 */
public function __construct($backgroundImage)
```

- image()

```php
/**
 * 设置图像
 * @param string $image 本地图片
 * @param int $x 起始x坐标
 * @param int $y 起始y坐标
 * @param int $width 所占宽度
 * @param int $height 所占高度
 * @param bool $circle 是否需要转成圆形
 * @return $this
 * @throws \Exception
 */
public function image($image, $x, $y, $width, $height, $circle = false)
```

- font()

```php
/**
 * 设置文字
 * @param string $text 文本
 * @param float $size 字体大小
 * @param int $x 起始x坐标(注意是字体左下角的坐标)
 * @param int $y 起始y坐标
 * @param string $rgb rgb颜色字符串 逗号隔开
 * @param string $fontFamily 字体
 * @return $this
 * @throws \Exception
 */
public function font($text, $size, $x, $y, $rgb = '255,255,255', $fontFamily = null)
```

- line()

```php
/**
 * 设置线条
 * @param int $x1 第1个点x坐标
 * @param int $y1 第1个点y坐标
 * @param int $x2 第2个点x坐标
 * @param int $y2 第2个点y坐标
 * @param string $rgb rgb颜色字符串 逗号隔开
 * @param int $weight 线条粗细
 * @return $this
 * @throws \Exception
 */
public function line($x1, $y1, $x2, $y2, $rgb, $weight = 1)
```

- output()

```php
/**
 * 输出
 * @param null $filename 保存图片名称,不写则浏览器输出
 */
public function output($filename = null)
```

