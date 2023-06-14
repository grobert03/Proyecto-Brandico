let filtro_nombre = "";
let filtro_rol = false;
let filtro_usuarios = true;
let filtro_empresas = true;
let filtro_correo = "";
let filtro_direccion = "";
let filtro_provincia = "";
let filtro_cif = "";

$(".rotate").click(function () {
  if ($(this).children("ion-icon").hasClass("down")) {
    $(".input:not(:first)").val("");
    filtro_correo = "";
    filtro_direccion = "";
    filtro_provincia = "";
    filtro_cif = "";
    devolverUsuarios();
  }
  $(this).children("ion-icon").toggleClass("down");
});

$("#btn-borrar-datos").click(function () {
  $("#formulario-crear input").val("");
});

const devolverUsuarios = () => {
  $(".user-item").remove();
  $.ajax({
    url: ruta_devolver_usuarios,
    method: "POST",
    dataType: "json",
    data: {
      nombre: filtro_nombre,
      rol: filtro_rol,
      usuarios: filtro_usuarios,
      empresas: filtro_empresas,
      correo: filtro_correo,
      direccion: filtro_direccion,
      provincia: filtro_provincia,
      cif: filtro_cif,
    },
    async: true,
    success: function (data) {
      console.log(data);
      data.forEach((p) => {
        $(".user-list").append(
          $(`<dt class="user-item" x-data="{modificar: false, empresa: ${
            p.es_empresa
          }}">
        <div class='izquierda'>
        <figure class="avatar image  is-64x64 mr-0">
            <img class="is-rounded" src="${p.foto}" alt="User Avatar">
        </figure>
        <div class="user-details">
            <p class="user-type" x-show="!modificar">${
              p.es_empresa
                ? `EMPRESA (${p.direccion}, ${p.provincia})`
                : "USUARIO"
            }</p>
            <h4 class="user-name" x-show="!modificar" ><a href="${ruta_perfil}/${
            p.id
          }">${p.nombre}</a></h4>
            <p class="user-email" x-show="!modificar">${p.correo}</p>
            <div data-usu="${
              p.id
            }" class="modificar-datos" x-show="modificar" x-transition x-cloak>

              <div class="field">
                <label class="label">Nombre:</label>
                <input class="input" id="mod_nombre-${p.id}" value="${
            p.nombre
          }">
              </div>
              <div class="field">
                <label class="label">Correo:</label>
                <input class="input" type="email" id="mod_correo-${
                  p.id
                }" value="${p.correo}">
              </div>
              <div class="field">
                <label class="label">Clave:</label>
                <input class="input" type="password" id="mod_clave-${p.id}">
              </div>
             
              <div class="field" x-cloak>
                <label class="label">Teléfono:</label>
                <input class="input" type="text" id="mod_tel-${p.id}" value="${
            p.telefono ? p.telefono : ""
          }">
              </div>
              <div class="field" x-show="empresa" x-cloak>
                <label class="label">CIF:</label>
                <input class="input" type="text" id="mod_cif-${p.id}" value="${
            p.cif ? p.cif : ""
          }">
              </div>
              <div class="field" x-show="empresa" x-cloak>
                <label class="label">Dirección:</label>
                <input class="input" type="text" id="mod_dir-${p.id}" value="${
            p.direccion ? p.direccion : ""
          }">
              </div>
              <div class="field" x-show="empresa" x-cloak>
                <label class="label">Provincia:</label>
                <input class="input" type="text" id="mod_prov-${p.id}" value="${
            p.provincia ? p.provincia : ""
          }">
              </div>
              <div class="field select" x-cloak>
              <select id="mod_tipo-${p.id}" @change="empresa = !empresa">
                <option value='0' :selected="!empresa">Usuario</option>
                <option value='1' :selected="empresa">Empresa</option>
              </select>
            </div>
              <div class="buttons field">
                <button data-usu="${
                  p.id
                }" class="btn-modificar-usu button is-success">Guardar</button>
              </div>
            </div>
        </div>
        
        </div>

        <div class="buttons">
          <button  @click="modificar = !modificar" data-usu="${
            p.id
          }" class="button btn-modificar-usuario is-warning"><i class="fa-solid fa-pen-to-square" style="color: #000000;"></i></button>
          <button data-usu="${
            p.id
          }" class="button is-danger btn-borrar-usuario"><i class="fa-solid fa-trash" style="color: #ffffff;"></i></button>
        </div>
    </dt>`)
        );
      });

      $(".btn-modificar-usu")
        .unbind()
        .click(function () {
          let id = $(this).data("usu");
          $("#error").remove();
          if (!validateEmail($(`#mod_correo-${id}`).val())) {
            $(this)
              .parent()
              .parent()
              .prepend(
                $(
                  `<p id='error' style='color: red; font-weight: bold' class='mt-4'>Formato del correo incorrecto!</p>`
                )
              );
            return false;
          }
          if (
            $(`#mod_clave-${id}`).val() != 0 &&
            !validatePassword($(`#mod_clave-${id}`).val())
          ) {
            $(`#mod_nombre-${id}`)
              .parent()
              .prepend(
                $(
                  `<p id='error' style='color: red; font-weight: bold' class='mt-4'>La contraseña debe tener como mínimo 8 carácteres, como mínimo una letra mayúscula, y sin símbolos especiales!</p>`
                )
              );
            return false;
          }
          $.ajax({
            url: ruta_modificar_usuario,
            method: "POST",
            dataType: "json",
            data: {
              id: id,
              mod_nombre: $(`#mod_nombre-${id}`).val(),
              mod_correo: $(`#mod_correo-${id}`).val(),
              mod_clave: $(`#mod_clave-${id}`).val(),
              mod_tipo: $(`#mod_tipo-${id}`).val(),
              mod_tel: $(`#mod_tel-${id}`).val(),
              mod_cif: $(`#mod_cif-${id}`).val(),
              mod_dir: $(`#mod_dir-${id}`).val(),
              mod_prov: $(`#mod_prov-${id}`).val(),
            },
            success: function (data) {
              if (data.error) {
                $(`#mod_nombre-${id}`)
                  .parent()
                  .prepend(
                    $(
                      `<p id='error' style='color: red; font-weight: bold' class='mt-4'>${data.error}</p>`
                    )
                  );
              } else {
               devolverUsuarios();
              }
            },
            error: function (err) {
              console.log(err);
            },
          });
        });

      $(".btn-borrar-usuario")
        .unbind()
        .click(function () {
          let conf = confirm("Estás seguro de que quieres borrar el usuario?");
          let id = $(this).data("usu");
          if (conf) {
            $.ajax({
              url: ruta_borrar_usuario,
              method: "POST",
              dataType: "json",
              data: {
                id: id,
              },
              success: function (data) {
                console.log(data);
                devolverUsuarios();
              },
              error: function (err) {
                console.log(err);
              },
            });
          }
        });
    },
    error: function (errorThrown) {
      console.log(errorThrown);
    },
  });
};

const validateEmail = (email) => {
  const emailRegex = /^\w+([\.-]?\w+)@\w+([\.-]?\w+)(\.\w{2,3})+$/;
  if (!emailRegex.test(email)) {
    return false;
  } else {
    return true;
  }
};

const validatePassword = (password) => {
  const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
  if (!passwordRegex.test(password)) {
    return false;
  } else {
    return true;
  }
};

const validateCIF = (cif) => {
  const cifRegex = /^[A-Z]\d{8}|[A-Z]\d{7}[A-Z]$/;
  if (!cifRegex.test(cif)) {
    return false;
  } else {
    return true;
  }
};

$("#btn-crear-usu").click(() => {
  $("#error").remove();
  if (!validateEmail($("#input-correo").val())) {
    $("#formulario-crear .title").after(
      $(
        `<p id='error' style='color: red; font-weight: bold' class='mt-4'>Formato del correo incorrecto!</p>`
      )
    );
    return false;
  }
  if (!validatePassword($("#input-pass").val())) {
    $("#formulario-crear .title").after(
      $(
        `<p id='error' style='color: red; font-weight: bold' class='mt-4'>La contraseña debe tener como mínimo 8 carácteres, como mínimo una letra mayúscula, y sin símbolos especiales!</p>`
      )
    );
    return false;
  }
  if ($("#radio-usu")[0].checked) {
    if ($("#input-nombre").val().replace(/\s/g, "").length == 0) {
      $("#formulario-crear .title").after(
        $(
          `<p id='error' style='color: red; font-weight: bold' class='mt-4'>Todos los campos son obligatorios!</p>`
        )
      );
      return false;
    }
    $.ajax({
      url: ruta_crear_usuario,
      method: "POST",
      data: {
        empresa: false,
        correo: $("#input-correo").val(),
        pass: $("#input-pass").val(),
        nombre: $("#input-nombre").val(),
        rol: $("#select-rol").val(),
      },
      async: true,
      dataType: "json",
      success: function (data) {
        if (data.error) {
          $("#formulario-crear .title").after(
            $(
              `<p id='error' style='color: red; font-weight: bold' class='mt-4'>${data.error}</p>`
            )
          );
        } else {
          $("#formulario-crear .title").after(
            $(
              `<p id='error' style='color: green; font-weight: bold' class='mt-4'>Usuario creado!</p>`
            )
          );
          devolverUsuarios();
        }

        function esconder() {
          $("#error").remove();
        }

        setTimeout(esconder, 3000);
      },
      error: function (errorThrown) {
        console.log(errorThrown);
      },
    });
  } else {
    if (!validateCIF($("#input-cif").val())) {
      $("#formulario-crear .title").after(
        $(
          `<p id='error' style='color: red; font-weight: bold' class='mt-4'>Formato del CIF incorrecto!</p>`
        )
      );
      return false;
    }
    if (
      $("#input-nombre").val().replace(/\s/g, "").length == 0 ||
      $("#input-tel").val().replace(/\s/g, "").length == 0 ||
      $("#input-dir").val().replace(/\s/g, "").length == 0 ||
      $("#input-prov").val().replace(/\s/g, "").length == 0
    ) {
      $("#formulario-crear .title").after(
        $(
          `<p id='error' style='color: red; font-weight: bold' class='mt-4'>Todos los campos son obligatorios!</p>`
        )
      );
      return false;
    }
    $.ajax({
      url: ruta_crear_usuario,
      method: "POST",
      data: {
        empresa: true,
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
        if (data.error) {
          $("#formulario-crear .title").after(
            $(
              `<p id='error' style='color: red; font-weight: bold' class='mt-4'>${data.error}</p>`
            )
          );
        } else {
          $("#formulario-crear .title").after(
            $(
              `<p id='error' style='color: green; font-weight: bold' class='mt-4'>Empresa creada!</p>`
            )
          );
          devolverUsuarios();
        }

        function esconder() {
          $("#error").remove();
        }
        setTimeout(esconder, 3000);
      },
      error: function (errorThrown) {
        console.log(errorThrown);
      },
    });
  }
});

devolverUsuarios();

$("#input_nombre").on("keyup", () => {
  filtro_nombre = $("#input_nombre").val();
  devolverUsuarios();
});

$("#check_usu").on("click", () => {
  filtro_usuarios = !filtro_usuarios;
  devolverUsuarios();
});

$("#check_empresa").on("click", () => {
  filtro_empresas = !filtro_empresas;
  devolverUsuarios();
});

$("#check_admin").on("click", () => {
  filtro_rol = !filtro_rol;
  devolverUsuarios();
});

$("#input_correo").on("keyup", () => {
  filtro_correo = $("#input_correo").val();
  devolverUsuarios();
});

$("#input_direccion").on("keyup", () => {
  filtro_direccion = $("#input_direccion").val();
  devolverUsuarios();
});

$("#input_provincia").on("keyup", () => {
  filtro_provincia = $("#input_provincia").val();
  devolverUsuarios();
});

$("#input_cif").on("keyup", () => {
  filtro_cif = $("#input_cif").val();
  devolverUsuarios();
});

$(".texto figure, .texto > div").click(() => {
  window.location.href = ruta_inicio;
});
