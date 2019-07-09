<?php

namespace Williamson\TPLinkSmartplug;

use UnexpectedValueException;

class TPLinkDevice
{

    protected $config;
    protected $deviceName;
    protected $client;
    protected $encryptionKey;


    /**
     * TPLinkDevice constructor.
     *
     * @param array $config
     * @param string $deviceName
     * @param int $encryptionKey
     */
    public function __construct(array $config, $deviceName, $encryptionKey = 171)
    {
        $this->config = $config;
        $this->deviceName = $deviceName;
        $this->encryptionKey = $encryptionKey;
    }

    /**
     * Return current power status
     *
     * @return boolean
     */
    public function powerStatus()
    {
        return (bool)json_decode($this->sendCommand(TPLinkCommand::systemInfo()))->system->get_sysinfo->relay_state;
    }

    /**
     * Toggle the current status of the switch on/off
     *
     * @return string
     */
    public function togglePower()
    {
        return $this->powerStatus() ? $this->sendCommand(TPLinkCommand::powerOff()) : $this->sendCommand(TPLinkCommand::powerOn());
    }

    /**
     * Change the current status of the switch to on
     *
     * @return string
     */
    public function powerOn()
    {
        return $this->sendCommand(TPLinkCommand::powerOn());
    }

    /**
     * Change the current status of the switch off
     *
     * @return string
     */
    public function powerOff()
    {
        return $this->sendCommand(TPLinkCommand::powerOff());
    }

    /**
     * Send a command to the connected device.
     *
     * @param array $command
     *
     * @return mixed|string
     */
    public function sendCommand(array $command)
    {
        $this->connectToDevice();

        if (fwrite($this->client, $this->encrypt(json_encode($command))) === false) {
            return $this->connectionError();
        }

        $response = $this->decrypt(stream_get_contents($this->client));

        $this->disconnect();

        return $response;
    }

    /**
     * Connect to the specified device
     */
    protected function connectToDevice()
    {
        $this->client = stream_socket_client(
            "tcp://" . $this->getConfig("ip") . ":" . $this->getConfig("port"),
            $errorNumber,
            $errorMessage,
            $this->getConfig('timeout', 5)
        );

        // Set stream timeout (important or some devices will cause the stream read function to hang for a period)
        stream_set_timeout($this->client, $this->getConfig('timeout_stream', 1));

        if ($this->client === false) {
            throw new UnexpectedValueException("Failed connect to {$this->deviceName}: $errorMessage ($errorNumber)");
        }
    }

    /**
     * @param      $key
     * @param null $default
     *
     * @return mixed
     */
    public function getConfig($key, $default = null)
    {
        if (is_array($this->config) && isset($this->config[$key])) {
            return $this->config[$key];
        }

        return $default;
    }

    /**
     * Encrypt all data being sent to the device
     *
     * @param $string
     *
     * @return mixed
     */
    protected function encrypt($string)
    {
        $key = $this->encryptionKey;

        return collect(str_split($string))
            ->reduce(
                function ($result, $character) use (&$key) {
                    $key = ord($character) ^ $key;

                    return $result .= chr($key);
                },
                strrev(pack('I', strlen($string)))
            );
    }

    /**
     *
     * @return string
     */
    protected function connectionError()
    {
        return json_encode([
            'success' => false,
            'message' => "{$this->deviceName} : connection terminated before the command was sent.",
        ]);
    }

    /**
     * Decrypt the response from the device.
     *
     * Must ignore the first 4 bytes of the response to decrypt properly.
     *
     * @param $data
     *
     * @param bool $stripHeader
     *
     * @return mixed
     */
    protected function decrypt($data, $stripHeader = true)
    {
        $key = $this->encryptionKey;

        return collect(str_split(substr($data, ($stripHeader) ? 4 : 0)))
            ->reduce(function ($result, $character) use (&$key) {
                $a = ord($character) ^ $key;
                $key = ord($character);

                return $result .= chr($a);
            });
    }

    /**
     * Disconnect from the device
     */
    protected function disconnect()
    {
        if (isset($this->client) && is_resource($this->client)) {
            fclose($this->client);
        }
    }
}
