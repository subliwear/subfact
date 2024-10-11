<?php

namespace App\SMS;

use App\Helpers\Helpers;
use App\Http\Traits\MessageTrait;
use App\Notifications\VendorRegisterNotification;

class VendorRegisterSMS
{
      use MessageTrait;

      /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
      public function send($notifiable, VendorRegisterNotification $vendorRegNotification)
      {
            return $vendorRegNotification->toSend($notifiable);
      }

      public function isEnabled()
      {
            $settings = Helpers::getSettings();
            return ($settings['activation']['send_sms'] && $settings['sms_methods']['config']['vendor_register_sms']);
      }

      public function sendSMS($notifiable, $store)
      {
            if ($this->isEnabled()) {
                  // for admin
                  $message = [
                        'to' =>'+'.$notifiable->country_code.$notifiable->phone,
                        'body' => "Exciting News! A new vendor, {$store->store_name}, has joined our website. Discover their incredible products and deals today. Also, stay tuned for updates on recent check request approvals and rejections."
                  ];
                  return $this->sendMessage($message,Helpers::getDefaultSMSMethod());
            }
      }
}
