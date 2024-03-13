<?php

namespace controllers;

use DB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;

class RilevatoreController {
    private $app;

    private $type;

    public function __construct(App $app, $type) {
        $this->app = $app;
        $this->type = $type;
        $this->registerRoutes();
    }

    private function registerRoutes() {
        $this->app->get('/rilevatori_di_' . $this->type, [$this, 'getAll']);
        $this->app->get('/rilevatori_di_' . $this->type . '/{id}', [$this, 'getById']);
        $this->app->get('/rilevatori_di_' . $this->type . '/{id}/misurazioni', [$this, 'getMisurazioni']);
        $this->app->get('/rilevatori_di_' . $this->type . '/{id}/maggiore_di/{valore_minimo}', [$this, 'getMisurazioniMaggioreDi']);
        $this->app->post('/rilevatori_di_' . $this->type, [$this, 'create']);
        $this->app->put('/rilevatori_di_' . $this->type . '/{id}', [$this, 'update']);
        $this->app->delete('/rilevatori_di_' . $this->type . '/{id}', [$this, 'delete']);
    }

    public function getAll(Request $request, Response $response, $args) {
        $sql = "SELECT * FROM Rilevatore WHERE tipo = ?";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bind_param("s", $this->type);
        $stmt->execute();
        $result = $stmt->get_result();
        $rilevatori = $result->fetch_all(MYSQLI_ASSOC);

        $response->getBody()->write(json_encode($rilevatori));
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getById(Request $request, Response $response, $args) {
        $id = $args['id'];
        $sql = "SELECT * FROM Rilevatore WHERE id = ? AND tipo = ?";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bind_param("is", $id, $this->type);
        $stmt->execute();
        $result = $stmt->get_result();
        $rilevatore = $result->fetch_assoc();

        if ($rilevatore) {
            $response->getBody()->write(json_encode($rilevatore));
        } else {
            $response->getBody()->write(json_encode(['message' => 'Rilevatore not found']));
            return $response->withStatus(404);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getMisurazioni(Request $request, Response $response, $args) {
        $id = $args['id'];
        $sql = "SELECT * FROM Misurazione WHERE rilevatore_id = ?";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $misurazioni = $result->fetch_all(MYSQLI_ASSOC);

        if ($misurazioni) {
            $response->getBody()->write(json_encode($misurazioni));
        } else {
            $response->getBody()->write(json_encode(['message' => 'No measurements found for this detector']));
            return $response->withStatus(404);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function getMisurazioniMaggioreDi(Request $request, Response $response, $args) {
        $id = $args['id'];
        $valore_minimo = $args['valore_minimo'];
        $sql = "SELECT * FROM Misurazione WHERE rilevatore_id = ? AND valore > ?";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bind_param("ii", $id, $valore_minimo);
        $stmt->execute();
        $result = $stmt->get_result();
        $misurazioni = $result->fetch_all(MYSQLI_ASSOC);

        if ($misurazioni) {
            $response->getBody()->write(json_encode($misurazioni));
        } else {
            $response->getBody()->write(json_encode(['message' => 'No measurements found for this detector greater than specified value']));
            return $response->withStatus(404);
        }

        return $response->withHeader('Content-Type', 'application/json');
    }

    public function create(Request $request, Response $response, $args) {
        $data = $request->getParsedBody();
        $sql = "INSERT INTO Rilevatore (tipo, unitaDiMisura, codiceSeriale, posizione, impianto_id) VALUES (?, ?, ?, ?, ?)";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bind_param("ssssi", $this->type, $data['unitaDiMisura'], $data['codiceSeriale'], $data['posizione'], $data['impianto_id']);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response->getBody()->write(json_encode(['message' => 'Humidity detector created successfully']));
            return $response->withStatus(201);
        } else {
            $response->getBody()->write(json_encode(['message' => 'Failed to create humidity detector']));
            return $response->withStatus(500);
        }
    }

    public function update(Request $request, Response $response, $args) {
        $id = $args['id'];
        $data = $request->getParsedBody();
        $sql = "UPDATE Rilevatore SET tipo = ?, unitaDiMisura = ?, codiceSeriale = ?, posizione = ?, impianto_id = ? WHERE id = ?";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bind_param("ssssii", $this->type, $data['unitaDiMisura'], $data['codiceSeriale'], $data['posizione'], $data['impianto_id'], $id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response->getBody()->write(json_encode(['message' => 'Humidity detector updated successfully']));
            return $response->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['message' => 'Failed to update humidity detector or no changes were made']));
            return $response->withStatus(500);
        }
    }

    public function delete(Request $request, Response $response, $args) {
        $id = $args['id'];
        $sql = "DELETE FROM Rilevatore WHERE id = ? AND tipo = ?";
        $stmt = DB::getConnection()->prepare($sql);
        $stmt->bind_param("is", $id, $this->type);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response->getBody()->write(json_encode(['message' => 'Humidity detector deleted successfully']));
            return $response->withStatus(200);
        } else {
            $response->getBody()->write(json_encode(['message' => 'Failed to delete humidity detector']));
            return $response->withStatus(500);
        }
    }
}
