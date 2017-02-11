<?php

use AkniCallback\Model\Callback;
use AkniCallback\Controller\PageView;
global $wp;

$pluginDir = '';
$pageView =  PageView::getInstance($pluginDir);
$callback =  Callback::getInstance($pluginDir);
if ($_POST['update']) {
    if($_POST['id'] && $_POST['status']) {
        $callback->updateStatus((int)$_POST['id'], (int)$_POST['status']);
    }
} elseif($_POST['delete']) {
    if($_POST['id']) {
        $callback->deleteCallback((int)$_POST['id']);
    }
}

$context = [];
$context['msgs'] = [
  'empty_text' => __('You have no callbacks yet')
];

$context['labels'] = [
    'id'      => __('ID'),
    'name'    => __('Name'),
    'phone'   => __('Phone'),
    'email'   => __('Email'),
    'comment' => __('Comment'),
    'company' => __('Company'),
    'status'  => __('Status'),
    'info'    => __('Info'),
    'date'    => __('Date'),
    'update'  => __('Update'),
    'delete'  => __('Delete')
];
$context['statuses'] = [
    __('New'),
    __('Ok'),
    __('Processed'),
    __('Rejected')
];
$context['current_url'] = home_url() . $_SERVER['REQUEST_URI'];

$types = $callback->getCallbackTypes();
if (!empty($types)) {
    $context['types'] = $types;

    foreach ($context['types'] as &$type) {
        $type['content'] = $callback->getCallbackContentByType($type['type']);
    }

    $pageView->display('callback.twig', $context);
} else {
    echo "<h3>{$context['empty_text']}</h3>";
}

