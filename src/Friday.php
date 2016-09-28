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
    private function _setupSlimRoutes()
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
        // group everything together too
        $this->slim->group('/api', function () use ($db, $config) {
            /** @var App $this */

            // api-index
            // also setName to api-index
            $this->get('', function (Request $req, Response $res, $args) use ($db, $config) {
                $res->getBody()->write(Views::load('api'));

                return $res;
            })->setName('api-index');

            // specifically handle /token/request
            // and setName token-request
            $this->get('/token/request', function (Request $req, Response $res, $args) use ($db, $config) {

                /**
                 * TODO: Implement good token generation here
                 */
                $index = $config['authentication']['sessionTokenName'];
                if ( ! isset($_SESSION[$index])) {
                    $_SESSION[$index] = sha1(time());
                }

                return $res->withJson([
                    'status' => 'success',
                    'token'  => $_SESSION[$index],
                ]);
            });

            // wildcard matching for any token and any data table
            // /token/anything
            $this->get('/{token}/{table}', function (Request $req, Response $res, $args) use ($db, $config) {

                $token = $args['token'];
                $table = $args['table'];

                if ($config['authentication']['useAuthentication']) {
                    $sessionName = $config['authentication']['sessionTokenName'];
                    if ( ! isset($_SESSION[$sessionName])) {
                        return $res->withJson([
                            'status'  => 'error',
                            'message' => 'missing token',
                        ]);
                    }
                    if (isset($_SESSION[$sessionName]) && ($_SESSION[$sessionName] !== $token)) {
                        return $res->withJson([
                            'status'  => 'error',
                            'message' => 'invalid token',
                        ]);
                    }
                }

                if ($config['authentication']['useWhitelist']) {
                    if (in_array($table, $config['database']['whitelist'])) {
                        $table   = FRIDAY_DB_PREFIX . $args['table'];
                        $sql     = "SELECT * FROM `{$table}`";
                        $result  = $db->runPrepared($sql, true);
                        $numRows = count($result);

                        return $res->withJson([
                            'status'    => 'success',
                            'totalRows' => $numRows,
                            'results'   => $result,
                        ]);
                    }
                }

                return $res;
            });
            // end of the get /token/table route

        });
        // end of the /api group
    }

    private function _runSlim()
    {
        $this->slim->run();
    }
}
