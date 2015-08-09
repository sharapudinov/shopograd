<?php
return array(
    'utf_mode' =>
        array(
            'value' => true,
            'readonly' => true,
        ),
    'cache_flags' =>
        array(
            'value' =>
                array(
                    'config_options' => 3600,
                    'site_domain' => 3600,
                ),
            'readonly' => false,
        ),
    'cookies' =>
        array(
            'value' =>
                array(
                    'secure' => false,
                    'http_only' => true,
                ),
            'readonly' => false,
        ),
    'exception_handling' =>
        array(
            'value' =>
                array(
                    'debug' => true,
                    'handled_errors_types' => 20853,
                    'exception_errors_types' => 20853,
                    'ignore_silence' => false,
                    'assertion_throws_exception' => true,
                    'assertion_error_type' => 256,
                    'log' =>
                        array(
                            'settings' =>
                                array(
                                    'file' => NULL,
                                    'log_size' => NULL,
                                ),
                        ),
                ),
            'readonly' => false,
        ),
    'connections' =>
        array(
            'value' =>
                array(
                    'default' =>
                        array(
                            'className' => '\\Bitrix\\Main\\DB\\MysqlConnection',
                            'host' => 'localhost',
                            'database' => 'shopograd',
                            'login' => 'root',
                            'password' => '',
                            'options' => 2,
                        ),
                ),
            'readonly' => true,
        ),
    'cache' =>
        array(
            'value' =>
                array(
                    'type' => 'memcache',
                    'sid' => $_SERVER["DOCUMENT_ROOT"] . "s1",
                    'memcache' =>
                        array(
                            'host' => 'unix:///tmp/memcached.sock',
                            'port' => 0
                        ),
                ),
            'readonly' => false,
        ),
);
