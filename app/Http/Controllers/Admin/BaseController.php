<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController as GuestBaseController;

/**
 * Базовый контроллер для всех контроллеров управления
 * блогом в панели администрирования.
 *
 * Должен быть родителём всех контроллеров управления блогом.
 *
 * @package App\Http\Controllers\Blog\Admin
 */
abstract class BaseController extends GuestBaseController
{
    /**
     * BaseController constructor
     */
    public function __construct()
    {
    }
}
