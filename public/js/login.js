$("#link-pass").click((e) => {
    e.preventDefault();
})

const enviarCorreo = () => {
    $.ajax({
        url: ruta_enviar_correo,
        method: 'POST',
        dataType: 'json',
        data: {
            'mail': $("#correo").val()
        },
        async: false,
        success: function (data) {
            alert('Revisa tu correo electrónico!');
        }, 
        error: function (errorThrown) {
            console.log(errorThrown);
        }
    });
}

const enviarFormulario = () => {
    $("#form-login").submit();
}
