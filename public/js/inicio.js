
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
                    if (data.error) {
                        $(".file").after($(`<p id='error' style='color: red; font-weight: bold' class='mt-4'>${data.error}</p>`));

                        function esconder() {
                            $("#error").remove();
                        }

                        setTimeout(esconder, 3000);
                    } else {
                        $("textarea").eq(0).css("border", "1px solid transparent");

                        pagina = 1;
                        $(".publicacion").remove();
                        loadPublicaciones();
                    }

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
											<div class="buttons" x-data="{borrar: false}">
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
													<span id='nr-comentarios-${d.id}'>${d.comentarios.length}</span>
												</button>
                                                ${correo == d.correo || es_admin ? `<button @click="borrar = !borrar" x-show="!borrar"  class="button btn-borrar-post">
                                                <span class="icon">
                                                    <ion-icon name="trash-outline" style="color: red;"></ion-icon>
                                                </span>
                                            </button>
                                            <span x-show="borrar">¿Estás seguro? Los comentarios se borrarán también! </span>
                                            <button data-cod="${d.id}" @click="borrar = borrar" class="button btn-confirmar-borrar ml-3" x-show="borrar">
                                                <ion-icon name="checkmark-outline" style="color: green;"></ion-icon>
                                            </button>
                                            <button @click="borrar = !borrar" class="btn-cancelar button" x-show="borrar">
                                                <ion-icon name="close-outline"></ion-icon>
                                            </button>`: ''}
                                                
											</div>
                                            <div class='comments comments-${d.id}' x-show="comentarios">
                                                <textarea id='textarea-${d.id}' class='textarea mb-3' maxlength="180" class='tu-comentario' placeholder='Escribe un comentario...'></textarea>
                                                <button class='button is-info crear-comment' data-post="${d.id}">Enviar</button>
                                                <div>${d.comentarios.map((e) => `
                                                <article class="media comentario" id="comentario-${e.id}">
                                                <figure class="media-left">
                                                  <p class="image is-48x48">
                                                    <img class='is-rounded' src="${e.foto}">
                                                  </p>
                                                </figure>
                                                <div class="media-content">
                                                  <div class="content">
                                                    <p x-data="{borrar_comment: false}">
                                                      <strong>${e.autor}</strong>
                                                      <br>
                                                      <span>${e.contenido}</span>
                                                      <br>
                                                      <small><span id='likes-com-${e.id}'>${e.likes}</span> · <ion-icon data-com='${e.id}' class='${e.le_gusta ? 'has-text-danger' : 'has-text-gray'} btn-like-com ' name="heart"></ion-icon> · ${e.fecha.date.substring(0, 16)} ${e.correo == correo || es_admin ? ` · <ion-icon @click="borrar_comment = !borrar_comment" class="trash-icon" name="trash-outline" style='color: red;' x-show="!borrar_comment"></ion-icon>
                                                      <span x-show="borrar_comment">¿Estás seguro?</span>
                                      
                                                        <ion-icon name="checkmark-outline" data-com='${e.id}' data-post='${d.id}' class="trash-icon btn-confirmar-borrar-comment" style="color: green;" x-show="borrar_comment"></ion-icon>
                                                        <ion-icon @click="borrar_comment = !borrar_comment" class="trash-icon" name="close-outline" x-show="borrar_comment"></ion-icon>
                                                   

                                                    ` : ''}</small>
                                                    </p>
                                                  </div>
                                                </div>
                                                
                                              </article>
                                                `).join("")}</div>
                                            </div>
                                            <div id='personas-${d.id}' class='personas'>
                                                <h3>Likes:</h3>
                                                ${d.personas.map((x) => `<div class='persona-${x.id}'><img class='image is-48x48 is-rounded' src='${x.foto}'><span>${x.nombre}</span></div>`).join("")}
                                            </div>
										</div>
									</div>
                                    
                                </div>
                                
							</div>
						</div>
					</div>
				</section>`))
                });

                

                $(".btn-confirmar-borrar").unbind().click(function () {
                    let id = $(this).data("cod");
                    $.ajax({
                        url: ruta_borrar_post,
                        method: "POST",
                        dataType: "json",
                        data: {
                            "id": id
                        },
                        success: function (data) {
                            $(`#publicacion-${id}`).remove();
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    })
                });

                $(".btn-confirmar-borrar-comment").unbind().click(function () {
                    let id = $(this).data("com");
                    let id_post = $(this).data("post")
                    $.ajax({
                        url: ruta_borrar_comentario,
                        method: "POST",
                        dataType: "json",
                        data: {
                            "id": id
                        },
                        success: function (data) {
                            $(`#comentario-${id}`).remove();
                            $(`#nr-comentarios-${id_post}`).text(Number($(`#nr-comentarios-${id_post}`).text()) - 1);
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    })
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

                $(".btn-like").click(function (e) {
                    e.stopImmediatePropagation();
                    let id = $(this).data("twt");
                    console.log($(this))
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

                $(".btn-like").hover(function(e) {
                    e.stopImmediatePropagation();

                    let id = $(this).data("twt");
                    $(`#personas-${id}`).show();

                }, function() {
                    let id = $(this).data("twt");
                    $(`#personas-${id}`).hide();
                });

                $(".crear-comment").unbind().click(function () {
                    let id = $(this).data("post");
                    let scroll = $(window).scrollTop();
                    if ($(`#textarea-${id}`).val().length == 0) {
                        $(`#textarea-${id}`).toggleClass("error");
                    } else {
                        if ($(`#textarea-${id}`).hasClass("error")) {
                            $(`#textarea-${id}`).toggleClass("error");
                        }

                        $.ajax({
                            url: ruta_crear_comentario,
                            data: { "id": id, "texto": $(`#textarea-${id}`).val() },
                            type: "POST",
                            dataType: "json",
                            success: function (data) {
                                console.log(data)
                                $(`#textarea-${id}`).val('');
                                $(`#nr-comentarios-${id}`).text(Number($(`#nr-comentarios-${id}`).text()) + 1);
                                $(`.comments-${id} > div`).prepend(`<article class="media comentario" id="comentario-${id}">
                                <figure class="media-left">
                                  <p class="image is-48x48">
                                    <img class='is-rounded' src="${data.comentario.foto}">
                                  </p>
                                </figure>
                                <div class="media-content">
                                  <div class="content">
                                    <p x-data="{borrar_comment: false}">
                                      <strong>${data.comentario.autor}</strong>
                                      <br>
                                      <span>${data.comentario.contenido}</span>
                                      <br>
                                      <small><span id='likes-com-${data.comentario.id}'>${data.comentario.likes}</span> · <ion-icon data-com='${data.comentario.id}' class='${data.comentario.le_gusta ? 'has-text-danger' : 'has-text-gray'} btn-like-com ' name="heart"></ion-icon> · ${data.comentario.fecha.date.substring(0, 16)} · <ion-icon @click="borrar_comment = !borrar_comment" class="trash-icon" name="trash-outline" style='color: red;' x-show="!borrar_comment"></ion-icon>
                                      <span x-show="borrar_comment">¿Estás seguro?</span>
                      
                                        <ion-icon name="checkmark-outline" data-com='${data.comentario.id}' data-post='${id}' class="trash-icon btn-confirmar-borrar-comment" style="color: green;" x-show="borrar_comment"></ion-icon>
                                        <ion-icon @click="borrar_comment = !borrar_comment" class="trash-icon" name="close-outline" x-show="borrar_comment"></ion-icon>
                                   </small>
                                    </p>
                                  </div>
                                </div>
                              </article>`);

                                $(".btn-confirmar-borrar").unbind().click(function () {
                                    let id = $(this).data("cod");
                                    $.ajax({
                                        url: ruta_borrar_post,
                                        method: "POST",
                                        dataType: "json",
                                        data: {
                                            "id": id
                                        },
                                        success: function (data) {
                                            $(`#publicacion-${id}`).remove();
                                        },
                                        error: function (err) {
                                            console.log(err);
                                        }
                                    })
                                });

                                $(".btn-confirmar-borrar-comment").unbind().click(function () {
                                    let id = $(this).data("com");
                                    let id_post = $(this).data("post")
                                    $.ajax({
                                        url: ruta_borrar_comentario,
                                        method: "POST",
                                        dataType: "json",
                                        data: {
                                            "id": id
                                        },
                                        success: function (data) {
                                            $(`#comentario-${id}`).remove();
                                            $(`#nr-comentarios-${id_post}`).text(Number($(`#nr-comentarios-${id_post}`).text()) - 1);
                                        },
                                        error: function (err) {
                                            console.log(err);
                                        }
                                    })
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