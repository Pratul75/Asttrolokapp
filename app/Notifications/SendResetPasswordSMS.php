<?php

namespace App\Notifications;

use App\Channels\TwilioSMSChannel;
use Illuminate\Notifications\Notification;

class SendResetPasswordSMS extends Notification
{
    private $user;
    private $pass;

    /**
     * Create a new notification instance.
     *
     * @param $user
     */
    public function __construct($user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $user
     * @return array
     */
    public function via($user)
    {
        return [TwilioSMSChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $user
     */
    public function toTwilioSMS($user)
    {
        $generalSettings = getGeneralSettings();
        $siteName = $generalSettings['site_name'] ?? '';

        $content = trans('update.your_new_password') . ': ' . $this->pass;
        $content .= PHP_EOL;
        $content .= trans('update.your_new_password_on_the_site', ['site' => $siteName]);

        return [
            'to' => $user->mobile,
            'content' => $content,
        ];
    }
}
