<?php
/**
 * Friday
 * Main Friday class
 */

namespace PhutureProof;

use PhutureProof\Database\PDOAdapter;
use PhutureProof\Utils\Config;
use PhutureProof\Utils\Views;
use Slim\App;

class Friday
{
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
        $config = Config::loadConfigFromFile(FRIDAY_APPLICATION_CONFIG);

        // shorthand, too much typing
        $dbConfig = $config['database'];

        // load our database adapter
        /** @var PDOAdapter db */
        $this->db = new PDOAdapter(
            $dbConfig['driver'],
            $dbConfig['hostname'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['database']
        );

        /** @var App slim */
        $this->slim = new App($this->slimAppConfig);
    }

    public function run()
    {
        $this->_loadGroups();
    }

    private function _loadGroups()
    {
        // handle url.com/api/somesecrettoken/tablename
        $this->slim->group('/api/{token}/{table}', function () {


        });
        $this->slim->get('', function ($req, $res, $args) {
            echo "Hello";
            /** @var \Slim\Http\Response $res */
            $res->getBody()->write(Views::load('api'));

            return $res;
        });
    }
}