<?php

namespace App\Helpers;

use DB;

class MailConfigHelper
{
    public static function loadDynamicMailConfig()
    {
        $settings = DB::table('settings')->first();

        if (!$settings) {
            return;
        }

        config([
            'mail.mailers.smtp.transport'  => 'smtp',
            'mail.mailers.smtp.host'       => $settings->smtp_host ?? 'localhost',
            'mail.mailers.smtp.port'       => $settings->smtp_port ?? 587,
            'mail.mailers.smtp.username'   => $settings->smtp_username ?? null,
            'mail.mailers.smtp.password'   => $settings->smtp_password ?? null,
            'mail.mailers.smtp.encryption' => $settings->smtp_encryption ?? 'tls',
            'mail.from.address'            => $settings->smtp_from_address ?? $settings->smtp_username,
            'mail.from.name'               => $settings->smtp_from_name ?? config('app.name'),
        ]);
    }
}

