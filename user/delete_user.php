<?php
require '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    if ($id === 1) {
        header("Location: users.php?error=Não é possível excluir o usuário Administrador.");
        exit();
    }
    $sql = "DELETE FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        header("Location: users.php?message=Cliente excluído com sucesso");
        exit();
    } else {
        header("Location: users.php?error=Erro ao excluir Cliente");
        exit();
    }
} else {
    header("Location: users.php?error=ID não especificado");
    exit();
}
?>