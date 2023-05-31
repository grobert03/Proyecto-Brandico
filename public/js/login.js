$("#link-pass").click((e) => {
    e.preventDefault();
})



const enviarCorreo = () => {
    $('#respuesta').remove();
    $.ajax({
        url: ruta_enviar_correo,
        method: 'POST',
        dataType: 'json',
        data: {
            'mail': $("#correo").val()
        },
        async: false,
        success: function (data) {
            if (data.enviado == false) {
                $("#form-header").after($(`<div id='respuesta' class="form-element">
                <p style='color: #dc2626; font-weight: bold;'>No hemos encontrado ninguna cuenta con ese correo!</p>
            </div>`));
            } else {
                $("#form-header").after($(`<div id='respuesta' class="form-element">
                <p style='color: #16a34a; font-weight: bold;'>Revisa tu correo electrónico!</p>
            </div>`));
            }
            
        },
        error: function (errorThrown) {

            console.log(errorThrown);
        }
    });
}

const enviarFormulario = () => {
    $("#form-login").submit();
}


const esconder = () => {
    $("#mensaje-error").remove();
}


setTimeout(esconder, 3000);