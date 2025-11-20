<?php
class ComponenteModel
{

    private $pdo;
    function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }


    public function getAll()
    {


        $stmt = $this->pdo->query("SELECT * FROM componentes ORDER BY id DESC");
        return $stmt->fetchAll();

    }

    public function create(array $data)
    {
        
        $stmt = $this->pdo->prepare("INSERT INTO componentes (referencia,nombre,stock)VALUES (?,?,?)");
        return $stmt->execute(
            [
                $data['referencia'] ?? '',
                $data['nombre'] ?? '',
                $data['stock'] ?? ''
            ]
        );


/*   Con esto de aqí abajo podría sumar stock si se introdujese un componente con las misma referencia que otro
$referencia = trim($data['referencia'] ?? '');
        $nombre = trim($data['nombre'] ?? '');
        $stock = (int) ($data['stock'] ?? 0);

        if ($referencia === '') {

            return false;
        }

        $existing = $this->getByReferencia($referencia);

        if ($existing) {



            $newStock = (int) ($existing['stock'] ?? 0) + $stock;

            $stmt = $this->pdo->prepare("UPDATE componentes SET stock=?,nombre=? WHERE id = ?");
            return $stmt->execute([$newStock, $nombre !== '' ? $nombre : $existing['nombre'], $existing['id']]);


        }

        $stmt = $this->pdo->prepare("INSERT INTO componentes (referencia,nombre,stock)VALUES (?,?,?)");
        return $stmt->execute(
            [$referencia,$nombre,$stock]
        );

*/


    }


    public function update(int $id, array $data)
    {


        $stmt = $this->pdo->prepare("UPDATE componentes SET referencia = ?, nombre=?,stock=? WHERE id = ?");
        return $stmt->execute([
            $data['referencia'] ?? '',
            $data['nombre'] ?? '',
            $data['stock'] ?? '',
            $id
        ]);

    }


    public function delete(int $id)
    {

        $stmt = $this->pdo->prepare("DELETE FROM componentes WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getById(int $id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM componentes WHERE id= ?");
        $stmt->execute([$id]);

        $c = $stmt->fetch();
        return $c ?: null;




    }

}


?>