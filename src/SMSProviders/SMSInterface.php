<?php

interface SMSInterface
{
    public function send($message, $recipientNumbers, $smsNumber = null);
    public function credit();
    public function checkMessage();
}
