<?php
/**
 * 文件后缀名映射表
 *
 * @author camera360_server@camera360.com
 * @copyright Chengdu pinguo Technology Co.,Ltd.
 */
$config['fileHeader']['normal'] = ['Content-Type: application/octet-stream', 'Content-Transfer-Encoding: binary'];
$config['fileHeader']['jpg']  = ['Content-Type: image/jpeg'];
$config['fileHeader']['jpeg'] = ['Content-Type: image/jpeg'];
$config['fileHeader']['png']  = ['Content-Type: image/jpeg'];
$config['fileHeader']['svg']  = ['Content-Type: image/svg+xml'];
$config['fileHeader']['txt']  = ['Content-type: text/plain'];
$config['fileHeader']['css']  = ['Content-type: text/css'];
$config['fileHeader']['js']   = ['Content-type: application/javascript'];
$config['fileHeader']['json'] = ['Content-type: application/json'];
$config['fileHeader']['pdf']  = ['Content-type: application/pdf'];
$config['fileHeader']['html'] = ['Content-Type: text/html; charset=utf-8'];
$config['fileHeader']['zip']  = ['Content-Type: application/zip'];
$config['fileHeader']['mp3']  = ['Content-Type: audio/mpeg'];
$config['fileHeader']['mp4']  = ['Content-Type: audio/mpeg'];
$config['fileHeader']['swf']  = ['Content-Type: application/x-shockwave-flash'];
$config['fileHeader']['ico']  = ['Content-Type: image/x-icon'];

return $config;
