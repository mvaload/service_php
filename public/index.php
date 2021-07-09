<?php

namespace App;

use function App\response;
use function App\Renderer\render;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';

if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

$opt = array(
    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
);

$pdo = new \PDO('sqlite:db.sqlite', null, null, $opt);
// SQLite не имеет отдельного класса хранения для хранения дат и / или времени, но SQLite способен хранить даты и время как значения TEXT, REAL или INTEGER.
$pdo->exec('create table if not exists products (
    id integer primary key autoincrement,
    name text not null,
    price numeric not null,
    dateTime text not null)');

$repository = new ProductRepository($pdo);

$app = new Application();

$app->get('/', function () use ($repository) {
    $items = $repository->all();
    return response(render('index', ['items' => $items]));
});

$app->get('/items/new', function ($meta, $params, $attributes) {
    return response(render('products/new', ['errors' => [], 'item' => []]));
});

$app->delete('/items/:id', function ($meta, $params, $attributes) use ($repository) {
    $repository->delete($attributes['id']);
    return response()->redirect('/');
});

$app->post('/items', function ($meta, $params, $attributes) use ($repository) {
    $item = $params['item'];

    $validator = new Validator();
    $errors = $validator->validate($item);

    if (empty($errors)) {
        $repository->insert($item);
        return response()->redirect('/');
    } else {
        return response(render('products/new', ['item' => $item, 'errors' => $errors]))
            ->withStatus(422);
    }
});

$app->run();
