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
  $(".user-list").empty();
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
          $(`<dt class="user-item">
        <div class='izquierda'>
        <figure class="avatar image  is-64x64 mr-0">
            <img class="is-rounded" src="${p.foto}" alt="User Avatar">
        </figure>
        <div class="user-details">
            <p class="user-type">${p.es_empresa ? `EMPRESA (${p.direccion}, ${p.provincia})` : 'USUARIO'}</p>
            <h4 class="user-name">${p.nombre}</h4>
            <p class="user-email">${p.correo}</p>
        </div>
        </div>
        <div class="buttons">
          <button class="button is-warning"><i class="fa-solid fa-pen-to-square" style="color: #000000;"></i></button>
          <button class="button is-danger"><i class="fa-solid fa-trash" style="color: #ffffff;"></i></button>
        </div>
    </dt>`)
        );
      });
    },
    error: function (errorThrown) {
      console.log(errorThrown);
    },
  });
};

$("#btn-crear-usu").click(() => {
  if ($("#radio-usu")[0].checked) {
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
        $("#formulario-crear .title").after(
          $(
            `<p id='error' style='color: green; font-weight: bold' class='mt-4'>Usuario creado!</p>`
          )
        );

        function esconder() {
          $("#error").remove();
        }

        setTimeout(esconder, 3000);
        devolverUsuarios();
      },
      error: function (errorThrown) {
        console.log(errorThrown);
      },
    });
  } else {
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
        $("#formulario-crear .title").after(
          $(
            `<p id='error' style='color: green; font-weight: bold' class='mt-4'>Empresa creada!</p>`
          )
        );
        function esconder() {
          $("#error").remove();
        }
        setTimeout(esconder, 3000);

        devolverUsuarios();
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
