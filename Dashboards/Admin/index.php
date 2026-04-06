<?php
include('../../Inicio-de-sesion/db.php'); 
include('../Core/EmpleadoDAO.php');
include('../Core/VehiculoDAO.php');

$empDAO = new EmpleadoDAO($conexion);
$vehDAO = new VehiculoDAO($conexion);

$empleados = $empDAO->listarTodos();
$totalVehiculos = $vehDAO->getVehiculosHoy();
$totalIngresos = $vehDAO->getIngresosHoy();

$capacidadTotal = 50; 
$cuposDisponibles = $capacidadTotal - $totalVehiculos;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | RuedaSport</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <style>
        .right { position: relative; padding-top: 80px; }
        .right .top { position: absolute; top: 0; right: 0; width: 100%; display: flex; justify-content: flex-end; gap: 2rem; align-items: center; padding: 10px 0; }
        .right h2 { text-align: center; width: 100%; margin-bottom: 0.8rem; }
        #modalGestionEmpleados { display: none; position: fixed; z-index: 900; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.7); backdrop-filter: blur(5px); }
        .modal-body { background: var(--color-white); margin: 5% auto; padding: 30px; border-radius: var(--border-radius-3); width: 90%; max-width: 850px; box-shadow: var(--box-shadow); }
        .form-crear { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; background: var(--color-light); padding: 15px; border-radius: var(--border-radius-1); margin-bottom: 20px; }
        .form-crear input { padding: 10px; border-radius: 5px; border: 1px solid #ddd; outline: none; }
        .btn-save { background: var(--color-primary); color: white; border: none; cursor: pointer; font-weight: 600; border-radius: 5px; }
        .search-box-modal { width: 100%; padding: 12px; margin-bottom: 15px; border: 2px solid var(--color-primary); border-radius: 8px; }
        .tabla-scroll { max-height: 250px; overflow-y: auto; }
        .tabla-scroll table { width: 100%; border-collapse: collapse; }
        .tabla-scroll th, .tabla-scroll td { padding: 12px; text-align: left; border-bottom: 1px solid var(--color-light); }
    </style>
</head>
<body>

<div class="container">
    <aside>
        <div class="top">
            <div class="logo"><h2>RUEDA<span>SPORT</span></h2></div>
        </div>
        <div class="sidebar">
            <a href="index.php" class="active"><i class='bx bxs-grid-alt'></i><h3>Inicio</h3></a>
            <a href="empleados.php"><i class='bx bx-user'></i><h3>Empleados</h3></a>
            <a href="vehiculos.php"><i class='bx bx-car'></i><h3>Vehículos</h3></a>
            <a href="#"><i class='bx bx-calendar'></i><h3>Reservas</h3></a>
            <a href="../../index.html" class="logout"><i class='bx bx-log-out'></i><h3>Cerrar sesión</h3></a>
        </div>
    </aside>

    <main>
        <h1>Panel General</h1>
        <p class="welcome-text">¡Bienvenido al Panel de Administración Integral de RuedaSport!</p>
        
        <div class="top-bar">
            <input type="date" class="date" value="<?php echo date('Y-m-d'); ?>">
            <div class="income-filter">
                <span>Total Ingresos: <b>$<?php echo number_format($totalIngresos, 0, ',', '.'); ?></b></span>
            </div>
        </div>

        <div class="insights">
            <div class="sales">
                <i class='bx bx-car'></i>
                <div class="middle">
                    <div class="left"><h3>Vehículos Hoy</h3><h1><?php echo $totalVehiculos; ?></h1></div>
                    <div class="progress">
                        <svg><circle cx="38" cy="38" r="36"></circle></svg>
                        <div class="number"><p><?php echo round(($totalVehiculos / $capacidadTotal) * 100); ?>%</p></div>
                    </div>
                </div>
            </div>

            <div class="expenses">
                <i class='bx bx-dollar'></i>
                <div class="middle">
                    <div class="left"><h3>Ingresos</h3><h1>$<?php echo number_format($totalIngresos / 1000, 1); ?>k</h1></div>
                    <div class="progress">
                        <svg><circle cx="38" cy="38" r="36"></circle></svg>
                        <div class="number"><p>--</p></div>
                    </div>
                </div>
            </div>

            <div class="income">
                <i class='bx bx-parking'></i>
                <div class="middle">
                    <div class="left"><h3>Cupos</h3><h1><?php echo $cuposDisponibles; ?></h1></div>
                    <div class="progress">
                        <svg><circle cx="38" cy="38" r="36"></circle></svg>
                        <div class="number"><p><?php echo round(($cuposDisponibles / $capacidadTotal) * 100); ?>%</p></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="recent-orders">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.8rem;">
                <h2>Gestión de Empleados</h2>
                <button onclick="abrirGestion()" style="padding: 10px 20px; background: var(--color-primary); color: white; border-radius: 5px; cursor: pointer; border: none;">Gestionar Empleados</button>
            </div>
            <table id="tablaDashboard">
                <thead>
                    <tr>
                        <th>ID Acceso</th>
                        <th>Nombre</th>
                        <th>Estado</th>
                        <th>Conexión</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    mysqli_data_seek($empleados, 0);
                    while($user = mysqli_fetch_assoc($empleados)): 
                        $statusClass = ($user['estado'] == 'Activo') ? 'success' : (($user['estado'] == 'Almuerzo') ? 'warning' : 'danger');
                    ?>
                    <tr>
                        <td><b><?php echo $user['codigo_empleado']; ?></b></td>
                        <td><?php echo $user['nombre']; ?></td>
                        <td class="<?php echo $statusClass; ?>"><?php echo $user['estado']; ?></td>
                        <td><?php echo $user['conexion']; ?></td>
                        <td>
                            <button class="btn-config btn-accion" data-id="<?php echo $user['id']; ?>" data-estado="Activo" style="background: var(--color-success); cursor:pointer;">Activar</button>
                            <button class="btn-config btn-accion" data-id="<?php echo $user['id']; ?>" data-estado="Offline" style="background: var(--color-danger); cursor:pointer;">Apagar</button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

    <div class="right">
        <div class="top">
            <div class="theme-toggler"><i class='bx bx-sun active'></i><i class='bx bx-moon'></i></div>
            <div class="profile">
                <div class="info"><p>Hola, <b>Admin</b></p><small class="text-muted">Administrador</small></div>
                <div class="profile-photo"><img src="img/perfil.jpg"></div>
            </div>
        </div>

        <div class="recent-updates">
            <h2>Vehículos Activos (TR)</h2>
            <div class="updates">
                <?php 
                $recientes = $vehDAO->listarUltimosIngresos(3);
                while($reg = mysqli_fetch_assoc($recientes)): 
                    $icon = (strtolower($reg['tipo'] ?? '') == 'moto') ? 'bx-bicycle' : 'bx-car';
                ?>
                <div class="update">
                    <div class="profile-photo" style="display: flex; align-items: center; justify-content: center; background: var(--color-light); border-radius: 50%;">
                        <i class='bx <?php echo $icon; ?>' style="font-size: 1.8rem; color: var(--color-primary);"></i>
                    </div>
                    <div class="message">
                        <p><b>Placa: <?php echo $reg['placa']; ?></b> Ingresó al sistema</p>
                        <small class="text-muted">Hora: <?php echo date('H:i', strtotime($reg['fecha_ingreso'])); ?></small>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

        <div class="sales-analytics">
            <h2>Reservas Pendientes</h2>
            <div class="item online">
                <div class="icon"><i class='bx bx-cart'></i></div>
                <div class="right-text">
                    <div class="info"><h3>RESERVAS APP</h3><small class="text-muted">Últimas 2 horas</small></div>
                    <h3 class="success">+39%</h3><h3>384</h3>
                </div>
            </div>
            <div class="item offline">
                <div class="icon"><i class='bx bx-shopping-bag'></i></div>
                <div class="right-text">
                    <div class="info"><h3>RESERVAS WEB</h3><small class="text-muted">Últimas 5 horas</small></div>
                    <h3 class="danger">-17%</h3><h3>110</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modalGestionEmpleados">
    <div class="modal-body">
        <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom:15px;">
            <h2 style="color:var(--color-primary);">Centro de Gestión de Empleados</h2>
            <i class='bx bx-x' onclick="cerrarGestion()" style="font-size:2.5rem; cursor:pointer; color: var(--color-danger);"></i>
        </div>
        <div class="form-crear">
            <input type="hidden" id="emp_id_modal">
            <input type="text" id="emp_nombre_modal" placeholder="Nombre Completo">
            <input type="text" id="emp_codigo_modal" placeholder="Código (EMP101)">
            <input type="password" id="emp_pass_modal" placeholder="Contraseña">
            <button class="btn-save" id="btnAccionModal" onclick="procesarEmpleado()">Guardar Nuevo</button>
        </div>
        <input type="text" id="buscadorModal" class="search-box-modal" onkeyup="buscarEmpleadoModal()" placeholder="🔍 Buscar por nombre o código...">
        <div class="tabla-scroll">
            <table id="tablaGestionInterna">
                <thead>
                    <tr><th>Código</th><th>Nombre</th><th>Acciones</th></tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const themeToggler = document.querySelector(".theme-toggler");
themeToggler.addEventListener('click', () => {
    document.body.classList.toggle('dark-theme-variables');
    themeToggler.querySelector('i:nth-child(1)').classList.toggle('active');
    themeToggler.querySelector('i:nth-child(2)').classList.toggle('active');
});

function abrirGestion() {
    document.getElementById('modalGestionEmpleados').style.display = 'block';
    actualizarTablaModal();
}

function cerrarGestion() {
    document.getElementById('modalGestionEmpleados').style.display = 'none';
    limpiarFormularioModal();
}

function limpiarFormularioModal() {
    document.getElementById('emp_id_modal').value = '';
    document.getElementById('emp_nombre_modal').value = '';
    document.getElementById('emp_codigo_modal').value = '';
    document.getElementById('emp_pass_modal').value = '';
    document.getElementById('btnAccionModal').innerText = 'Guardar Nuevo';
}

function actualizarTablaModal() {
    const tbody = document.querySelector("#tablaGestionInterna tbody");
    const filasDash = document.querySelectorAll("#tablaDashboard tbody tr");
    tbody.innerHTML = "";
    filasDash.forEach(fila => {
        const codigo = fila.cells[0].innerText;
        const nombre = fila.cells[1].innerText;
        tbody.innerHTML += `
            <tr>
                <td><b>${codigo}</b></td>
                <td>${nombre}</td>
                <td>
                    <i class='bx bx-edit-alt' onclick="prepararEdicion('${codigo}', '${nombre}')" style="color:var(--color-primary); cursor:pointer; font-size:1.4rem;"></i>
                    <i class='bx bx-trash' onclick="confirmarEliminar('${codigo}')" style="color:var(--color-danger); cursor:pointer; font-size:1.4rem; margin-left:10px;"></i>
                </td>
            </tr>`;
    });
}

function buscarEmpleadoModal() {
    let filtro = document.getElementById("buscadorModal").value.toUpperCase();
    let filas = document.getElementById("tablaGestionInterna").getElementsByTagName("tr");
    for (let i = 1; i < filas.length; i++) {
        filas[i].style.display = filas[i].innerText.toUpperCase().includes(filtro) ? "" : "none";
    }
}

// LOGICA DE EDITAR
function prepararEdicion(codigo, nombre) {
    document.getElementById('emp_nombre_modal').value = nombre;
    document.getElementById('emp_codigo_modal').value = codigo;
    document.getElementById('emp_id_modal').value = "EDIT";
    document.getElementById('btnAccionModal').innerText = 'Actualizar Datos';
}

// LOGICA DE REGISTRAR / ACTUALIZAR
function procesarEmpleado() {
    const id_flag = document.getElementById('emp_id_modal').value;
    const nombre = document.getElementById('emp_nombre_modal').value;
    const codigo = document.getElementById('emp_codigo_modal').value;
    const pass = document.getElementById('emp_pass_modal').value;
    const accion = id_flag === "EDIT" ? 'actualizar' : 'registrar';

    if(!nombre || !codigo) return Swal.fire({ title: 'Error', text: 'Completa nombre y código', icon: 'error', target: document.getElementById('modalGestionEmpleados') });

    fetch('controlador_empleado.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `accion=${accion}&nombre=${nombre}&codigo=${codigo}&pass=${pass}`
    })
    .then(res => res.text())
    .then(data => {
        if(data.trim() === "success") {
            Swal.fire({ title: 'Listo', text: 'Operación exitosa', icon: 'success', target: document.getElementById('modalGestionEmpleados') }).then(() => location.reload());
        } else if(data.trim() === "exists") {
            Swal.fire({ title: 'Atención', text: 'El código ya existe', icon: 'warning', target: document.getElementById('modalGestionEmpleados') });
        }
    });
}

// LOGICA DE ELIMINAR
function confirmarEliminar(codigo) {
    Swal.fire({
        title: '¿Eliminar?',
        text: `Se borrará al empleado ${codigo}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        target: document.getElementById('modalGestionEmpleados')
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('controlador_empleado.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: `accion=eliminar&codigo=${codigo}`
            })
            .then(res => res.text())
            .then(data => {
                if(data.trim() === "success") {
                    Swal.fire({ title: 'Eliminado', icon: 'success', target: document.getElementById('modalGestionEmpleados') }).then(() => location.reload());
                }
            });
        }
    });
}

// LOGICA ACTIVAR / APAGAR (Dashboard Principal)
document.querySelectorAll(".btn-accion").forEach(btn => {
    btn.addEventListener("click", function(e){
        let id = this.getAttribute("data-id");
        let estado = this.getAttribute("data-estado");
        fetch('actualizar_empleado.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: 'id=' + id + '&estado=' + estado
        })
        .then(res => res.text())
        .then(data => {
            if(data.trim() === "success"){
                Swal.fire({ icon: 'success', title: 'Actualizado', timer: 1200, showConfirmButton: false }).then(() => location.reload());
            }
        });
    });
});
</script>
</body>
</html>