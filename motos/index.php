<?php
ob_start();
require '../config.php';

$sql = "SELECT id, marca, modelo, created, modified, imagem FROM motos_dados";
$result = $conn->query($sql);

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage Club | Geral Moto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="../assets/img/gestao.png" type="image/x-icon">

    <link rel="stylesheet" href="../assets/css/motos/motos.css">

    <link rel="stylesheet" href="../assets/css/motos/motoindex.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../home.php">
                <span class="brand-garage">Garage</span>
                <span class="brand-club">Club</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../home.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Configurações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Relatórios</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Gestão</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="customers/add.php"> <i class="fa-solid fa-plus"></i> Novo
                                    item </a></li>
                            <li><a class="dropdown-item" href="customers"> <i class="fa-solid fa-motorcycle"></i> Ver
                                    Motos</a></li>
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#" id="nestedDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">Mais Opções</a>
                                <ul class="dropdown-menu" aria-labelledby="nestedDropdown">
                                    <li><a class="dropdown-item" href="#">Sub-item 1</a></li>
                                    <li><a class="dropdown-item" href="#">Sub-item 2</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <label class="switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider">
                        <i class="fa fa-sun"></i>
                        <i class="fa fa-moon"></i>
                    </span>
                </label>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-5" id="title">Lista de Motos</h1>

        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success" id="alert">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger" id="alert">
                <?php echo htmlspecialchars($_GET['error']); ?>
            </div>
        <?php endif; ?>


        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Pesquisar por marca ou modelo"
                onkeyup="filterTable()">
        </div>

        <div class="table-container">
            <button class="btn btn-primary mb-3" onclick="window.location.href='motoadd.php'">Adicionar Nova
                Moto</button>
            <button class="btn btn-secondary mb-3" onclick="location.reload()">Atualizar Página</button>

            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Marca</th>
                            <th>Modelo</th>
                            <th>Data de Cadastro</th>
                            <th>Data de Modificação</th>
                            <th>Imagem</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody id="motoTableBody">
                        <?php if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['marca']; ?></td>
                                    <td><?php echo $row['modelo']; ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($row['created'])); ?></td>
                                    <td>
                                        <?php
                                        $modifiedDate = date("d/m/Y", strtotime($row['modified']));
                                        $modifiedTime = date("H:i", strtotime($row['modified']));
                                        ?>
                                        <?php echo $modifiedDate; ?><br>
                                        <small><?php echo $modifiedTime; ?></small>
                                    </td>
                                    <td>
                                        <?php if ($row['imagem']) { ?>
                                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['imagem']); ?>"
                                                class="img-thumbnail" alt="Imagem da Moto">
                                        <?php } else { ?>
                                            <span>Sem imagem</span>
                                        <?php } ?>
                                    </td>
                                    <td class="action-buttons">
                                        <a href="viewmoto.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">
                                            <i class="fa-solid fa-eye"></i> Visualizar
                                        </a>
                                        <a href="editarmoto.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-edit"></i> Editar
                                        </a>
                                        <a href="#" class="btn btn-danger btn-sm" data-id="<?php echo $row['id']; ?>"
                                            onclick="openDeleteModal(this)">
                                            <i class="fa-solid fa-trash"></i> Excluir
                                        </a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="7" class="text-center">Nenhuma moto cadastrada.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel" style="color: black; font-weight:bold">Excluir Moto
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="color: black; font-weight:bold">
                    Tem certeza que deseja excluir esta moto?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                        style="background:#495057">Cancelar</button>
                    <button type="button" class="btn btn-secondary" id="confirmDelete">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer text-center fade-in">
        <p class="mb-0">&copy; 2024 Garage Club | Leonardo e Pedro</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../assets/scripts/darkmode.js"></script>

    <script src="../assets/scripts/motos/motoindex.js"></script>

</body>

</html>