<?php

$user = "root";
$pass = "";

try {
    $dbh = new PDO("mysql:host=localhost;dbname=bierproject", $user, $pass);
} catch (PDOException $e) {
    die("Database fout: " . $e->getMessage());
}

$id = $_POST['id'];

$sql = 'DELETE FROM bier WHERE id = :id';
$stmt = $dbh->prepare($sql);
$stmt->execute([":id" => $id]);

// Renumber IDs consecutively
$dbh->exec("SET @num := 0");
$dbh->exec("UPDATE bier SET id = (@num := @num + 1)");
$count = $dbh->query("SELECT COUNT(*) FROM bier")->fetchColumn();
$dbh->exec("ALTER TABLE bier AUTO_INCREMENT = " . ($count + 1));

echo "Bier verwijderd <br>";
echo "<a href='front.html'>Terug</a>";