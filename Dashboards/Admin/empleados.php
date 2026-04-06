<?php
include('../../Inicio-de-sesion/db.php'); 
include('../Core/EmpleadoDAO.php');

$empDAO = new EmpleadoDAO($conexion);
$empleados = $empDAO->listarTodos();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Empleados | RuedaSport</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="stylesheet" href="style.css"> 
    <style>
        .container {
            display: grid;
            width: 96%;
            margin: 0 auto;
            gap: 1.8rem;
            grid-template-columns: 14rem auto; 
        }

        main { 
            margin-top: 1.4rem; 
            max-width: 1000px; 
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        .form-container, .recent-orders {
            background: var(--color-white);
            padding: var(--card-padding);
            border-radius: var(--border-radius-3);
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
            transition: all 300ms ease;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            background: #f0f2f5; 
            padding: 20px;
            border-radius: var(--border-radius-2);
            align-items: flex-end;
        }

        .input-group { display: flex; flex-direction: column; gap: 0.5rem; }
        
        .input-group input {
            padding: 0.8rem;
            border-radius: 8px;
            border: 1px solid #dce1e7;
            outline: none;
            background: var(--color-white);
        }

        .btn-principal {
            background: #6c7ae0; 
            color: white;
            padding: 0.8rem;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: 0.3s;
        }

        .btn-principal:hover { background: #5a66c7; }

        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .table-header h2 { margin: 0; }

        .search-bar {
            width: 250px; 
            padding: 8px 12px;
            border: 1px solid #dce1e7;
            border-radius: 8px;
            outline: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
        }

        th:last-child, td:last-child {
            text-align: right;
            padding-right: 25px;
        }

        .action-icons { 
            font-size: 1.5rem; 
            display: flex; 
            gap: 15px; 
            justify-content: flex-end; 
            align-items: center;
        }

        .edit-btn { color: #6c7ae0; cursor: pointer; }
        .delete-btn { color: #ff7782; cursor: pointer; }
    </style>
</head>
<body>

<div class="container">
    <aside>
        <div class="top">
            <div class="logo"><h2>RUEDA<span>SPORT</span></h2></div>
        </div>
        <div class="sidebar">
            <a href="index.php"><i class='bx bxs-grid-alt'></i><h3>Inicio</h3></a>
            <a href="empleados.php" class="active"><i class='bx bx-user'></i><h3>Empleados</h3></a>
            <a href="vehiculos.php"><i class='bx bx-car'></i><h3>Vehículos</h3></a>
            <a href="#"><i class='bx bx-calendar'></i><h3>Reservas</h3></a>
            <a href="../../index.html" class="logout"><i class='bx bx-log-out'></i><h3>Cerrar sesión</h3></a>
        </div>
    </aside>

    <main>
        <h1 class="animate__animated animate__fadeInDown">Gestión de Empleados</h1>

        <div class="form-container animate__animated animate__fadeInLeft">
            <h2 id="titulo-form" style="margin-bottom: 1.2rem;">Registrar Nuevo Empleado</h2>
            <div class="form-grid">
                <input type="hidden" id="emp_id_db">
                <div class="input-group">
                    <label style="font-size: 0.8rem; font-weight: 600;">Nombre Completo</label>
                    <input type="text" id="emp_nombre" placeholder="Ej: Juan Pérez">
                </div>
                <div class="input-group">
                    <label style="font-size: 0.8rem; font-weight: 600;">Código de Acceso</label>
                    <input type="text" id="emp_codigo" placeholder="Ej: EMP105">
                </div>
                <div class="input-group">
                    <label style="font-size: 0.8rem; font-weight: 600;">Contraseña</label>
                    <input type="password" id="emp_pass" placeholder="Clave de ingreso">
                </div>
                <button class="btn-principal" id="btn-submit" onclick="guardarDatos()">Guardar Empleado</button>
            </div>
        </div>

        <div class="recent-orders animate__animated animate__fadeInUp">
            <div class="table-header">
                <h2>Listado de Personal</h2>
                <input type="text" id="buscador" class="search-bar" onkeyup="filtrar()" placeholder="🔍 Buscar por nombre o código...">
            </div>
            
            <table id="tablaEmpleados">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = mysqli_fetch_assoc($empleados)): ?>
                    <tr>
                        <td><b><?php echo $row['codigo_empleado']; ?></b></td>
                        <td><?php echo $row['nombre']; ?></td>
                        <td><span class="text-muted"><?php echo $row['estado']; ?></span></td>
                        <td>
                            <div class="action-icons">
                                <i class='bx bx-edit-alt edit-btn' onclick="prepararEdicion('<?php echo $row['codigo_empleado']; ?>', '<?php echo $row['nombre']; ?>')"></i>
                                <i class='bx bx-trash delete-btn' onclick="eliminarRegistro('<?php echo $row['codigo_empleado']; ?>')"></i>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function filtrar() {
    let input = document.getElementById("buscador").value.toUpperCase();
    let filas = document.getElementById("tablaEmpleados").getElementsByTagName("tr");
    for (let i = 1; i < filas.length; i++) {
        filas[i].style.display = filas[i].innerText.toUpperCase().includes(input) ? "" : "none";
    }
}

function prepararEdicion(codigo, nombre) {
    document.getElementById('emp_nombre').value = nombre;
    document.getElementById('emp_codigo').value = codigo;
    document.getElementById('emp_id_db').value = "MODO_EDITAR";
    document.getElementById('titulo-form').innerText = "Editando a: " + nombre;
    document.getElementById('btn-submit').innerText = "Actualizar Empleado";
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function guardarDatos() {
    const modo = document.getElementById('emp_id_db').value;
    const nombre = document.getElementById('emp_nombre').value;
    const codigo = document.getElementById('emp_codigo').value;
    const pass = document.getElementById('emp_pass').value;
    const accion = (modo === "MODO_EDITAR") ? 'actualizar' : 'registrar';

    if(!nombre || !codigo) return Swal.fire('Error', 'Faltan datos obligatorios', 'error');

    fetch('controlador_empleado.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `accion=${accion}&nombre=${nombre}&codigo=${codigo}&pass=${pass}`
    })
    .then(res => res.text())
    .then(data => {
        if(data.trim() === "success") {
            Swal.fire('Éxito', 'Operación realizada', 'success').then(() => location.reload());
        } else {
            Swal.fire('Error', 'No se pudo procesar', 'error');
        }
    });
}

function eliminarRegistro(codigo) {
    Swal.fire({
        title: '¿Eliminar Empleado?',
        text: "Código: " + codigo,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff7782',
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('controlador_empleado.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `accion=eliminar&codigo=${codigo}`
            }).then(() => location.reload());
        }
    });
}
</script>
</body>
</html>