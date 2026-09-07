<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class UserAgents extends BaseConfig
{
    public array $platforms = [
        'windows nt 10.0' => 'Windows 10',
        'windows nt 6.3' => 'Windows 8.1',
        'windows nt 6.2' => 'Windows 8',
        'windows nt 6.1' => 'Windows 7',
        'windows nt 6.0' => 'Windows Vista',
        'windows nt 5.1' => 'Windows XP',
        'windows' => 'Unknown Windows OS',
        'android' => 'Android',
        'iphone' => 'iOS',
        'ipad' => 'iOS',
        'ipod' => 'iOS',
        'os x' => 'Mac OS X',
        'linux' => 'Linux',
        'ubuntu' => 'Ubuntu',
        'debian' => 'Debian',
        'freebsd' => 'FreeBSD',
        'sunos' => 'Sun Solaris',
        'unix' => 'Unknown Unix OS',
    ];

    public array $browsers = [
        'OPR' => 'Opera',
        'Edg' => 'Edge',
        'Edge' => 'Spartan',
        'Chrome' => 'Chrome',
        'Firefox' => 'Firefox',
        'Safari' => 'Safari',
        'MSIE' => 'Internet Explorer',
        'Trident.* rv' => 'Internet Explorer',
        'Opera' => 'Opera',
        'Vivaldi' => 'Vivaldi',
        'Mozilla' => 'Mozilla',
    ];

    public array $mobiles = [
        'mobileexplorer' => 'Mobile Explorer',
        'iphone' => 'Apple iPhone',
        'ipad' => 'iPad',
        'ipod' => 'Apple iPod Touch',
        'android' => 'Android',
        'blackberry' => 'BlackBerry',
        'windows phone' => 'Windows Phone',
        'samsung' => 'Samsung',
        'nokia' => 'Nokia',
        'motorola' => 'Motorola',
        'htc' => 'HTC',
        'lg' => 'LG',
        'mobile' => 'Generic Mobile',
        'wireless' => 'Generic Mobile',
        'smartphone' => 'Generic Mobile',
    ];

    public array $robots = [
        'googlebot' => 'Googlebot',
        'bingbot' => 'Bing',
        'msnbot' => 'MSNBot',
        'baiduspider' => 'Baiduspider',
        'yandex' => 'YandexBot',
        'slurp' => 'Inktomi Slurp',
        'duckduckbot' => 'DuckDuckBot',
        'bot' => 'Generic Bot',
        'crawler' => 'Generic Crawler',
        'spider' => 'Generic Spider',
    ];
}
