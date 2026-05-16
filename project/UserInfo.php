<?php
class UserInfo {
    public static function getInfo(): array {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '';
        
        return [
            'ip' => $ip,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Неизвестно',
            'browser_lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Неизвестно',
            'time' => date('Y-m-d H:i:s'),
            'session_id' => session_id()
        ];
    }
    
    public static function getBrowserInfo(): string {
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        if (strpos($userAgent, 'Chrome') !== false) return 'Google Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Mozilla Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'Edge') !== false) return 'Microsoft Edge';
        return 'Неизвестный браузер';
    }
}