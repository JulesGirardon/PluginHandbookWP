<?php

/*
 * Jules
 *
 * @author              Jules
 *
 * @wordpress-plugin
 * Plugin Name:         Jules
 * Description:         I learn with this plugin
 * Version:             1.0
 * Requires PHP:        5.6
 * Author:              Jules
 *
 */

use Jules\Plugin\PluginTest;

if (!defined('ABSPATH')) exit;

define('JULES_PLUGIN_DIR', plugin_dir_path(__FILE__));

require JULES_PLUGIN_DIR . 'vendor/autoload.php';

$plugin = new PluginTest(__FILE__);