<?php
try {
    $user = 'root';
    $pass = '';
    $dbh = new PDO('mysql:host=localhost;dbname=bierproject', $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}

$requestMethod = $_SERVER['REQUEST_METHOD'];
$id = $_GET['id'] ?? null;
$body = file_get_contents('php://input');
$data = json_decode($body, true);


   //GET - Toon alle bieren

function showBeer($dbh) {
    $sql = 'SELECT * FROM bier';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    return json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
}


   //POST - Voeg bier toe

function addBier($dbh, $data) {

    $sql = 'INSERT INTO bier 
            (naam, brouwer, type, gisting, perc, inkoop_prijs) 
            VALUES 
            (:naam, :brouwer, :type, :gisting, :perc, :inkoop_prijs)';

    $stmt = $dbh->prepare($sql);
    $stmt->execute([
        ":naam" => $data['naam'],
        ":brouwer" => $data['brouwer'],
        ":type" => $data['type'],
        ":gisting" => $data['gisting'],
        ":perc" => $data['perc'],
        ":inkoop_prijs" => $data['inkoop_prijs']
    ]);

    echo json_encode(["status" => "Bier toegevoegd"]);
}


   //DELETE - Verwijder bier

function deleteBier($dbh, $id) {

    $sql = 'DELETE FROM bier WHERE id = :id';
    $stmt = $dbh->prepare($sql);
    $stmt->execute([":id" => $id]);

    echo json_encode(["status" => "Bier verwijderd"]);
}


   //POST - Like bier

function likeBier($dbh, $id) {
    $sql = "UPDATE bier SET likes = likes + 1 WHERE id = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([":id" => $id]);

    echo json_encode(["status" => "liked"]);
}


if ($requestMethod === 'GET') {
    echo showBeer($dbh);
}
else if ($requestMethod === 'POST' && isset($data['actie']) && $data['actie'] === 'like') {
    likeBier($dbh, $data['id']);
}
else if ($requestMethod === 'POST') {
    addBier($dbh, $data);
}
else if ($requestMethod === 'DELETE') {
    deleteBier($dbh, $id);
}
else {
    echo json_encode(["error" => "Unsupported request method"]);
}
?>
