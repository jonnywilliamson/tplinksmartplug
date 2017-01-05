PHP Library to Control and Access a TP-Link Smartplug!
=========
<p align="center">
<a href="https://github.com/jonnywilliamson/tplinksmartplug"><img src="https://raw.githubusercontent.com/jonnywilliamson/tplinksmartplug/master/tplinkplug.jpg" alt="Smart Plug"></a>
<a href="https://github.com/jonnywilliamson/tplinksmartplug"><img src="https://travis-ci.org/jonnywilliamson/tplinksmartplug.svg?branch=master" alt="Build Status"></a>
<a href="https://github.com/jonnywilliamson/tplinksmartplug"><img src="https://poser.pugx.org/williamson/tplinksmartplug/downloads" alt="Total Downloads"></a>
<a href="https://github.com/jonnywilliamson/tplinksmartplug"><img src="https://poser.pugx.org/williamson/tplinksmartplug/v/stable" alt="Latest Stable Version"></a>
<a href="https://github.com/jonnywilliamson/tplinksmartplug"><img src="https://poser.pugx.org/williamson/tplinksmartplug/license" alt="License"></a>
</p>

###(Bonus Laravel integration supported!!)

**[TPLink Smartplug](https://github.com/jonnywilliamson/tplinksmartplug) is a small PHP library that allows anyone to control and access a TPLink Smartplug.** 

Current TPLINK models supported are
 - [HS110](http://uk.tp-link.com/products/details/cat-5258_HS110.html)
 - [HS100](http://uk.tp-link.com/products/details/cat-5258_HS100.html)
 
It is likely that other TPLink models will also work, but these have not been checked.

## Installation
This package can be installed standalone in a regular PHP project, or can also be integrated into Laravel to make life even easier.

To install the latest version simply use composer to add it to your project using the following command:

```
composer require williamson/tplinksmartplug
```

### Laravel Installation/Integration

Once the TPLink Smartplug library is installed, you need to register the library's service provider, in `config/app.php`:

```php
'providers' => array(
    //...
    Williamson\TPLinkSmartplug\Laravel\TPLinkServiceProvider::class,
)
```
##### Facades

By default, this library will *automatically* register a facade to be used in Laravel. The package checks first to ensure `TPLink` has not already be registered and if this is the case, will register `TPLink` as your quick access to the library. More examples to follow.

##### Config file
This package requires a config file so that you can provide the address/details of the TPLink devices you would like to control. To generate this file, run the following command: 
```
$ php artisan vendor:publish --provider='Williamson\TPLinkSmartplug\Laravel\TPLinkServiceProvider'
```
This will create a `TPLink.php` file in your Laravel `config` folder. You should edit this to setup your devices.

## Configuration
The config file is a very simple array structured file. A config file is required for both standalone/Laravel projects. The content is similar to this:

```php
//TPLink.php

<?php
return [
    'lamp' => [
        'ip'   => '192.168.1.100', //Or hostname eg: home.example.com
        'port' => '9999',
    ],
];
```

You may add as many devices as you wish, as long as you specify the IP address (or host address if required) and port number to access each one. Giving each device a name makes it easy to identify them when coding later. _(Please note that the name you give here does NOT have to match the actual name you might have assigned the device using an official app like Kasa. They do NOT have to match)_

## Usage
You can access your device either through the `TPLinkManager` class (especially useful if you have multiple devices), or directly using the `TPLinkDevice` class.

Using the manager, allows you to specify *WHICH* device you would like to send your command to.

If you only have one device you *may* just want to use the `TPDevice` class by itself - but using the manager is recommended.

Depending on your style of coding you may use either the Facade or instantiate the object yourself.

The following are all similar:
```php

//Non laravel
    $tpManager = new TPLinkManager($configArray);
    $tpDevice = $tpManager->device('lamp')

//Laravel
    //with facade
    TPLink::device('lamp')
    
    //without facade
    $tpDevice = app('tplink')->device('lamp');
    $tpDevice = app(TPLinkManager::class)->device('lamp');

```

Once you have your device ready, you can then send it a command.

#### Commands
All commands for the smartplug have been created in a separate class to ease use and allow for more to be added easily in the future.
 
To send a command, simply call the `sendCommand` method on the `TPDevice` object and pass in the command required as a parameter.

For example, to get the current status of the smartplug  
```php
//Non laravel
    
    $tpDevice->sendCommand(TPLinkCommand::systemInfo());


//Laravel
    //with facade
    TPLink::device('lamp')->sendCommand(TPLinkCommand::systemInfo());
    
    //without facade
    $tpDevice->sendCommand(TPLinkCommand::systemInfo());

```

If a command requires a parameter, provide that as well:

```php
//Non laravel
    
    $tpDevice->sendCommand(TPLinkCommand::setLED(false));


//Laravel
    //with facade
    TPLink::device('lamp')->sendCommand(TPLinkCommand::setLED(false));
    
    //without facade
    $tpDevice->sendCommand(TPLinkCommand::setLED(false));
```

####Toggle Power
There is one command that is called directly on the `TPLinkDevice` and that is the `togglePower()` method.

If you only wish to toggle the current power state of the plug, use it as follows: 
```php
//Non laravel
    
    $tpDevice->togglePower();


//Laravel
    //with facade
    TPLink::device('lamp')->togglePower();
    
    //without facade
    $tpDevice->togglePower();
```


There are a large number of commands in the `TPLinkCommand` class. Please read the docblock comments for explanations and requirements for each one.
 
The current list of commands available to use are:
 ```
 systemInfo
 powerOn
 powerOff
 setLED
 setDeviceAlias
 setMacAddress
 setDeviceId
 setHardwareId
 setLocation
 checkUboot
 getDeviceIcon
 getDownloadState
 checkConfig
 flashFirmware
 downloadFirmware
 setTestMode
 reboot
 reset
 cloudInfo
 cloudFirmwareList
 cloudSetServerUrl
 cloudConnectWithAccount
 cloudUnregisterDevice
 wlanScan
 wlanConnectTo
 getTime
 getTimezone
 setTimeAndTimeZone
 emeterRealtimeReading
 emeterGainSettings
 emeterSetGains
 emeterStartCalibration
 emeterStatsMonth
 emeterStatsYear
 emeterStatsWipeAll
 scheduleNext
 scheduleRuleList
 scheduleRuleCreate
 scheduleRuleEdit
 scheduleRuleDelete
 scheduleRuleWipeAll
 scheduleRuntimeStatsWipeAll
 countdownRuleList
 countdownRuleCreate
 countdownRuleEdit
 countdownRuleDelete
 countdownRuleWipeAll
 antitheftRuleList
 antitheftRuleCreate
 antitheftRuleEdit
 antitheftRuleDelete
 antitheftRuleWipeAll
 ```

## Disclaimer

This project and its author is neither associated, nor affiliated with [Telegram](https://telegram.org/) in anyway.
See License section for more details.

## Additional information

Any issues, feedback, suggestions or questions please use issue tracker [here][link-issues].

## Credits

- [softScheck](https://github.com/softScheck/tplink-smartplug) (Who did the reverse engineering and provided the secrets on how to talk to the Smartplug.)
- [Jonathan Williamson][link-author]

## Disclaimer

This project and its author is neither associated, nor affiliated with [TP-LINK](http://www.tp-link.com/en/) in anyway.
See License section for more details.

## License

This project is released under the [MIT][link-license] License.

© 2017 [Jonathan Williamson][link-author], All rights reserved.

[link-author]: https://github.com/jonnywilliamson
[link-repo]: https://github.com/jonnywilliamson/tplinksmartplug
[link-issues]: https://github.com/jonnywilliamson/tplinksmartplug/issues
[link-license]: https://github.com/jonnywilliamson/tplinksmartplug/blob/master/LICENSE