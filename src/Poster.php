<?php

namespace Fairy;

/**
 * 生成海报
 * Class Poster
 */
class Poster
{
    protected $background;

    protected $resources = [];

    protected $colors = [];

    protected $fontFamily;

    /**
     * @param string $backgroundImage 背景图片
     * @throws \Exception
     */
    public function __construct($backgroundImage)
    {
        $this->fontFamily = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'SourceHanSansCN-Normal.otf';
        $this->background = $this->resource($backgroundImage);
    }

    /**
     * 获取图像资源
     * @param string $image 图片地址或者画布大小字符串
     * @return false|resource
     * @throws \Exception
     */
    protected function resource($image)
    {
        $size = explode(',', $image);
        if (count($size) === 2) {
            $resource = imagecreatetruecolor($size[0], $size[1]);
        } else {
            if (!file_exists($image)) {
                throw new \Exception('图片' . $image . '不存在');
            }
            $resource = imagecreatefromstring(file_get_contents($image));
        }
        $this->resources[] = $resource;

        return $resource;
    }

    /**
     * 图像圆形处理
     * @param resource $resource 图像资源
     * @return false|resource
     * @throws \Exception
     */
    protected function circle($resource)
    {
        //创建透明画布
        $width = $height = min(imagesx($resource), imagesy($resource));
        $dstImg = $this->resource($width . ',' . $height);
        imagesavealpha($dstImg, true);
        $transparent = imagecolorallocatealpha($dstImg, 255, 255, 255, 127);
        imagefill($dstImg, 0, 0, $transparent);
        //填充图片中在圆内的点到透明画布
        $radius = $width / 2;
        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                if ((pow($x - $radius, 2) + pow($y - $radius, 2)) < pow($radius, 2)) {
                    imagesetpixel($dstImg, $x, $y, imagecolorat($resource, $x, $y));
                }
            }
        }

        return $dstImg;
    }

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
    {
        $dstImg = $this->resource($image);
        $circle && $dstImg = $this->circle($dstImg);
        imagecopyresampled($this->background, $dstImg, $x, $y, 0, 0, $width, $height, imagesx($dstImg), imagesy($dstImg));

        return $this;
    }

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
    {
        imagettftext($this->background, $size, 0, $x, $y, $this->color($rgb), $fontFamily ?: $this->fontFamily, $text);

        return $this;
    }

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
    {
        imagesetthickness($this->background, $weight);
        imageline($this->background, $x1, $y1, $x2, $y2, $this->color($rgb));

        return $this;
    }

    /**
     * 获取颜色
     * @param string $rgb rgb颜色字符串 逗号隔开
     * @return false|int
     * @throws \Exception
     */
    protected function color($rgb)
    {
        if (isset($this->colors[$rgb])) {
            return $this->colors[$rgb];
        }
        $rgbArr = explode(',', $rgb);
        if (count($rgbArr) !== 3) {
            throw new \Exception('rgb颜色格式错误');
        }
        $color = imagecolorallocate($this->background, $rgbArr[0], $rgbArr[1], $rgbArr[2]);
        $this->colors[$rgb] = $color;

        return $color;
    }

    /**
     * 输出
     * @param null $filename 保存图片名称
     */
    public function output($filename = null)
    {
        $ext = $filename ? pathinfo($filename, PATHINFO_EXTENSION) : 'jpg';
        switch ($ext) {
            case 'gif':
                $function = 'imagegif';
                $mime = 'image/gif';
                break;
            case 'png':
                $function = 'imagepng';
                $mime = 'image/png';
                break;
            case 'jpg':
            case 'jpeg':
            default:
                $function = 'imagejpeg';
                $mime = 'image/jpeg';
                break;
        }

        if (is_null($filename)) {
            header('Content-type:' . $mime);
            call_user_func($function, $this->background);
        } else {
            call_user_func($function, $this->background, $filename);
        }
    }

    public function __destruct()
    {
        foreach ($this->resources as $resource) {
            imagedestroy($resource);
        }
    }
}


