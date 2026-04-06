<?php
include('../../Inicio-de-sesion/db.php');
$query = "SELECT * FROM empleados"; 
$resultado = mysqli_query($conexion, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Gestión de Empleados</title>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
*{ font-family: sans-serif; }

table{
    width: 100%;
    border-collapse: collapse;
}

th, td{
    padding: 10px;
    text-align: center;
    border-bottom: 1px solid #ccc;
}

.btn{
    padding: 8px 12px;
    border: none;
    cursor: pointer;
    color: white;
    border-radius: 5px;
    font-weight: bold;
}

.btn-activar{ background:#20e3b2; }
.btn-apagar{ background:#ff7782; }

.estado-activo{
    background: green;
    color: white;
    padding: 5px;
    border-radius: 5px;
}

.estado-offline{
    background: red;
    color: white;
    padding: 5px;
    border-radius: 5px;
}
</style>
</head>

<body>

<h2>Gestión de Empleados</h2>

<table>
<thead>
<tr>
    <th>Nombre</th>
    <th>Estado</th>
    <th>Acción</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($resultado)): ?>
<tr id="fila-<?php echo $row['id']; ?>">

<td><?php echo $row['nombre']; ?></td>

<td class="col-estado">
    <span class="<?php echo ($row['estado']=='Activo') ? 'estado-activo' : 'estado-offline'; ?>">
        <?php echo $row['estado']; ?>
    </span>
</td>

<td class="col-botones">

<!-- 🔥 SIN onclick (evita conflicto con dashboard) -->
<button type="button" class="btn btn-activar"
data-id="<?php echo $row['id']; ?>" data-estado="Activo">
Activar
</button>

<button type="button" class="btn btn-apagar"
data-id="<?php echo $row['id']; ?>" data-estado="Offline">
Apagar
</button>

</td>

</tr>
<?php endwhile; ?>
</tbody>
</table>

<script>

// 🔥 FUNCIONA EN CUALQUIER DASHBOARD
document.addEventListener("DOMContentLoaded", function(){

    document.querySelectorAll(".btn-activar, .btn-apagar").forEach(btn => {

        btn.addEventListener("click", function(e){

            e.preventDefault();
            e.stopPropagation();

            let id = this.getAttribute("data-id");
            let estado = this.getAttribute("data-estado");

            fetch('/RuedaSport/Dashboards/Admin/actualizar_empleado.php', {
                method: 'POST',
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: 'id=' + id + '&estado=' + estado
            })
            .then(res => res.text())
            .then(data => {

                console.log("RESPUESTA:", data);

                if(data.trim() === "success"){

                    Swal.fire({
                        icon: 'success',
                        title: 'Actualizado correctamente',
                        timer: 1200,
                        showConfirmButton: false
                    });

                    const fila = document.getElementById('fila-'+id);

                    let clase = (estado === "Activo") ? "estado-activo" : "estado-offline";

                    fila.querySelector('.col-estado').innerHTML =
                        `<span class="${clase}">${estado}</span>`;

                } else {
                    Swal.fire('Error','No se pudo actualizar','error');
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error','Fallo de conexión','error');
            });

        });

    });

});
</script>

</body>
</html>