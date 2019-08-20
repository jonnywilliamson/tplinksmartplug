<?php

namespace Williamson\TPLinkSmartplug;

use DateTime;
use stdClass;
use Exception;
use InvalidArgumentException;
use Illuminate\Support\Collection;

/**
 * Class TPLinkCommands
 *
 * @package Williamson\TPLinkSmartplug
 * @link    https://github.com/softScheck/tplink-smartplug/blob/master/tplink-smarthome-commands.txt
 */
class TPLinkCommand
{

    const WEP = 1;
    const WPA = 2;
    const WPA2 = 3;


    /**
     * Get System Info (Software & Hardware Versions, MAC, deviceID, hwID etc.)
     *
     * @return array
     */
    public static function systemInfo()
    {
        return [
            'system' => [
                'get_sysinfo' => new stdClass(),
            ],
        ];
    }

    /**
     * Turn switch On
     *
     * @return array
     */
    public static function powerOn()
    {
        return [
            'system' => [
                'set_relay_state' => [
                    'state' => 1,
                ],
            ],
        ];
    }

    /**
     * Turn switch Off
     *
     * @return array
     */
    public static function powerOff()
    {
        return [
            'system' => [
                'set_relay_state' => [
                    'state' => 0,
                ],
            ],
        ];
    }

    /**
     * Turn On/off Device LED
     *
     * @param bool $isOn
     *
     * @return array
     */
    public static function setLED($isOn = true)
    {
        return [
            'system' => [
                'set_led_off' => [
                    'off' => (int)!$isOn,
                ],
            ],
        ];
    }

    /**
     * Set Device Alias
     *
     * @param string $name
     *
     * @return array
     */
    public static function setDeviceAlias($name)
    {
        return [
            'system' => [
                'set_dev_alias' => [
                    'alias' => "$name",
                ],
            ],
        ];
    }

    /**
     * Set MAC Address
     *
     * @param string $macAddress A mac address with hyphen between each group.
     *
     * @return array
     */
    public static function setMacAddress($macAddress)
    {
        if (filter_var($macAddress, FILTER_VALIDATE_MAC) === false) {
            throw new InvalidArgumentException('MAC address invalid. Try hyphens between each group of characters.');
        }

        return [
            'system' => [
                'set_mac_addr' => [
                    'mac' => "$macAddress",
                ],
            ],
        ];
    }

    public static function setDeviceId($id)
    {
        return [
            'system' => [
                'set_device_id' => [
                    'deviceId' => $id,
                ],
            ],
        ];
    }

    public static function setHardwareId($id)
    {
        return [
            'system' => [
                'set_hw_id' => [
                    'hwId' => $id,
                ],
            ],
        ];
    }

    /**
     * Set Location
     *
     * @param float $longitude
     * @param float $latitude
     *
     * @return array
     */
    public static function setLocation($longitude, $latitude)
    {
        return [
            'system' => [
                'set_dev_location' => [
                    'longitude' => (float)round($longitude, 6),
                    'latitude'  => (float)round($latitude, 6),
                ],
            ],
        ];
    }

    /**
     * Perform uBoot Bootloader Check
     *
     * @return array
     */
    public static function checkUboot()
    {
        return [
            'system' => [
                'test_check_uboot' => null,
            ],
        ];
    }

    /**
     * Get Device Icon
     *
     * @return array
     */
    public static function getDeviceIcon()
    {
        return [
            'system' => [
                'get_dev_icon' => null,
            ],
        ];
    }

    /**
     * Get Download State
     *
     * @return array
     */
    public static function getDownloadState()
    {
        return [
            'system' => [
                'get_download_state' => new stdClass(),
            ],
        ];
    }

    /**
     * Check Config
     *
     * @return array
     */
    public static function checkConfig()
    {
        return [
            'system' => [
                'check_new_config' => null,
            ],
        ];
    }

    /**
     * Flash Downloaded Firmware
     *
     * @param bool $confirm
     *
     * @return array
     */
    public static function flashFirmware($confirm = false)
    {
        if ($confirm) {
            return [
                'system' => [
                    'flash_firmware' => new stdClass(),
                ],
            ];
        }

        throw new InvalidArgumentException('Confirm flag to true before flashing firmware is allowed.');
    }

    /**
     * Download Firmware from URL
     *
     * @param string $url
     *
     * @return array
     */
    public static function downloadFirmware($url)
    {
        if ((filter_var($url, FILTER_VALIDATE_URL) !== false) && substr($url, 0, 4) === 'http') {
            return [
                'system' => [
                    'download_firmware' => [
                        'url' => "$url",
                    ],
                ],
            ];
        }

        throw new InvalidArgumentException('The supplied URL did not meet the correct format.');
    }


    /**
     * Set Test Mode (command only accepted coming from IP 192.168.1.100)
     * @return array
     */
    public static function setTestMode()
    {
        return [
            'system' => [
                'set_test_mode' => [
                    'enable' => 1,
                ],
            ],
        ];
    }

    /**
     * Reboot the device after a delay of x secs
     *
     * @param int $delay
     *
     * @return array
     */
    public static function reboot($delay = 1)
    {
        return [
            'system' => [
                'reboot' => [
                    'delay' => (int)$delay,
                ],
            ],
        ];
    }

    /**
     * Reset the device to factory settings after a delay of x secs
     *
     * @param int $delay
     *
     * @return array
     */
    public static function reset($delay = 1)
    {
        return [
            'system' => [
                'reset' => [
                    'delay' => (int)$delay,
                ],
            ],
        ];
    }


    /**
     * Get Cloud Info (Server, Username, Connection Status)
     *
     * @return array
     */
    public static function cloudInfo()
    {
        return [
            'cnCloud' => [
                'get_info' => new stdClass(),
            ],
        ];
    }

    /**
     * Get Firmware List from Cloud Server
     *
     * @return array
     */
    public static function cloudFirmwareList()
    {
        return [
            'cnCloud' => [
                'get_intl_fw_list' => new stdClass(),
            ],
        ];
    }

    /**
     * Set Server URL that device connects to
     *
     * @param string $url
     *
     * @return array
     */
    public static function cloudSetServerUrl($url = 'devs.tplinkcloud.com')
    {
        return [
            'cnCloud' => [
                'set_server_url' => [
                    'server' => "$url",
                ],
            ],
        ];
    }

    /**
     * Connect with Cloud username & Password
     *
     * @param string $email
     * @param string $password
     *
     * @return array
     */
    public static function cloudConnectWithAccount($email, $password)
    {
        return [
            'cnCloud' => [
                'bind' => [
                    'username' => "$email",
                    'password' => "$password",
                ],
            ],
        ];
    }

    /**
     * Unregister Device from Cloud Account
     *
     * @param bool $confirm
     *
     * @return array
     */
    public static function cloudUnregisterDevice($confirm = false)
    {
        if ($confirm) {
            return [
                'cnCloud' => [
                    'unbind' => null,
                ],
            ];
        }

        throw new InvalidArgumentException('Confirm flag to true before un-registering the device is allowed.');
    }

    /**
     * Scan for list of available APs
     *
     * @param int $refresh
     *
     * @return array
     */
    public static function wlanScan($refresh = 1)
    {
        return [
            'netif' => [
                'get_scaninfo' => [
                    'refresh' => (int)$refresh,
                ],
            ],
        ];
    }

    /**
     * Connect to AP with given SSID and Password
     *
     * @param string $SSID
     * @param string $password
     * @param int    $wifiType
     *
     * @return array
     */
    public static function wlanConnectTo($SSID, $password, $wifiType = 3)
    {
        return [
            'netif' => [
                'set_stainfo' => [
                    'ssid'     => $SSID,
                    'password' => $password,
                    'key_type' => (int)$wifiType,
                ],
            ],
        ];
    }

    /**
     * Get Time currently set on device
     *
     * @return array
     */
    public static function getTime()
    {
        return [
            'time' => [
                'get_time' => new stdClass(),
            ],
        ];
    }

    /**
     * Get Timezone currently set on device. (It's a custom integer number)
     *
     * @return array
     */
    public static function getTimezone()
    {
        return [
            'time' => [
                'get_timezone' => new stdClass(),
            ],
        ];
    }

    /**
     * Set the date, time and timezone on the device.
     *
     * @param DateTime $dateTime
     * @param int      $timeZoneIndex
     *
     * @return array
     */
    public static function setTimeAndTimeZone(DateTime $dateTime, $timeZoneIndex)
    {
        return [
            'time' => [
                'set_timezone' => [
                    'year'  => (int)$dateTime->format('Y'),
                    'month' => (int)$dateTime->format('n'),
                    'mday'  => (int)$dateTime->format('j'),
                    'hour'  => (int)$dateTime->format('G'),
                    'min'   => (int)$dateTime->format('i'),
                    'sec'   => (int)$dateTime->format('s'),
                    'index' => (int)$timeZoneIndex,
                ],
            ],
        ];
    }


    /**
     * Get Realtime Current and Voltage Reading
     *
     * @return array
     */
    public static function emeterRealtimeReading()
    {
        return [
            'emeter' => [
                'get_realtime' => new stdClass(),
            ],
        ];
    }


    /**
     * Get EMeter VGain and IGain Settings
     *
     * @return array
     */
    public static function emeterGainSettings()
    {
        return [
            'emeter' => [
                'get_vgain_igain' => new stdClass(),
            ],
        ];
    }


    /**
     * Set EMeter VGain and Igain (What are these?)
     *
     * @param int $vgain
     * @param int $igain
     *
     * @return array
     */
    public static function emeterSetGains($vgain, $igain)
    {
        return [
            'emeter' => [
                'set_vgain_igain' => [
                    'vgain' => (int)$vgain,
                    'igain' => (int)$igain,
                ],
            ],
        ];
    }


    /**
     * Start EMeter Calibration
     *
     * @param int $vtarget
     * @param int $itarget
     *
     * @return array
     */
    public static function emeterStartCalibration($vtarget, $itarget)
    {
        return [
            'emeter' => [
                'start_calibration' => [
                    'vtarget' => (int)$vtarget,
                    'itarget' => (int)$itarget,
                ],
            ],
        ];
    }


    /**
     * Get Daily Statistics for given Month
     *
     * @param int $mm   2 digit month
     * @param int $yyyy 4 digit year
     *
     * @return array
     */
    public static function emeterStatsMonth($mm, $yyyy)
    {
        return [
            'emeter' => [
                'get_daystat' => [
                    'month' => (int)$mm,
                    'year'  => (int)$yyyy,
                ],
            ],
        ];
    }


    /**
     * Get Monthly Statistic for given Year
     *
     * @param int $yyyy 4 digit year
     *
     * @return array
     */
    public static function emeterStatsYear($yyyy)
    {
        return [
            'emeter' => [
                'get_monthstat' => [
                    'year' => (int)$yyyy,
                ],
            ],
        ];
    }


    /**
     * Erase All EMeter Statistics
     *
     * @return array
     */
    public static function emeterStatsWipeAll()
    {
        return [
            'emeter' => [
                'erase_emeter_stat' => null,
            ],
        ];
    }


    /**
     * Get Next Scheduled Action
     *
     * @return array
     */
    public static function scheduleNext()
    {
        return [
            'schedule' => [
                'get_next_action' => null,
            ],
        ];
    }


    /**
     * Get list of all Schedule Rules
     *
     * @return array
     */
    public static function scheduleRuleList()
    {
        return [
            'schedule' => [
                'get_rules' => null,
            ],
        ];
    }


    /**
     * Add New Schedule Rule
     *
     * @param DateTime $dateAndTime        The actual Date and Time for this event.
     * @param bool     $turnOn             Should the event turn on or off the timer.
     * @param string   $name               An event name. On some clients this isn't even seen.
     * @param array    $daysOfWeekToRepeat (Optional). Days of week event should repeat. Use EN like Tues, Saturday etc.
     *
     * @return array
     */
    public static function scheduleRuleCreate(DateTime $dateAndTime, $turnOn, $name, $daysOfWeekToRepeat = [])
    {
        $data = self::formatDates($dateAndTime, $daysOfWeekToRepeat);

        return [
            'schedule' => self::ruleCommonData(
                'add_rule',
                $dateAndTime,
                $turnOn,
                $name,
                $daysOfWeekToRepeat,
                $data,
                null
            ),
        ];
    }

    /**
     * Depending on if the event is to be repeated during the week or not return the
     * data needed to create the event.
     *
     * @param DateTime $dateAndTime
     * @param          $weekDaysToRepeat
     *
     * @return Collection
     */
    protected static function formatDates(DateTime $dateAndTime, $weekDaysToRepeat)
    {
        if (empty($weekDaysToRepeat)) {
            $data = collect([
                'year'  => $dateAndTime->format('Y'),
                'month' => $dateAndTime->format('n'),
                'day'   => $dateAndTime->format('j'),
                'wday'  => self::createDayMatrix($dateAndTime),
            ]);
        } else {
            $data = collect(['wday' => self::createRepeatingDayMatrix($weekDaysToRepeat)]);
        }

        return $data;
    }

    /**
     * Create the array/matrix required for single events
     *
     * @param DateTime $dateAndTime
     *
     * @return array
     */
    protected static function createDayMatrix(DateTime $dateAndTime)
    {
        $weekMatrix = self::emptyMatrix();
        $weekMatrix[$dateAndTime->format('w')] = 1;

        return $weekMatrix;
    }

    /**
     * Create an empty matrix.
     *
     * @return array
     */
    protected static function emptyMatrix()
    {
        return [0, 0, 0, 0, 0, 0, 0];
    }

    /**
     * Create the array/matrix required for repeating/reoccuring events
     *
     * @param array $daysToReoccur
     *
     * @return array
     */
    protected static function createRepeatingDayMatrix(array $daysToReoccur)
    {
        $weekMatrix = self::emptyMatrix();

        foreach ($daysToReoccur as $dayString) {
            try {
                $weekMatrix[(new DateTime($dayString))->format('w')] = 1;
            } catch (Exception $e) {
                throw new InvalidArgumentException("Invalid date string provided. {$e->getMessage()}");
            }
        }

        return $weekMatrix;
    }

    /**
     * @param string     $type               The type of action that should be performed, add or edit.
     * @param DateTime   $dateAndTime        The actual Date and Time for this event.
     * @param bool       $turnOn             Should the event turn on or off the timer.
     * @param string     $name               An event name. On some clients this isn't even seen.
     * @param array      $daysOfWeekToRepeat (Optional) Day of week event should repeat. Use EN like Tues, Saturday etc.
     * @param Collection $data               specific information depending on if the event is repeating or not.
     * @param string     $ruleId             The ID of the rule to be edited.
     *
     * @return array
     */
    protected static function ruleCommonData(
        $type,
        DateTime $dateAndTime,
        $turnOn,
        $name,
        $daysOfWeekToRepeat,
        $data,
        $ruleId
    ) {
        return [
            $type                => [
                'id'        => $ruleId,
                'enable'    => 1,
                'name'      => "$name",
                'sact'      => (int)$turnOn,
                'repeat'    => (int)!empty($daysOfWeekToRepeat),
                'smin'      => self::calculateMinutes($dateAndTime),
                'emin'      => 0,
                'wday'      => (array)$data->get('wday'),
                'day'       => (int)$data->get('day', 0),
                'month'     => (int)$data->get('month', 0),
                'year'      => (int)$data->get('year', 0),
                'etime_opt' => -1,
                'eact'      => -1,
                'stime_opt' => 0,
                'force'     => 0,
                'longitude' => 0,
                'latitude'  => 0,
            ],
            'set_overall_enable' => [
                'enable' => 1,
            ],
        ];
    }

    /**
     * All start/end times on the device are recorded as minutes from midnight.
     *
     * Return the required minute value from the supplied DateTime object
     *
     * @param DateTime $dateAndTime
     *
     * @return int
     */
    protected static function calculateMinutes(DateTime $dateAndTime)
    {
        $datetime2 = clone $dateAndTime;
        $datetime2->setTime(00, 00, 00);

        $interval = $dateAndTime->diff($datetime2);

        //Timer needs minutes since midnight
        return ($interval->h * 60 + $interval->i);
    }

    /**
     * Edit Schedule Rule with given ID
     *
     * @param string   $ruleId             The ID of the rule to be edited.
     * @param DateTime $dateAndTime        The actual Date and Time for this event.
     * @param bool     $turnOn             Should the event turn on or off the timer.
     * @param string   $name               An event name. On some clients this isn't even seen.
     * @param array    $daysOfWeekToRepeat (Optional). Days of week event should repeat. Use EN like Tues, Saturday etc.
     *
     * @return array
     */
    public static function scheduleRuleEdit($ruleId, DateTime $dateAndTime, $turnOn, $name, $daysOfWeekToRepeat = [])
    {
        $data = self::formatDates($dateAndTime, $daysOfWeekToRepeat);

        return [
            'schedule' => self::ruleCommonData(
                'edit_rule',
                $dateAndTime,
                $turnOn,
                $name,
                $daysOfWeekToRepeat,
                $data,
                $ruleId
            ),
        ];
    }

    /**
     * Delete the schedule rule with the provided ID.
     *
     * @param string $ruleId
     *
     * @return array
     */
    public static function scheduleRuleDelete($ruleId)
    {
        return [
            'schedule' => [
                'delete_rule' => [
                    'id' => $ruleId,
                ],
            ],
        ];
    }

    /**
     * Delete All Schedule Rules
     *
     * @return array
     */
    public static function scheduleRuleWipeAll()
    {
        return [
            'schedule' => [
                'delete_all_rules' => null,
            ],
        ];
    }

    /**
     * Erase all Runtime Statistics
     *
     * @return array
     */
    public static function scheduleRuntimeStatsWipeAll()
    {
        return [
            'schedule' => [
                'erase_runtime_stat' => null,
            ],
        ];
    }

    /**
     * Get list of all Countdown Rules. Some (most) clients can only show 1 rule.
     *
     * @return array
     */
    public static function countdownRuleList()
    {
        return [
            'count_down' => [
                'get_rules' => new stdClass(),
            ],
        ];
    }

    /**
     * Add New Countdown Rule
     *
     * @param int    $delay  The number of secs until the event should fire.
     * @param bool   $turnOn Should the event turn on or off the timer.
     * @param string $name   An event name. On some clients this isn't even seen.
     *
     * @return array
     */
    public static function countdownRuleCreate($delay, $turnOn, $name = 'countdown')
    {
        return [
            'count_down' => self::countdownCommonData('add_rule', $delay, $turnOn, $name, null),
        ];
    }

    /**
     * @param string $type   The type of action that should be performed, add or edit.
     * @param int    $delay  The number of secs until the event should fire.
     * @param bool   $turnOn Should the event turn on or off the timer.
     * @param string $name   An event name. On some clients this isn't even seen.
     * @param string $ruleId The id of the rule to edit.
     *
     * @return array
     */
    protected static function countdownCommonData($type, $delay, $turnOn, $name, $ruleId)
    {
        return [
            $type => [
                'id'     => $ruleId,
                'enable' => 1,
                'delay'  => (int)$delay,
                'act'    => (int)$turnOn,
                'name'   => $name,
            ],
        ];
    }

    /**
     * Edit Countdown Rule with specified ID
     *
     * @param string $ruleId The id of the rule to edit.
     * @param int    $delay  The number of secs until the event should fire.
     * @param bool   $turnOn Should the event turn on or off the timer.
     * @param string $name   An event name. On some clients this isn't even seen.
     *
     * @return array
     */
    public static function countdownRuleEdit($ruleId, $delay, $turnOn, $name = 'countdown')
    {
        return [
            'count_down' => self::countdownCommonData('edit_rule', $delay, $turnOn, $name, $ruleId),
        ];
    }

    /**
     * Delete the countdown rule with the provided ID.
     *
     * @param string $ruleId
     *
     * @return array
     */
    public static function countdownRuleDelete($ruleId)
    {
        return [
            'count_down' => [
                'delete_rule' => [
                    'id' => $ruleId,
                ],
            ],
        ];
    }

    /**
     * Delete All Countdown Rules
     *
     * @return array
     */
    public static function countdownRuleWipeAll()
    {
        return [
            'count_down' => [
                'delete_all_rules' => null,
            ],
        ];
    }

    /**
     * Get list of all Anti Theft Rules
     *
     * @return array
     */
    public static function antitheftRuleList()
    {
        return [
            'anti_theft' => [
                'get_rules' => new stdClass(),
            ],
        ];
    }

    /**
     * Add New Anti theft Rule
     *
     * @param DateTime $startTime          The start date/time for the event to begin
     * @param DateTime $endTime            The end date/time for the event to finish.
     * @param string   $name               An event name. On some clients this isn't even seen.
     * @param array    $daysOfWeekToRepeat (Optional). Days of week event should repeat. Use EN like Tues, Saturday etc.
     *
     * @return array
     */
    public static function antitheftRuleCreate(DateTime $startTime, DateTime $endTime, $name, $daysOfWeekToRepeat = [])
    {
        $data = self::formatDates($startTime, $daysOfWeekToRepeat);

        return [
            'anti_theft' => self::antitheftCommonData(
                'add_rule',
                $startTime,
                $endTime,
                $name,
                $daysOfWeekToRepeat,
                $data,
                null
            ),
        ];
    }

    /**
     * @param string     $type               The type of action that should be performed, add or edit.
     * @param DateTime   $startTime          The start date/time for the event to begin
     * @param DateTime   $endTime            The end date/time for the event to finish.
     * @param string     $name               An event name. On some clients this isn't even seen.
     * @param array      $daysOfWeekToRepeat (Optional) Day of week event should repeat. Use EN like Tues, Saturday etc.
     * @param Collection $data               specific information depending on if the event is repeating or not.
     * @param string     $ruleId             The ID of the rule to be edited.
     *
     * @return array
     */
    protected static function antitheftCommonData(
        $type,
        DateTime $startTime,
        DateTime $endTime,
        $name,
        $daysOfWeekToRepeat,
        $data,
        $ruleId
    ) {
        return [
            $type                => [
                'id'        => $ruleId,
                'enable'    => 1,
                'frequency' => 5,
                'name'      => "$name",
                'repeat'    => (int)!empty($daysOfWeekToRepeat),
                'smin'      => self::calculateMinutes($startTime),
                'emin'      => self::calculateMinutes($endTime),
                'wday'      => (array)$data->get('wday'),
                'day'       => (int)$data->get('day', 0),
                'month'     => (int)$data->get('month', 0),
                'year'      => (int)$data->get('year', 0),
                'stime_opt' => 0,
                'etime_opt' => 0,
                'duration'  => 2,
                'lastfor'   => 1,
                'force'     => 0,
                'longitude' => 0,
                'latitude'  => 0,
            ],
            'set_overall_enable' => [
                'enable' => 1,
            ],
        ];
    }

    /**
     * Edit Anti theft Rule with given ID
     *
     * @param string   $ruleId             The ID of the rule to be edited.
     * @param DateTime $startTime          The start date/time for the event to begin
     * @param DateTime $endTime            The end date/time for the event to finish.
     * @param string   $name               An event name. On some clients this isn't even seen.
     * @param array    $daysOfWeekToRepeat (Optional). Days of week event should repeat. Use EN like Tues, Saturday etc.
     *
     * @return array
     */
    public static function antitheftRuleEdit(
        $ruleId,
        DateTime $startTime,
        DateTime $endTime,
        $name,
        $daysOfWeekToRepeat = []
    ) {
        $data = self::formatDates($startTime, $daysOfWeekToRepeat);

        return [
            'anti_theft' => self::antitheftCommonData(
                'edit_rule',
                $startTime,
                $endTime,
                $name,
                $daysOfWeekToRepeat,
                $data,
                $ruleId
            ),
        ];
    }

    /**
     * Delete the Anti theft rule with the provided ID.
     *
     * @param string $ruleId
     *
     * @return array
     */
    public static function antitheftRuleDelete($ruleId)
    {
        return [
            'anti_theft' => [
                'delete_rule' => [
                    'id' => $ruleId,
                ],
            ],
        ];
    }

    /**
     * Delete All Anti theft Rules
     *
     * @return array
     */
    public static function antitheftRuleWipeAll()
    {
        return [
            'anti_theft' => [
                'delete_all_rules' => null,
            ],
        ];
    }

    /**
     * @param int    $brightness
     * @param int    $transPeriod
     * @param int    $hue
     * @param int    $saturation
     * @param int    $color_temp
     * @param string $mode
     *
     * @return array
     */
    public static function lightControlValues(
        $brightness = 100,
        $transPeriod = 100,
        $hue = 120,
        $saturation = 150,
        $color_temp = 2700,
        $mode = 'normal'
    ) {
        return [
            "transition_period" => $transPeriod,
            "mode"              => $mode,
            "hue"               => $hue,
            "saturation"        => $saturation,
            "color_temp"        => $color_temp,
            "brightness"        => $brightness,
        ];
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public static function lightOn($params = [])
    {
        $cmd = array_merge(["ignore_default" => 1, "on_off" => 1], $params);

        return [
            'smartlife.iot.smartbulb.lightingservice' => [
                'transition_light_state' => $cmd,
            ],
        ];
    }

    /**
     * @return array
     */
    public static function lightOff()
    {
        return [
            'smartlife.iot.smartbulb.lightingservice' => [
                'transition_light_state' => [
                    "ignore_default" => 1,
                    "on_off"         => 0,
                ],
            ],
        ];
    }

}
