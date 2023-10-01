<?php

defined('ABSPATH') || exit;

if (!defined('WP_UNINSTALL_PLUGIN')) {
  exit;
}

$option_name = 'see_excluded_ids';
delete_option($option_name);