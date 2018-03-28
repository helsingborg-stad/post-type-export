<?php

/**
 * Plugin Name:       Post type export
 * Plugin URI:        (#plugin_url#)
 * Description:       Plugin to export post types as CSV.
 * Version:           1.0.0
 * Author:            Jonatan Hanson
 * Author URI:        (#plugin_author_url#)
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       post-type-export
 * Domain Path:       /languages
 */

 // Protect agains direct file access
if (! defined('WPINC')) {
    die;
}

define('POSTTYPEEXPORT_PATH', plugin_dir_path(__FILE__));
define('POSTTYPEEXPORT_URL', plugins_url('', __FILE__));
define('POSTTYPEEXPORT_TEMPLATE_PATH', POSTTYPEEXPORT_PATH . 'templates/');

load_plugin_textdomain('post-type-export', false, plugin_basename(dirname(__FILE__)) . '/languages');

require_once POSTTYPEEXPORT_PATH . 'source/php/Vendor/Psr4ClassLoader.php';
require_once POSTTYPEEXPORT_PATH . 'Public.php';

// Instantiate and register the autoloader
$loader = new PostTypeExport\Vendor\Psr4ClassLoader();
$loader->addPrefix('PostTypeExport', POSTTYPEEXPORT_PATH);
$loader->addPrefix('PostTypeExport', POSTTYPEEXPORT_PATH . 'source/php/');
$loader->register();

// Start application
new PostTypeExport\App();
