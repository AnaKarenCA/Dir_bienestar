<?php

require_once APPROOT . '/controllers/AuthController.php';

class App
{
    protected $controller = 'AuthController';
    protected $method = 'login';
    protected $params = [];

    public function __construct()
    {
        $url = $this->getUrl();

        if(isset($url[0]))
        {
            switch(strtolower($url[0]))
            {
                case 'auth':
                    require_once APPROOT . '/controllers/AuthController.php';
                    $this->controller = 'AuthController';
                    break;

                case 'dashboard':
                    require_once APPROOT . '/controllers/DashboardController.php';
                    $this->controller = 'DashboardController';
                    break;
                    case 'actividad':
    require_once APPROOT . '/controllers/ActividadController.php';
    $this->controller = 'ActividadController';
    break;
            }
        }

        $controller = new $this->controller();

        if (
    isset($url[1]) &&
    method_exists($controller, $url[1])
)
{
    $this->method = $url[1];
    unset($url[0]);
unset($url[1]);

$this->params = $url
    ? array_values($url)
    : [];
}


call_user_func_array(
    [$controller, $this->method],
    $this->params
);
    }

    private function getUrl()
{
    if(isset($_GET['url']))
    {
        $url = filter_var(
            rtrim($_GET['url'], '/'),
            FILTER_SANITIZE_URL
        );

        $url = explode('/', $url);

        if (
            isset($url[0]) &&
            strtolower($url[0]) === 'dir_bienestar'
        ) {
            array_shift($url);
        }

        return $url;
    }

    return [];
}
}