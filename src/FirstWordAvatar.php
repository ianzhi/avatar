<?php
namespace IanZhi\Avatar;

use Ianzhi\Avatar\Interfaces\IAvatar;

/**
 * 根据用户名生成头像
 */
class FirstWordAvatar implements IAvatar
{
    /**
     * 配置项
     */
    private $config = [
        'name' => 'undefined',
        'type' => 'png', // jpeg|png|gif|bmp
        'width' => '100',
        'height' => '100',
        'size' => '20', // 文字大小，单位：磅
        'path' => false,
        'font_file' => './fonts/msyh.ttf'
    ];

    /**
     * 构造方法
     * @param array $config 配置项
     */
    public function __construct(array $config = [])
    {
        if (isset($config) && $config) {
            $this->config = $config;
        }
    }

    /**
     * 配置
     * @param string|array $key
     * @param mixed $value
     * @return FirstWordAvatar 对象
     */
    public function set($key, $value)
    {
        if (array_key_exists($key, $this->config)) {
            $this->config[$key] = $value;
        }
        return $this;
    }

    /**
     * 生成图像
     * @return resource 图片资源
     */
    private function generate()
    {
        // 创建图片资源
        $img_res = imagecreate($this->config['width'], $this->config['height']);

        // 背景颜色
        $bg_color = imagecolorallocate($img_res, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
        // 文字颜色
        $font_color = imagecolorallocate($img_res, mt_rand(150, 255), mt_rand(150, 255), mt_rand(150, 255));

        // 填充背景色
        imagefill($img_res, 1, 1, $bg_color);

        // 计算文字的宽高
        $pos = imagettfbbox($this->config['size'], 0, $this->config['font_file'], mb_substr($this->config['name'], 0, 1));
        $font_width = $pos[2] - $pos[0] + 0.0763 * $this->config['size'];
        $font_height = $pos[1] - $pos[5] + 0.0763 * $this->config['size'];

        // 写入文字
        imagettftext($img_res, $this->config['size'], 0, ($this->config['width'] - $font_width) / 2, ($this->config['height'] - $font_height) / 2 + $font_height, $font_color, $this->config['font_file'], mb_substr($this->config['name'], 0, 1));
        
        return $img_res;
    }
    
    /**
     * 输出图片（默认输出到浏览器，给定输出文件位置则输出到文件）
     * @param string|false $path 保存路径
     */
    public function output($path = false)
    {
        // 保存路径
        if (isset($path) && $path) {
            $this->config['path'] = $path;
        }

        $img_res = $this->generate();

        // 确定输出类型和生成用的方法名
        $content_type = 'image/' . $this->config['type'];
        $generateMethodName = 'image' . $this->config['type'];

        // 确定是否输出到浏览器
        if (!$this->config['path']) {
            header("Content-type: " . $content_type);
            $generateMethodName($img_res);
        } else {
            $generateMethodName($img_res, $this->config['path']);
        }
        imagedestroy($img_res);
    }
}