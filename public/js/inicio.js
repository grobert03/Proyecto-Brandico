
$(document).ready(() => {
    let pagina = 1;

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

    const darLike = (id) => {
        console.log('xd')
        $.ajax({
            url: ruta_dar_like,
            data: { "id": id },
            type: "POST",
            dataType: "json",
            success: function (data) {
                console.log(data);
            },
            error: function (err) {
                console.log(err);
            }
        });
    }

    const quitarLike = (id) => {
        $.ajax({
            url: ruta_quitar_like,
            data: { "id": id },
            type: "POST",
            dataType: "json",
            success: function (data) {


            },
            error: function (err) {
                console.log(err);
            }
        });
    }

    const loadPublicaciones = () => {
        $.ajax({
            url: ruta_devolver_publicaciones,
            type: 'POST',
            data: {
                "pagina": pagina
            },
            success: function (data) {
                console.log(data)
                data.forEach(d => {
                    $("#principal").append($(`<section class="publicacion section feed">
					<div class="container">
						<div class="card">
							<div class="container">
								<div
									class="content">
									<!-- Post Card -->
									<div class="card">
										<div class="card-content">
											<div class="media">
												<div class="media-left">
													<figure class="image is-64x64 mr-0">
														<img class="is-rounded" src="${d.perfil}" alt="Imágen de perfil">
													</figure>
												</div>
												<div class="media-content">
													<div class="title is-4">${d.autor}</div>
													<div class="subtitle is-6">
                                                    <div>${d.correo}</div>
                                                    <small>${d.fecha.date.substring(0, 16)}</small>
                                                    </div>
                                                    
												</div>
											</div>
											<div class="content">
												<p>${d.texto}</p>
												<img src="${d.imagen}" alt="Imagen publicacion">
											</div>
											
											<br>
											<div class="buttons">
												<button data-twt='${d.id}' class='btn-like button ${d.le_gusta ? "is-danger" : "is-light"}'>
													<span class="icon">
														<ion-icon name="heart"></ion-icon>
													</span>
													<span id='likes-${d.id}'}>${d.likes}</span>
												</button>
												<button data-twt='${d.id}' class="btn-comment button is-info">
													<span class="icon">
														<ion-icon name="chatbubble"></ion-icon>
													</span>
													<span>0</span>
												</button>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>`))
                });
                
                $(".btn-like").unbind().click(function() {
                    let id = $(this).data("twt");
                    if ($(this).hasClass("is-danger")) {
                        $(`#likes-${id}`).text(Number($(`#likes-${id}`).text()) - 1);
                        quitarLike(id);
                    } else {
                        $(`#likes-${id}`).text(Number($(`#likes-${id}`).text()) + 1);
                        darLike(id);
                    }
                    $(this).toggleClass("is-danger");
                    $(this).toggleClass("is-light");
                });

                $(".btn-comment").unbind().click(function() {
                    
                });
        
                pagina++;
            },
            error: function (err) {
                console.log(err);
            }
        })
    }

    loadPublicaciones();

    $(window).scroll(() => {
        if (Math.ceil($(window).scrollTop() + $(window).height()) == $(document).height()) {
            loadPublicaciones();
        }
    })
});