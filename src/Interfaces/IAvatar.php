<?php
namespace IanZhi\Avatar\Interfaces;

interface IAvatar
{
    /**
     * 用于配置
     * @param string $key
     * @param mixed $key
     * @return IAvatar 类对象
     */
    function set($key, $value);

    /**
     * 存储文件
     * @param string $path 存储路径
     * @return boolean|string 如果设置了保存路径，返回布尔值 没有设置，成功放回路径，失败返回false
     */
    function output();
}