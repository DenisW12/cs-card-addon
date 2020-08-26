<?php
if (!defined('BOOTSTRAP')) {
    die('Access denied');
}

/**
 * Hook handler: save old and new order status into log table
 *
 * @param int    $order_id    Order identifier
 * @param string $status_to   New order status (one char)
 * @param string $status_from Old order status (one char)
 */
function fn_order_state_history_change_order_status_post($order_id, $status_to, $status_from)
{
    if (!empty(Tygh::$app['session']['auth']['user_id'])) {
        $user_id = Tygh::$app['session']['auth']['user_id'];
    } else {
        $user_id = 0;
    }

    $data = array(
        'user_id' => $user_id,
        'timestamp' => TIME,
        'order_id' => $order_id,
        'status_from' => $status_from,
        'status_to' => $status_to,
    );

    db_query('INSERT INTO ?:order_state_logs ?e', $data);
}

/**
 * Gets the list order state logs.
 *
 * @param array $params         The parameters for search of logs.
 * @param int   $items_per_page Items per page.
 *
 * @return array The logs list and the search params.
 */
function fn_order_state_history_get_logs($params, $items_per_page = 0)
{
    $default_values = array(
        'page' => 1,
        'items_per_page' => $items_per_page,
    );

    $params = array_merge($default_values, $params);

    $fields = array(
        '?:order_state_logs.user_id',
        '?:order_state_logs.timestamp',
        '?:order_state_logs.order_id',
        '?:order_state_logs.status_from',
        '?:order_state_logs.status_to',
        '?:users.firstname',
        '?:users.lastname',
    );
    $join = ' LEFT JOIN ?:users ON ?:users.user_id = ?:order_state_logs.user_id';

    $limit = '';
    if (!empty($params['items_per_page'])) {
        $params['total_items'] = db_get_field(
            'SELECT COUNT(?:order_state_logs.log_id)'
            . ' FROM ?:order_state_logs ?p',
            $join
        );
        $limit = db_paginate($params['page'], $params['items_per_page'], $params['total_items']);
    }

    $fields = implode(', ', $fields);

    $logs = db_get_array(
        'SELECT ?p'
        . ' FROM ?:order_state_logs ?p'
        . ' ORDER BY ?:order_state_logs.log_id DESC ?p',
        $fields,
        $join,
        $limit
    );

    $order_status_descr = fn_get_simple_statuses(STATUSES_ORDER, true, true);

    foreach ($logs as $key => $log) {
        $logs[$key]['status_from_name'] = $log['status_from'] ? $order_status_descr[$log['status_from']] : '';
        $logs[$key]['status_to_name'] = $order_status_descr[$log['status_to']];
    }

    return array($logs, $params);
}
