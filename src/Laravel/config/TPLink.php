<?php
return [
    'heater' => [
        'ip'             => '192.168.1.100',         //Or hostname eg: home.example.com
        'port'           => '9999',
        'timeout'        => 5,                       // Optional, timeout setting (how long we will try communicate with device before giving up)
        'timeout_stream' => 3,                       // Optional, timeout setting for stream (how long to wait for the response from the device)
        'deviceType'     => 'IOT.SMARTPLUGSWITCH',   // Smart Bulbs are also supported: 'IOT.SMARTBULB'
    ],

//    'bedroom' => [
//        'ip'              => '192.168.1.101',
//        'port'            => '9999',
//        'timeout'         => 5,
//        'timeout_stream'  => 3,
//        'deviceType'      => 'IOT.SMARTPLUGSWITCH',
//    ],

//    'livingroom' => [
//        'ip'              => '192.168.1.102',
//        'port'            => '9999',
//        'timeout'         => 10,
//        'timeout_stream'  => 3,
//        'deviceType'      => 'IOT.SMARTBULB',
//    ],

];