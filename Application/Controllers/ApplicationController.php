<?php

namespace Application\Controllers;

use Bramus\Router\Router;
use Application\Utils\MySQL;
use Exception;
use Predis\Client;

class ApplicationController extends BaseController
{

    public function Start()
    {

        date_default_timezone_set('Europe/Moscow');


        session_start([
            'cookie_lifetime' => 86400,
        ]);

        MySQL::$db = new \PDO(
            "mysql:dbname=booksdb;host=127.0.0.1;charset=utf8",
            "root",
            ""
        );

        $router = new Router();

        $routes = include_once '../Application/Models/AdminRoutes.php';

        $router->setNamespace('Application\\Controllers');

        $router->set404(function () {

            try {

                $template = $this->twig->load('ErrorPages/404-not-found.twig');
                echo $template->render();

            }
            catch (\Exception $ex) {

            }

        });

        foreach ($routes as $key => $path) {

            foreach ($path as $subKey => $value) {

                $router->before('GET|POST|DELETE|PUT', $subKey, function () {

                    if (!isset($_SESSION['admin']) && !isset($_COOKIE['admin'])) {
                        header('location: /BookShopMVC/public/home');
                    }

                });

                $router->$key($subKey, $value);

            }

        }


        $routes = include_once '../Application/Models/PublicRoutes.php';

        foreach ($routes as $key => $path) {

            foreach ($path as $subKey => $value) {

                $router->$key($subKey, $value);

            }

        }

        $router->run();

       

    }

    public function LogoutAction()
    {

        if (isset($_SESSION['session_user'])) {

            $_SESSION = array();

        }

        if (isset($_SESSION['admin'])) {

            $_SESSION = array();

        }

        if (isset($_COOKIE['cookie_user'])) {

            unset($_COOKIE['cookie_user']);
            setcookie("cookie_user", "", 1);

        }

        if (isset($_COOKIE['admin'])) {

            unset($_COOKIE['admin']);
            setcookie("admin", "", 1);
        }

        $this->json(200, array());

    }

}