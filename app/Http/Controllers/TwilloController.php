<?php

namespace App\Http\Controllers;

use App\Services\TwilloService;

class TwilloController extends Controller
{
    protected TwilloService $twilio;

    public function __construct(TwilloService $twilio)
    {
        $this->twilio = $twilio;
    }

    public function send(): string
    {
        return $this->twilio->sendSMS('+380660685608', 'Another code 92903290');
    }
}
