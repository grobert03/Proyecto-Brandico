const devolverUsuarios = (nombre, rol, usuarios, empresas) => {
  $.ajax({
    url: ruta_devolver_usuarios,
    method: "POST",
    dataType: "json",
    data: {
      nombre: nombre,
      rol: rol,
      usuarios: usuarios,
      empresas: empresas,
    },
    async: true,
    success: function (data) {
      console.log(data);
      data = data.filter((usu) => usu.nombre.toLowerCase().startsWith(nombre));
      if (data.length == 0) {
        $("tbody").empty();
        $("tbody").append(
          $(
            `<td colspan="8"><p>No se ha encontrado un usuario que cumpla los criterios</p></td>`
          )
        );
      } else {
        $("tbody").empty();
        data.forEach((e) => {
          $("tbody").append(
            $(`<tr><td>${e.correo}</td>
            <td>${e.nombre}</td>
            <td>${e.apellidos ? e.apellidos : ""}</td>
            <td>${e.rol ? e.rol : ""}</td>
            <td>${e.dni ? e.dni : e.cif}</td>
            <td>${e.telefono}</td>
            <td>${e.direccion ? e.direccion : ""}</td>
            <td>${e.provincia ? e.provincia : ""}</td></tr>`)
          );
        });
      }
    },
    error: function (errorThrown) {
      console.log(errorThrown);
    },
  });
};

devolverUsuarios(nombre, rol, usuarios, empresas);

$("#input_nombre").on("keyup", () => {
  nombre = $("#input_nombre").val();
  devolverUsuarios(nombre, rol, usuarios, empresas);
});

$("#check_usu").on("click", () => {
  usuarios = !usuarios;
  devolverUsuarios(nombre, rol, usuarios, empresas);
});

$("#check_empresa").on("click", () => {
  empresas = !empresas;
  devolverUsuarios(nombre, rol, usuarios, empresas);
});

$("#check_admin").on("click", () => {
  rol = !rol;
  devolverUsuarios(nombre, rol, usuarios, empresas);
});
