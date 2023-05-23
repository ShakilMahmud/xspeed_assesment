<?php
include_once __DIR__ . '/Helper.php';

$helper = new Helper();

if (!isset($_GET['req'])) {
    echo $helper->landing();
    exit;
}

$route = $_GET['req'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    switch ($route) {
        case 'data_entry_handler':
            $helper->data_entry_handler_submit();
            break;
        default:
            echo json_encode("Urls not found");
            break;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    switch ($route) {
        case 'data_entry':
            echo $helper->data_insert();
            break;
        case 'data_report':
            echo $helper->data_report();
            break;
        default:
            echo $helper->landing();
            break;
    }
}
