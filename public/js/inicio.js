$("#publicar-mensaje").click(() => {
    let formulario = $("#formulario-publicar")[0];
    $.ajax({
        url: ruta_crear_publicacion,
        data: new FormData(formulario),
        type: "POST",
        contentType: false,
        processData: false,
        dataType: "json",
        success: function (data) {
            console.log(data);
        },
        error: function (err) {
            console.log(err);
        }
    });
});