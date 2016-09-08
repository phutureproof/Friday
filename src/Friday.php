<?php
/**
 * Friday
 * Main Friday class
 *
 * This is simply the flow of the application wrapped up into a class.
 * It may turn out to be not such a smart idea, we'll see down the road..
 *
 * TODO: Implement total rewrite when I've decided it's a bad idea!
 */

namespace PhutureProof;

use PhutureProof\Database\PDOAdapter as PDOAdapter;
use PhutureProof\Utils\Config as Config;
use PhutureProof\Utils\Views as Views;
use Slim\App as App;

class Friday
{
    private $config = [];
    private $db;
    private $slim;
    private $slimAppConfig = [
        'settings' => [
            'addContentLengthHeader' => false,
            'displayErrorDetails'    => true,
        ],
    ];

    public function __construct()
    {
        // load config from file
        $this->config = Config::loadConfigFromFile(FRIDAY_APPLICATION_CONFIG);

        // load our database adapter
        /** @var PDOAdapter db */
        $this->db = new PDOAdapter(
            $this->config['database']['driver'],
            $this->config['database']['hostname'],
            $this->config['database']['username'],
            $this->config['database']['password'],
            $this->config['database']['database']
        );

        /** @var App slim */
        $this->slim = new App($this->slimAppConfig);
    }

    public function run()
    {
        $this->_setupSlimRoutes();
        $this->_runSlim();
    }

    public function _setupSlimRoutes()
    {
        // handle home page
        $this->slim->get('/', function ($req, $res, $args) {
            $res->getBody()->write(Views::load('home'));

            return $res;
        })->setName('home');

        // handle /api
        $this->slim->get('/api', function ($req, $res, $args) {
            $res->getBody()->write(Views::load('api'));
            return $res;
        })->setName('api-frontend');


    }

    private function _runSlim()
    {
        $this->slim->run();
    }
}
