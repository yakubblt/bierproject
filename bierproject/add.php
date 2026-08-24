<?php
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
