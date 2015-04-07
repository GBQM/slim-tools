<?php
namespace Neochic\SlimTools\Core;

use \Slim\Slim;

class App
{
    protected $slim;
    protected $container;

    function __construct(array $config)
    {
        $this->slim = new Slim(array(
            'log.enabled' => $config['log.enabled'],
            'log.level' => constant('\Slim\Log::' . strtoupper($config['log.level'])),
            'debug' => false
        ));

        switch ($config['mode']) {
            case "dev":
                error_reporting(E_ALL);
                ini_set("display_errors", 1);
                $this->slim->config('debug', true);
                break;
            default:
                error_reporting(E_ALL ^ E_NOTICE);
                ini_set("display_errors", 0);
        }
    }

    function run()
    {
        $this->container->get('router.rest')->route();
        $this->slim->run();
    }

    function setContainer($container)
    {
        $this->container = $container;
    }

    function getContainer()
    {
        return $this->container;
    }
}