<?php
/**
 * Friday
 * Main Friday class
 *
 * Basically configure itself and run automatically
 * This may get turned into a config variable so
 * we can switch the running of the app on the fly
 */

namespace PhutureProof;

use PhutureProof\Database\PDOAdapter;
use PhutureProof\Utils\Config;
use Slim\App;

class Friday
{
    private $db;
    private $slim;
    private $slimAppConfig = [
        'settings' => [
            'addContentLengthHeader' => false
        ]
    ];

    public function __construct($run = false)
    {
        // load config from file
        $config = Config::loadConfigFromFile(FRIDAY_APP_CONFIGDIR . '/application.ini');

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

        if (($config['config']['autorun'] || $run) && $run) {
            $this->run();
        }
    }

    public function run()
    {
        $this->_loadGroups();
    }

    private function _loadGroups()
    {
        $this->slim->get('', function ($req, $res, $args) {
            /** @var \Slim\Http\Request $res */
            $res->
        });
        $this->slim->group('/api/{token}/{table}', function () {
            $this->map(['GET', 'DELETE', 'PATCH', 'PUT'], '', function ($request, $response, $args) {

            })->setName('user');

            $this->get('/reset-password', function ($request, $response, $args) {
                // Route for /users/{id:[0-9]+}/reset-password
                // Reset the password for user identified by $args['id']
            })->setName('user-password-reset');
        });
    }
}