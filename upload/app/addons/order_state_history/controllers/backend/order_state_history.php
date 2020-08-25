<?php

/** @var string $mode */

use Tygh\Registry;

if (!defined('BOOTSTRAP')) { die('Access denied'); }

$params = $_REQUEST;

if ($mode == 'manage') {

    list($logs, $search) = fn_order_state_history_get_logs($params, Registry::get('settings.Appearance.admin_elements_per_page'));

    Tygh::$app['view']->assign('logs', $logs);
    Tygh::$app['view']->assign('search', $search);

}
