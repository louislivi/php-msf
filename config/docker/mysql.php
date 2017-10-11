<?php
/**
 * Docker环境
 */
$config['mysql']['master']['host']            = '192.168.0.1';
$config['mysql']['master']['port']            = 3306;
$config['mysql']['master']['user']            = 'maiya';
$config['mysql']['master']['password']        = 'mAiya123';
$config['mysql']['master']['charset']         = 'utf8mb4';
$config['mysql']['master']['database']        = 'article';

$config['mysql']['slave1']['host']           = '192.168.0.1';
$config['mysql']['slave1']['port']           = 3306;
$config['mysql']['slave1']['user']           = 'maiya';
$config['mysql']['slave1']['password']       = 'mAiya123';
$config['mysql']['slave1']['charset']        = 'utf8mb4';
$config['mysql']['slave1']['database']       = 'article';

$config['mysql']['slave2']['host']           = '192.168.0.1';
$config['mysql']['slave2']['port']           = 3306;
$config['mysql']['slave2']['user']           = 'maiya';
$config['mysql']['slave2']['password']       = 'mAiya123';
$config['mysql']['slave2']['charset']        = 'utf8mb4';
$config['mysql']['slave2']['database']       = 'article';

$config['mysql_proxy']['master_slave'] = [
    'pools' => [
        'master' => 'master',
        'slaves' => ['slave1', 'slave2'],
    ],
    'mode' => \PG\MSF\Marco::MASTER_SLAVE,
];

return $config;