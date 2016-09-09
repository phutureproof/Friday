<?php
/**
 * Friday
 * Main Friday class
 *
 * This is simply the flow of the application wrapped up into a class.
 * It may turn out to be not such a smart idea, we'll see down the road..
 */

namespace PhutureProof;

use PhutureProof\Database\PDOAdapter as PDOAdapter;
use PhutureProof\Utils\Config as Config;
use PhutureProof\Utils\Views as Views;
use Slim\App as App;
use Slim\Http\Request;
use Slim\Http\Response;

class Friday
{
    private $config = [];
    private $db;
    private $slim;
    private $formBuilder;

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
        $this->slim = new App($this->config['slim']);

        defined('FRIDAY_DB_PREFIX') or define('FRIDAY_DB_PREFIX', $this->config['database']['tablePrefix']);
    }

    public function run()
    {
        $this->_setupSlimRoutes();
        $this->_runSlim();
    }

    /**
     * _setupSlimRoutes
     *
     * As the name suggest this will assign all of our api routes
     */
    public function _setupSlimRoutes()
    {
        // need to pass these on later as the scope changes
        $db     = $this->db;
        $config = $this->config;

        // handle /
        // also setName to home
        $this->slim->get('/', function (Request $req, Response $res, $args) use ($db, $config) {
            $res->getBody()->write(Views::load('home'));

            return $res;
        })->setName('home');

        // handle /api
        $this->slim->group('/api', function () use ($db, $config) {
            /** @var App $this */

            // index
            // also setName to api-index
            $this->get('', function (Request $req, Response $res, $args) use ($db, $config) {
                $res->getBody()->write(Views::load('api'));

                return $res;
            })->setName('api-index');

            // /token/table
            $this->get('/{token}/{table}', function (Request $req, Response $res, $args) use ($db, $config) {

                $token = $args['token'];
                $table = $args['table'];

                if($config['authentication']['useAuthentication']) {
                    // TODO: Implement token validation
                    // for now just continue onwards
                }

                if ($config['authentication']['useWhitelist']) {
                    if (in_array($table, $config['database']['whitelist'])) {
                        $table = FRIDAY_DB_PREFIX . $args['table'];
                        $sql = "SELECT * FROM `{$table}`";
                        $result = $db->runPrepared($sql, true);
                        return $res->withJson($result);
                    }
                }

                return $res;
            });
        });
    }

    private function _runSlim()
    {
        $this->slim->run();
    }
}
