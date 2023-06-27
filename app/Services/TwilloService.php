<?php

namespace App\Services;

use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Api\V2010\Account\MessageInstance;
use Twilio\Rest\Client;

class TwilloService
{
    protected Client $client;

    /**
     * @throws ConfigurationException
     */
    public function __construct()
    {
        $this->client = new Client(env('TWILIO_SID'), env('TWILIO_TOKEN'));
    }

    public function sendSMS($to, $message): MessageInstance
    {
        return $this->client->messages->create(
            $to,
            [
                'from' => env('TWILIO_FROM'),
                'body' => $message
            ]
        );
    }
}
