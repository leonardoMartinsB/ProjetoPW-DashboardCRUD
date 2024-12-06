<?php
require '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM clientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php?message=Cliente excluído com sucesso");
        exit();
    } else {
        header("Location: index.php?error=Erro ao excluir Cliente");
        exit();
    }
} else {
    header("Location: index.php?error=ID não especificado");
    exit();
}
?>