<?php
/**
 * Plugin Name:      Query Monitor - RediPress extension
 * Description:      Query Monitor extension for RediPress.
 * Requires Plugins: query-monitor, redipress
 * Version:          1.0.1
 * Author:           Hion Digital
 * Author URI:       https://www.hiondigital.com/
 * Text Domain:      query-monitor-redipress
 * Domain Path:      /languages
 */

use Hion\QMRediPress\Plugin;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
    require_once __DIR__ . '/vendor/autoload.php';
}

Plugin::init();
