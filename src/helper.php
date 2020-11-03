<?php
namespace bingher\zipimg;

/**
 * 压缩图片
 *
 * @param string $input  输入文件路径
 * @param string $output 输出文件路径 默认为空覆盖源文件
 *
 * @return string         输出文件路径
 */
function zipimg($input, $output = '')
{
    $zipimg = new Image();
    return $zipimg->zip($input, $output);
}

/**
 * 远程调用python压缩图片
 *
 * @param string $input  输入文件路径
 * @param string $output 输出文件路径 默认为空覆盖源文件
 *
 * @return string         输出文件路径
 */
function remote_zipimg($input, $output = '')
{
    $client = new \bingher\transmit\Client('127.0.0.1', 8000);
    $params = [
        'in_img'  => $input,
        'out_img' => $output,
    ];
    return $client->zip($params);
}
