<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: user/login/login.php");
    exit;
}

$is_admin = isset($_SESSION['usuario_role']) && $_SESSION['usuario_role'] === 'admin';
$user_name = $_SESSION['usuario_nome'];

?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage Club | Dashboard | CRUD-PW2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/img/gestao.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/home.css">
    <link rel="stylesheet" href="assets/css/modal.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="home.php">
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
                        <a class="nav-link" href="home.php">Início</a>
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
                            <li><a class="dropdown-item" href="motos/motoadd.php"> <i class="fa-solid fa-plus"></i>
                                    <i class="fa-solid fa-motorcycle"></i>
                                    Nova
                                    Moto </a></li>
                            <li><a class="dropdown-item" href="clientes/clienteadd.php"><i class="fa-solid fa-plus"></i>
                                    <i class="fa-solid fa-user-pen"></i> Novo
                                    Cliente</a></li><br>

                            <li><a class="dropdown-item" href="motos/index.php"> <i class="fa-solid fa-motorcycle"></i>
                                    Ver
                                    Motos</a></li>
                            <li><a class="dropdown-item" href="clientes/index.php"><i class="fa-solid fa-users"></i> Ver
                                    Clientes</a></li>
                        </ul>
                    </li>
                    <li class="nav-item" style="margin-left:80px">
                        <a class="nav-link" href="#">
                            <?php echo htmlspecialchars($user_name); ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="javascript:void(0);" onclick="openModal('logoutModal')">Encerrar</a>
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

    <div class="container mt-4 content">
        <h1 class="header-title fade-in">Dashboard | Motos | Clientes</h1>
        <hr />
        <div class="row">
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 fade-in">
                <div class="open-modal-btn btn btn-primary btn-icon text-white custom-btn"
                    style="background-color: red; border: 0; cursor: pointer;" onclick="openModal('modal1')">
                    <i class="fa-solid fa-plus"></i>
                    <span>Adicionar Novo Item</span>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 fade-in">
                <div class="open-modal-btn btn btn-secondary btn-icon text-white"
                    style="background-color: #495057; border: 0; cursor: pointer;" onclick="openModal('modal2')">
                    <i class="fa-solid fa-rectangle-list"></i>
                    <span>Ver Itens</span>
                </div>
            </div>

            <?php if ($is_admin): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 fade-in">
                    <div class="open-modal-btn btn btn-secondary btn-icon text-white"
                        style="background-color: #343A40; border: 0; cursor: pointer;" onclick="openModal('modal3')">
                        <i class="fa-solid fa-users"></i>
                        <span>Usuários</span>
                    </div>
                </div>
            <?php endif; ?>


        </div>
    </div>

    <div id="modal1" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1><span class="garage">Garage</span> <span class="club">Club</span></h1>
                <span class="close" onclick="closeModal('modal1')">&times;</span>
            </div>
            <h2>Adicionar Novo Item</h2>
            <span class="details">Selecione uma opção para adicionar um novo item.</span>
            <button onclick="location.href='motos/motoadd.php'">Moto</button>
            <button onclick="location.href='clientes/clienteadd.php'">Cliente</button>
        </div>
    </div>

    <div id="modal2" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1><span class="garage">Garage</span> <span class="club">Club</span></h1>
                <span class="close" onclick="closeModal('modal2')">&times;</span>
            </div>
            <h2>Ver Itens</h2>
            <span class="details">Veja os itens que já foram cadastrados no sistema.</span>
            <button onclick="location.href='motos/index.php'">Motos</button>
            <button onclick="location.href='clientes/index.php'">Clientes</button>
        </div>
    </div>

    <!-- modal do Administrador -->
    <?php if ($is_admin): ?>
        <div id="modal3" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h1><span class="garage">Garage</span> <span class="club">Club</span></h1>
                    <span class="close" onclick="closeModal('modal3')">&times;</span>
                </div>
                <h2>Usuários</h2>
                <span class="details">Visualizar e Adicionar novos usuarios.<br> <span
                        style="color:#252422">(Administrador)</span> </span>
                <button onclick="location.href='user/new_user.php'">Adicionar Novo Usuário</button>
                <button onclick="location.href='user/users.php'">Ver Usuários</button>
            </div>
        </div>
    <?php endif; ?>

    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h1><span class="garage">Garage</span> <span class="club">Club</span></h1>
                <span class="close" onclick="closeModal('logoutModal')">&times;</span>
            </div>
            <h2>Tem certeza de que deseja sair?</h2>
            <div class="modal-buttons">
                <button onclick="logout()">Sim, Encerrar sessão</button>
                <button onclick="closeModal('logoutModal')">Cancelar</button>
            </div>
        </div>
    </div>

    <footer class="footer text-center fade-in">
        <p class="mb-0">&copy; 2024 Garage Club | Leonardo e Pedro</p>
    </footer>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).style.display = "block";
        }
        function closeModal(modalId) {
            document.getElementById(modalId).style.display = "none";
        }
        function logout() {
            window.location.href = "user/logout.php";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/scripts/home.js"></script>
    <script src="assets/scripts/darkmode.js"></script>
</body>

</html>