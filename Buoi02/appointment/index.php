<?php

require_once "config/database.php";
require_once "models/Appointment.php";
require_once "controllers/AppointmentController.php";

$model = new Appointment($pdo);

$controller = new AppointmentController($model);

$action = $_GET['action'] ?? 'index';

if (
    $action == 'store'
    && $_SERVER['REQUEST_METHOD'] == 'POST'
) {

    $controller->store();

} else {

    $controller->index();

}