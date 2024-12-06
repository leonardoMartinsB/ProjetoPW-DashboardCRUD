<?php
require '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM motos_dados WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php?message=Moto excluída com sucesso");
        exit();
    } else {
        header("Location: index.php?error=Erro ao excluir a moto");
        exit();
    }
} else {
    header("Location: index.php?error=ID não especificado");
    exit();
}
?>