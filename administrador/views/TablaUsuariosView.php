<div class="card mb-4">
    <div class="card-header">
        <i class="fas fa-table me-1"></i>
        Lista de usuarios registrados
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Descripcion</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?php echo $usuario['usuario']; ?></td>
                    <td><?php echo $usuario['correo']; ?></td>
                    <td><?php echo $usuario['permisos']; ?></td>
                    <td><?php echo $usuario['descripcion']; ?></td>
                    <td>
                        <button type="button" class="btn btn-light btn-sm">Detalles</button>
                        <button type="button" class="btn btn-danger btn-sm">Eliminar</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>