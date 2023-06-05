
$(document).ready(() => {
    let pagina = 1;

    $("#publicar-mensaje").click(() => {
        let formulario = $("#formulario-publicar")[0];
        if ($("textarea").eq(0).val().length == 0 && document.getElementById("fileInput").files.length == 0) {
            $("textarea").eq(0).css("border", "1px solid hsl(348, 100%, 61%)");
        } else {
            $.ajax({
                url: ruta_crear_publicacion,
                data: new FormData(formulario),
                type: "POST",
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (data) {
                    $("textarea").eq(0).css("border-color", "#dbdbdb");
                    pagina = 1;
                    $(".publicacion").remove();
                    loadPublicaciones();
                },
                error: function (err) {
                    console.log(err);
                }
            });
        }

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

    const darLikeComentario = (id) => {
        $.ajax({
            url: ruta_dar_like,
            data: { "id": id, "comentario": true },
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

    const quitarLikeComentario = (id) => {
        $.ajax({
            url: ruta_quitar_like,
            data: { "id": id, "comentario": true },
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
                    $("#principal").append($(`<section id="publicacion-${d.id}" class="publicacion section feed" x-data="{comentarios: false}">
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
												${d.imagen ? `<img src="${d.imagen}" alt="Imagen publicacion">` : ''}
                                                ${d.video ? `<video controls><source src='${d.video}'></video>` : ''}
											</div>
											<div class="buttons">
												<button data-twt='${d.id}' class='btn-like button ${d.le_gusta ? "is-danger" : "is-light"}'>
													<span class="icon">
														<ion-icon name="heart"></ion-icon>
													</span>
													<span id='likes-${d.id}'}>${d.likes}</span>
												</button>
												<button data-twt='${d.id}' @click="comentarios = !comentarios" class="btn-comment button" :class="comentarios ? 'is-light' : 'is-info'">
													<span class="icon">
														<ion-icon name="chatbubble"></ion-icon>
													</span>
													<span>${d.comentarios.length}</span>
												</button>
											</div>
                                            <div class='comments comments-${d.id}' x-show="comentarios">
                                                <textarea id='textarea-${d.id}' class='textarea mb-3' maxlength="180" class='tu-comentario' placeholder='Escribe un comentario...'></textarea>
                                                <button class='button is-info crear-comment' data-post="${d.id}">Enviar</button>
                                                <div>${d.comentarios.map((e) => `
                                                <article class="media comentario">
                                                <figure class="media-left">
                                                  <p class="image is-48x48">
                                                    <img class='is-rounded' src="${e.foto}">
                                                  </p>
                                                </figure>
                                                <div class="media-content">
                                                  <div class="content">
                                                    <p>
                                                      <strong>${e.autor}</strong>
                                                      <br>
                                                      ${e.contenido}
                                                      <br>
                                                      <small><span id='likes-com-${e.id}'>${e.likes} </span><ion-icon data-com='${e.id}' class='${e.le_gusta ? 'has-text-danger' : 'has-text-gray'} btn-like-com ' name="heart"></ion-icon> · ${e.fecha.date.substring(0, 16)}</small>
                                                    </p>
                                                  </div>
                                                </div>
                                              </article>
                                                `).join("")}</div>
                                            </div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>`))
                });

                $(".btn-like-com").unbind().click(function () {
                    let id = $(this).data("com");
                    if ($(this).hasClass("has-text-danger")) {
                        $(`#likes-com-${id}`).text(Number($(`#likes-com-${id}`).text()) - 1);
                        quitarLikeComentario(id);
                    } else {
                        $(`#likes-com-${id}`).text(Number($(`#likes-com-${id}`).text()) + 1);
                        darLikeComentario(id);
                    }
                    console.log($(this))
                    $(this).toggleClass("has-text-danger");
                });

                $(".btn-like").unbind().click(function () {
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

                $(".crear-comment").unbind().click(function () {
                    let id = $(this).data("post");
                    let scroll = $(window).scrollTop();
                    if ($(`#textarea-${id}`).val().length == 0) {
                        $(`#textarea-${id}`).css("border", "1px solid hsl(348, 100%, 61%)");
                    } else {
                        $(`#textarea-${id}`).val('');
                        $(`#textarea-${id}`).css("border-color", "#dbdbdb");
                        $.ajax({
                            url: ruta_crear_comentario,
                            data: { "id": id, "texto": $(`#textarea-${id}`).val() },
                            type: "POST",
                            dataType: "json",
                            success: function (data) {

                            },
                            error: function (err) {
                                console.log(err);
                            }
                        })
                    }


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