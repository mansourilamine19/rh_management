<?php

namespace App\Message;

final class SendEmailNotification {
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

    public function __construct(private string $email) {
        
    }

    public function getEmail(): string {
        return $this->email;
    }
}
