<?php
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

include 'Connection.php';
include 'config.php';

$connObj = new Connection();
$conn = $connObj->getConnection();

$username = $_SESSION['username'];

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $usuario = $result->fetch_assoc();
} else {
    // Si no se encuentra el usuario, redirigir al usuario a la página de inicio de sesión
    header("location: login.php");
    exit;
}

// Cerrar la conexión y liberar los recursos
$stmt->close();
$connObj->closeConnection();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CRUD - DSS</title>

    <!-- Agregar SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.2/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.2/dist/sweetalert2.all.min.js"></script>

    <!-- Agrega el JS y el CSS de ACE -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>

    <!-- Agregar fuente Nunito -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Agregar Estilo CSS -->
    <link rel="stylesheet" href="public/css/david.css">
    <link rel="stylesheet" href="public/css/hottaco.css">
    <link rel="stylesheet" href="public/css/rodri.css">

    <!-- Agregar Librerias de Estilo Internas -->
    <link href="public/libs/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="public/css/sb-admin-2.css" rel="stylesheet">

    <!-- Agregar favicon -->
    <link rel="icon" href="public/favicon.ico" type="image/x-icon">
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center " href="dashboard.php">
                <div class="sidebar-brand-icon rotate-n-15">
                </div>
                <img src="public/images/hello-world-admin-logo.png" style="width: 30px;" alt="hello world admin logo">
                <div class="sidebar-brand-text mx-2">CRUD - DSS</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link ff-Formula-1" href="dashboard.php">
                    <i class="fas fa-columns" style="color: #62bff3;"></i>
                    <?php 
                             if ($usuario['rol'] == "Administrador") { echo "<span>CRUD - Procesadores</span>"; }
                        else if ($usuario['rol'] == "Cliente")       { echo "<span>R - Procesadores</span>"; }
                        else if ($usuario['rol'] == "Factura")       { echo "<span>UD - Procesadores</span>"; }
                    ?>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow fixed-top" id="navbar-top">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $usuario['nombre']; ?></span>
                                <img class="img-profile rounded-circle" src="<?php 
                                                                                     if ($usuario['rol'] == "Administrador") { echo "./public/images/profile/Admin.jpg"; }
                                                                                else if ($usuario['rol'] == "Cliente")       { echo "./public/images/profile/Cliente.jpg"; }
                                                                                else if ($usuario['rol'] == "Factura")       { echo "./public/images/profile/Factura.jpg"; }
                                                                             ?>">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="close-session.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Cerrar sesión
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Alerts from SWAL2 -->
                 <?php require_once "alerts.php"; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Section -->
                    <div class="container-fluid">

                        <h6>CURD - DSS - inventario</h6>

                        <?php if ($usuario['rol'] == "Administrador") : ?>
                        <!-- Content Agregar procesador -->
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="card shadow mb-4">
                                    <!-- Card Header - Dropdown -->
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold">Agregar un procesador nuevo</h6>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <form action="process-inventory.php" method="post" enctype="multipart/form-data">
                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <label for="inputMarca">Marca</label>
                                                        <input class="form-control" id="inputMarca" name="marca" type="text" placeholder="Marca del procesador" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <label for="inputModelo">Modelo</label>
                                                        <input class="form-control" id="inputModelo" name="modelo" type="text" placeholder="Modelo del procesador" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <label for="inputVelocidad">Velocidad (GHz)</label>
                                                        <input class="form-control" id="inputVelocidad" name="velocidad" type="number" step="0.01" placeholder="Velocidad del procesador" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <label for="inputNucleos">Núcleos</label>
                                                        <input class="form-control" id="inputNucleos" name="nucleos" type="number" placeholder="Número de núcleos" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-floating">
                                                        <label for="inputHilos">Hilos</label>
                                                        <input class="form-control" id="inputHilos" name="hilos" type="number" placeholder="Número de hilos" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <div class="form-floating mb-3 mb-md-0">
                                                        <label for="inputFechaAdquisicion">Fecha de Adquisición</label>
                                                        <input class="form-control" id="inputFechaAdquisicion" name="fechaAdquisicion" type="date" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-floating">
                                                        <label for="inputEstado">Estado</label>
                                                        <select class="form-control" id="inputEstado" name="estado" required>
                                                            <option value="En stock">En stock</option>
                                                            <option value="En uso">En uso</option>
                                                            <option value="Dañado">Dañado</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mb-3">
                                                <div class="col-md-6">
                                                    <label for="inputImagen">Imagen del procesador</label>
                                                    <input type="file" class="form-control" id="inputImagen" name="imagen" accept="image/*" onchange="previewImage(event)" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <img id="preview" src="#" alt="Previsualización de imagen" style="display:none; margin-top:10px;" width="200">
                                                </div>
                                            </div>

                                            <!-- Botones alineados en un div con clase d-flex -->
                                            <div class="d-flex justify-content-between mt-4">
                                                <button type="submit" class="btn custom-btn-blue btn-sm shadow-sm">Ingresar este contenido</button>
                                                <button type="reset" class="btn custom-btn-red btn-sm shadow-sm">Limpiar Formulario</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Content Inventario de Procesadores -->
                        <div class="row mt-3">
                            <div class="col-lg-12">
                                <div class="card shadow mb-4">
                                    <!-- Card Header - Dropdown -->
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold">Inventario de Procesadores</h6>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered ff-Inter" id="dataTable" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>Imagen</th>
                                                        <th>Marca</th>
                                                        <th>Modelo</th>
                                                        <th>Velocidad (GHz)</th>
                                                        <th>Núcleos</th>
                                                        <th>Hilos</th>
                                                        <th>Fecha de Adquisición</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                        <th>Imagen</th>
                                                        <th>Marca</th>
                                                        <th>Modelo</th>
                                                        <th>Velocidad (GHz)</th>
                                                        <th>Núcleos</th>
                                                        <th>Hilos</th>
                                                        <th>Fecha de Adquisición</th>
                                                        <th>Estado</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <?php
                                                        // Crear una nueva instancia de la clase Connection
                                                        $connObj = new Connection();
                                                        $conn = $connObj->getConnection();

                                                        // Consulta para obtener los datos de la tabla
                                                        $sql = "SELECT ProcesadorID, Marca, Modelo, VelocidadGHz, Nucleos, Hilos, FechaAdquisicion, Estado, Imagen FROM InventarioProcesadores";
                                                        $result = $conn->query($sql);

                                                        if ($result->num_rows > 0) {
                                                            // Mostrar datos en cada fila
                                                            while($row = $result->fetch_assoc()) {
                                                                echo "<tr>";
                                                                echo "<td><img src='" . htmlspecialchars($row['Imagen']) . "' alt='Imagen del procesador' width='100'></td>";
                                                                echo "<td>" . htmlspecialchars($row['Marca']) . "</td>";
                                                                echo "<td>" . htmlspecialchars($row['Modelo']) . "</td>";
                                                                echo "<td>" . htmlspecialchars($row['VelocidadGHz']) . "</td>";
                                                                echo "<td>" . htmlspecialchars($row['Nucleos']) . "</td>";
                                                                echo "<td>" . htmlspecialchars($row['Hilos']) . "</td>";
                                                                echo "<td>" . htmlspecialchars($row['FechaAdquisicion']) . "</td>";
                                                                echo "<td>" . htmlspecialchars($row['Estado']) . "</td>";
                                                                // Convertir el array a JSON y luego escapar las comillas simples en JavaScript
                                                                $jsonRow = json_encode($row, JSON_HEX_QUOT | JSON_HEX_TAG);
                                                                $jsonRow = htmlspecialchars($jsonRow, ENT_QUOTES, 'UTF-8');
                                                                // Restringimos los permisos a Administrador y a Factura
                                                                if ($usuario['rol'] != "Cliente") {
                                                                    echo "<td class='text-center'><a href='javascript:editarprocesador(`" . $jsonRow . "`)' class='d-none d-sm-inline-block btn btn-sm custom-btn-blue shadow-sm'>Editar</a> <a href='javascript:eliminarProducto(" . $row['ProcesadorID'] . ")' class='d-none d-sm-inline-block btn btn-sm custom-btn-red shadow-sm'>Borrar</a></td>";
                                                                } else {
                                                                    echo "<td class='text-center'><button class='d-none d-sm-inline-block btn btn-sm custom-btn-blue shadow-sm' disabled>Editar</button> <button class='d-none d-sm-inline-block btn btn-sm custom-btn-red shadow-sm' disabled>Borrar</button></td>";
                                                                }                                                                
                                                                echo "</tr>";
                                                            }
                                                        } else {
                                                            echo "<tr><td colspan='9' class='text-center'>No hay datos disponibles</td></tr>";
                                                        }

                                                        // Cerrar conexión
                                                        $connObj->closeConnection();
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; CRUD - DSS 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Scripts Section -->
        <script src="public/js/main.js"></script>
        <script src="public/js/david.js"></script>
        <script src="public/js/hottaco.js"></script>
        <script src="public/js/rodri.js"></script>

        <!-- Bootstrap core JavaScript-->
        <script src="public/libs/jquery/jquery.min.js"></script>
        <script src="public/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="public/libs/jquery-easing/jquery.easing.min.js"></script>

        <!-- Page level plugins -->
        <script src="public/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="public/libs/datatables/dataTables.bootstrap4.min.js"></script>

        <!-- Page level plugins -->
        <script src="public/libs/chart.js/Chart.min.js"></script>

        <!-- Page level custom scripts -->
        <script src="public/js/demo/datatables-demo.js"></script>
        <script src="public/js/demo/chart-area-demo.js"></script>
        <script src="public/js/demo/chart-pie-demo.js"></script>
        <script src="public/js/demo/chart-bar-demo.js"></script>
        <script src="public/js/demo/chart-server-usage.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="public/js/sb-admin-2.min.js"></script>

        <script>
        function previewImage(event) {
            var reader = new FileReader();
            reader.onload = function(){
                var output = document.getElementById('preview');
                output.src = reader.result;
                output.style.display = 'block';
            }
            reader.readAsDataURL(event.target.files[0]);
        }

        function editarprocesador(row) {
            // Campos de la tabla (ProcesadorID, Marca, Modelo, VelocidadGHz, Nucleos, Hilos, FechaAdquisicion, Estado, Imagen)
            var rowData = JSON.parse(row);
            console.log(rowData);

            // Seleccionamos el Estado
            let en_stock = (rowData.Estado == 'En stock') ? 'selected' : '';
            let en_uso   = (rowData.Estado == 'En uso')   ? 'selected' : '';
            let danado   = (rowData.Estado == 'Dañado')   ? 'selected' : '';

            let rutaImagen = rowData.Imagen;

            Swal.fire({
                width: '800px',
                html: `
                    <div class="card shadow-lg border-0 rounded-lg mt-4" id="formReparacion">
                        <div class="card-header">
                            <h3 class="text-center font-weight-light my-4">Editar procesador</h3>
                        </div>
                        <div class="card-body">
                            <form action="update-inventory.php" method="POST" enctype="multipart/form-data">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <input class="form-control" id="inputMarca" name="inputMarca" type="text" value="${rowData.Marca}" required />
                                            <label for="inputMarca">Marca del procesador</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <input class="form-control" id="inputModelo" name="inputModelo" type="text" value="${rowData.Modelo}"/>
                                            <label for="inputModelo">Modelo del procesador</label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <label for="inputVelocidad">Velocidad (GHz)</label>
                                            <input class="form-control" id="inputVelocidad" name="velocidad" type="number" value="${rowData.VelocidadGHz}" step="0.01"  required/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <label for="inputNucleos">Núcleos</label>
                                            <input class="form-control" id="inputNucleos" name="nucleos" type="number" value="${rowData.Nucleos}"  required/>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-floating">
                                            <label for="inputHilos">Hilos</label>
                                            <input class="form-control" id="inputHilos" name="hilos" type="number" value="${rowData.Hilos}" required />
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 mb-md-0">
                                            <label for="inputFechaAdquisicion">Fecha de Adquisición</label>
                                            <input class="form-control" id="inputFechaAdquisicion" name="fechaAdquisicion" type="text" value="${rowData.FechaAdquisicion}" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating">
                                            <label for="inputEstado">Estado</label>
                                            <select class="form-control" id="inputEstado" name="estado" required>
                                                <option value="En stock" ${en_stock}>En stock</option>
                                                <option value="En uso"   ${en_uso}>En uso</option>
                                                <option value="Dañado"   ${danado}>Dañado</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="inputImagen">Imagen del procesador</label>
                                        <input type="file" class="form-control" id="inputImagen" name="imagen" accept="image/*" onchange="mostrarImagen(this)">

                                        <div id="imagen-preview" class="mt-2"></div>
                                        <input type="hidden" name="inputPath" value="${rutaImagen}">
                                    </div>
                                </div>

                                <div class="mb-3 mt-4">
                                    <input name="procesadorID" type="hidden" value="${rowData.ProcesadorID}" required/>
                                    <button class="btn btn-primary" type="submit" name="submit">Actualizar el inventario</button>
                                </div>
                            </form>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                didOpen: () => {
                    // Mostrar la imagen predeterminada
                    mostrarImagenPredeterminada(rutaImagen);
                }
            });

            // Función para mostrar la imagen predeterminada
            function mostrarImagenPredeterminada(path) {
                var preview = document.getElementById('imagen-preview');
                var img = document.createElement('img');
                img.src = path; // Cambia la ruta a la foto predeterminada
                img.className = 'img-fluid'; // clase Bootstrap para hacer que la imagen sea responsive
                img.style.maxHeight = '150px'; // altura máxima de 100px, ancho ajustado automáticamente
                preview.appendChild(img);
            }
        }

        function mostrarImagen(input) {
            var preview = document.getElementById('imagen-preview');
            preview.innerHTML = ''; // Limpiar cualquier imagen anterior

            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'img-fluid'; // clase Bootstrap para hacer que la imagen sea responsive
                    img.style.maxHeight = '150px'; // altura máxima de 100px, ancho ajustado automáticamente
                    preview.appendChild(img);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function eliminarProducto(id) {
            // Puedes utilizar Swal2 para mostrar un modal de confirmación antes de eliminar
            Swal.fire({
                title: '¿Estás seguro?',
                text: 'Esta acción eliminará el procesador. ¿Deseas continuar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Crear un formulario dinámicamente
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'delete-procesador.php';

                    // Crear un campo de entrada oculto para la id
                    var inputId = document.createElement('input');
                    inputId.type = 'hidden';
                    inputId.name = 'id';
                    inputId.value = id;

                    // Adjuntar el campo de entrada al formulario
                    form.appendChild(inputId);

                    // Adjuntar el formulario al cuerpo del documento y enviarlo
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
        </script>
</body>

</html>