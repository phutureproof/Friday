<?php
/**
 * Friday
 * Concept by Dale Paget
 *
 * ::concept::
 * Easy ajax api access
 * for database backend, supporting authentication
 *
 * ::composer::
 * I've used composer and a few libraries to save time
 * http://www.slimframework.com/ :: slim php framework
 * http://www.appelsiini.net/projects/slim-jwt-auth :: slim json web token middleware
 *
 */

// application setup
require_once("bootstrap.php");

// database connection
$db = new PhutureProof\Database\PDOAdapter('mysql', 'localhost', 'root', '', 'friday');

// slim application config
$slimAppConfig = [
    'settings' => [
        'addContentLengthHeader' => false
    ]
];

// create slim application and pass in the config array
$app = new Slim\App($slimAppConfig);

$app->group('/api', function () use ($db) {

    $this->get('', function ($request, $response, $args) use ($db) {
        require('./views/api.php');
    });

    $this->get('/users', function ($request, $response, $args) use ($db) {
        $result = $db->runPrepared('SELECT * FROM users', true);
        return json_encode($result);
    });

    $this->get('/products', function ($request, $response, $args) use ($db) {
        $result = $db->runPrepared('SELECT * FROM products', true);
        echo json_encode($result);
    });

    $this->post('/products', function ($request, $response, $args) use ($db) {
        if (isset($_POST['name'], $_POST['color'], $_POST['price'])) {
            $statement = $db->prepare("INSERT INTO products (name, color, price) VALUES (?, ?, ?)");
            $statement->execute([$_POST['name'], $_POST['color'], $_POST['price']]);
            echo json_encode(['status' => 'success', 'message' => 'product record saved']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'not all data supplied']);
        }
    });
});

$app->run();

