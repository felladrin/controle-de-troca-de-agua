<?php
require_once 'Slim/Slim.php';
require_once 'NotORM.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

define('ROOT', $app->request->getRootUri());
define('DATABASE', 'sqlite'); // Valores aceitos: 'sqlite', 'mysql'.

if (DATABASE == 'sqlite')
{
    $pdo = new PDO("sqlite:database/database.sqlite");
}
else if (DATABASE == 'mysql')
{
    // Obs: Se você prefere utilizar MySQL, favor executar os comandos SQL do arquivo criar_tabelas_mysql.sql, que se encontra na pasta 'database'.
    $dbhost = 'localhost';
    $dbname = 'xxxxxxxxx';
    $dbuser = 'xxxxxxxxx';
    $dbpass = 'xxxxxxxxx';
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $pdo->exec("set names utf8");
}
else
{
    die('Favor configurar o banco de dados a ser utilizado.');
}

$db = new NotORM($pdo);

$app->get('/', function () use ($app, $db) {
    $app->render('index.php', array(
        'pessoas' => $db->pessoa()->order('nome'),
        'historico' => $db->historico()->order('horario_troca DESC')
    ));
});

$app->post('/registrar/troca/', function () use ($app, $db) {
    if (is_numeric($_POST['id']))
    {
        $pessoa_id = $_POST['id'];
        $historico = array(
            'pessoa_id' => $pessoa_id,
            'horario_troca' => time(),
        );
        $db->historico()->insert($historico);
        $pessoa = $db->pessoa[$pessoa_id];
        $pessoa['trocas'] += 1;
        $pessoa->update();
    }
    $app->redirect(ROOT);
});

$app->post('/registrar/pessoa/', function () use ($app, $db) {
    if (!empty($_POST['nome']))
    {
        $pessoa = array('nome' => ucwords(strtolower($_POST['nome'])));
        $db->pessoa()->insert($pessoa);
    }
    $app->redirect(ROOT);
});

$app->post('/remover/pessoa/', function () use ($app, $db) {
    if (is_numeric($_POST['id']))
    {
        $pessoa_id = $_POST['id'];
        $db->pessoa[$pessoa_id]->delete();
        $db->historico('pessoa_id = ?', $pessoa_id)->delete();
    }
    $app->redirect(ROOT);
});

$app->get('/sortear', function () use ($db) {
    $random = (DATABASE == 'sqlite') ? 'RANDOM()' : 'RAND()';
    $pessoa = $db->pessoa()->select("nome")->order("trocas, $random")->limit(1)->fetch();
    echo $pessoa['nome'];
});

$app->run();
