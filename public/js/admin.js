$(".rotate").click(function() {
  if ($(this).children("ion-icon").hasClass("down")) {
    $(".input:not(:first)").val("")
  }
  $(this).children("ion-icon").toggleClass("down");

});

$("#btn-borrar-datos").click(function() {
  $("#formulario-crear input").val("");
});

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
      data = data.filter((usu) =>
        usu.nombre.toLowerCase().startsWith(nombre.toLowerCase())
      );
      if (data.length == 0) {
        $("tbody").empty();
        $("tbody").append(
          $(
            `<td colspan="8"><p>No se ha encontrado un usuario que cumpla los criterios</p></td>`
          )
        );
      } else {
        $("tbody").empty();src/Controller/InicioController.php
        data.forEach((e) => {
          $("tbody").append(
            $(`<tr data-correo="${e.correo}"><td>${e.correo}</td>
            <td>${e.nombre}</td>
            <td>${e.apellidos ? e.apellidos : ""}</td>
            <td>${e.rol ? e.rol : ""}</td>
            <td>${e.dni ? e.dni : e.cif}</td>
            <td>${e.telefono}</td>
            <td>${e.direccion ? e.direccion : ""}</td>
            <td>${e.provincia ? e.provincia : ""}</td>
            <td><button class='button data-correo="${
              e.correo
            }" modificar is-warning'><i class="fa-solid fa-pencil" style="color: #004080;"></i></button><button data-correo="${
              e.correo
            }" class='borrar button is-danger'><i class="fa-solid fa-x" style="color: #ffffff;"></i></button></td>
            </tr>`)
          );
        });

        function convertRowToInputs(row) {
          row.find("td").each(function () {
            if ($(this).children("button").length == 0) {
              var text = $(this).text().trim();
              var input = $('<input class="input" type="text">').val(text);
              $(this).html(input);
            }
          });
          var btnMod= row.find(".modificar");
          var btnBorrar = row.find(".borrar");
          var confirmBtn = $(
            '<button class="confirm button is-success"><i class="fa-solid fa-check" style="color: #ffffff;"></i></button>'
          );
          var cancelBtn = $(
            '<button class="cancel button is-danger"><i class="fa-solid fa-times" style="color: #ffffff;"></i></button>'
          );
          btnMod.replaceWith(confirmBtn);
          btnBorrar.replaceWith(cancelBtn);

          $(".confirm").unbind().click(function () {
            let aBuscar = $(this).parent().parent().data("correo");
            if ($(this).parent().parent().children("td").eq(3).children("input").eq(0).val() == '') {
              let correo = $(this).parent().parent().children("td").eq(0).children("input").eq(0).val();
              let nombrex = $(this).parent().parent().children("td").eq(1).children("input").eq(0).val();
              let cif = $(this).parent().parent().children("td").eq(4).children("input").eq(0).val();
              let telefono = $(this).parent().parent().children("td").eq(5).children("input").eq(0).val();
              let direccion = $(this).parent().parent().children("td").eq(6).children("input").eq(0).val();
              let provincia = $(this).parent().parent().children("td").eq(7).children("input").eq(0).val();
              $.ajax({
                url: ruta_modificar_empresa,
                method: "POST",
                dataType: "json",
                data: {
                  aBuscar: aBuscar,
                  correo: correo,
                  nombre: nombrex,
                  cif: cif,
                  telefono: telefono,
                  direccion: direccion,
                  provincia: provincia
                },
                async: true,
                success: function (data) {
                  alert("Empresa modificada!");
                  devolverUsuarios(nombre, rol, usuarios, empresas);
                },
                error: function (errorThrown) {
                  console.log(errorThrown);
                },
              });
            } else {
              let aBuscar = $(this).parent().parent().data("correo");
              let correo = $(this).parent().parent().children("td").eq(0).children("input").eq(0).val();
              let nombrex = $(this).parent().parent().children("td").eq(1).children("input").eq(0).val();
              let apellidos = $(this).parent().parent().children("td").eq(2).children("input").eq(0).val();
              let rolx = $(this).parent().parent().children("td").eq(3).children("input").eq(0).val();
              let dni = $(this).parent().parent().children("td").eq(4).children("input").eq(0).val();
              let telefono = $(this).parent().parent().children("td").eq(5).children("input").eq(0).val();

              $.ajax({
                url: ruta_modificar_usuario,
                method: "POST",
                dataType: "json",
                data: {
                  aBuscar: aBuscar,
                  correo: correo,
                  nombre: nombrex,
                  apellidos: apellidos,
                  rol: rolx,
                  dni: dni,
                  telefono: telefono
                },
                async: true,
                success: function (data) {
                  alert("Usuario modificado!");
                  devolverUsuarios(nombre, rol, usuarios, empresas);
                },
                error: function (errorThrown) {
                  console.log(errorThrown);
                },
              });
            }
          })
        }

        function convertRowToNormal(row) {
          row.find("td input").each(function () {
            var text = $(this).val();
            $(this).replaceWith(text);
          });
          var confirmBtn = row.find(".confirm");
          var cancelBtn = row.find(".cancel");
          var modificarBtn = $(
            '<button class="modificar button is-warning"><i class="fa-solid fa-pencil" style="color: #004080;"></i></button>'
          );
          var borrarBtn = $(
            '<button class="borrar button is-danger"><i class="fa-solid fa-x" style="color: #ffffff;"></i></button>'
          );
          confirmBtn.replaceWith(modificarBtn);
          cancelBtn.replaceWith(borrarBtn);

          $(".borrar")
          .unbind()
          .click(function () {
            let c = confirm("Estás seguro de que quieres borrar el usuario?");
            if (c) {
              $.ajax({
                url: ruta_borrar_usuario,
                method: "POST",
                dataType: "json",
                data: {
                  correo: $(this).data("correo"),
                },
                async: true,
                success: function (data) {
                  alert("Usuario borrado!");
                  devolverUsuarios(nombre, rol, usuarios, empresas);
                },
                error: function (errorThrown) {
                  console.log(errorThrown);
                },
              });
            }
          });
        }

        $(document).on("click", ".modificar", function () {
          var row = $(this).closest("tr");
          convertRowToInputs(row);
        });

        $(document).on("click", ".cancel", function () {
          var row = $(this).closest("tr");
          convertRowToNormal(row);
          devolverUsuarios(nombre, rol, usuarios, empresas);
        });

        $(".borrar")
          .unbind()
          .click(function () {
            let c = confirm("Estás seguro de que quieres borrar el usuario?");
            if (c) {
              $.ajax({
                url: ruta_borrar_usuario,
                method: "POST",
                dataType: "json",
                data: {
                  correo: $(this).data("correo"),
                },
                async: true,
                success: function (data) {
                  alert("Usuario borrado!");
                  devolverUsuarios(nombre, rol, usuarios, empresas);
                },
                error: function (errorThrown) {
                  console.log(errorThrown);
                },
              });
            }
          });
      }
    },
    error: function (errorThrown) {
      console.log(errorThrown);
    },
  });
};

$("#btn-crear-usu").click(() => {
  // Empresa
  if ($("#input-dir").val().length > 0) {
    $.ajax({
      url: ruta_crear_empresa,
      method: "POST",
      data: {
        correo: $("#input-correo").val(),
        pass: $("#input-pass").val(),
        nombre: $("#input-nombre").val(),
        tel: $("#input-tel").val(),
        cif: $("#input-cif").val(),
        dir: $("#input-dir").val(),
        prov: $("#input-prov").val(),
      },
      async: true,
      dataType: "json",
      success: function (data) {
        alert("Empresa creada!");
        devolverUsuarios(nombre, rol, usuarios, empresas);
      },
      error: function (errorThrown) {
        console.log(errorThrown);
      },
    });
  } else {
    // Usuario
    $.ajax({
      url: ruta_crear_usuario,
      method: "POST",
      data: {
        correo: $("#input-correo").val(),
        pass: $("#input-pass").val(),
        nombre: $("#input-nombre").val(),
        tel: $("#input-tel").val(),
        dni: $("#input-dni").val(),
        ape: $("#input-ape").val(),
        rol: $("#select-rol").val(),
      },
      async: true,
      dataType: "json",
      success: function (data) {
        alert("Usuario creado!");
        devolverUsuarios(nombre, rol, usuarios, empresas);
      },
      error: function (errorThrown) {
        console.log(errorThrown);
      },
    });
  }
});

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
