<?php
namespace controllers;

use DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

class ImpiantoController {
    private $app;

    public function __construct(App $app) {
        $this->app = $app;
        $this->registerRoutes();
    }

    private function registerRoutes() {
        $this->app->get('/impianto/{id}', [$this, 'getImpianto']);
    }

    public function getImpianto(Request $request, Response $response, $args) {
        $id = $args['id'];
        $sql = "SELECT * FROM Impianto WHERE id = ?";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $impianto = $result->fetch_assoc();

        $response->getBody()->write(json_encode($impianto));
        return $response->withHeader('Content-Type', 'application/json');
    }
}
