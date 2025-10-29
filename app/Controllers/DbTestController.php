<?php declare(strict_types = 1);

namespace App\Controllers;

use PDO;
use Psr\Http\Message\RequestInterface;

class DbTestController {
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function indexAction(RequestInterface $request): array {
        $stmt = $this->db->query('SELECT NOW() AS fecha_actual');
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ['current_time' => $result['fecha_actual']];
    }
}

?>