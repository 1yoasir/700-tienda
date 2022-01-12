// TODO Quedaría pendiente poner un timer para actualizar lo local si actualizan el servidor. Una solución óptima sería poner timestamp de modificación en la tabla y pedir elementoqueseaObtenerModificadosDesde(timestamp), donde timestamp es la última vez que he pedido algo.



// ---------- VARIOS DE BASE/UTILIDADES ----------

window.onload = inicializar;
var productosInicio; //----
var todosLosDatosCargados = false;

function notificarUsuario(texto) {
    // TODO En lugar del alert, habría que añadir una línea en una zona de notificaciones, arriba, con un temporizador para que se borre solo en ¿5? segundos.
    alert(texto);
}

// TODO El parametro "parametros" podía ser directamente un objeto JS y lo convertimos dentro de esta función, en lugar de que lo convierta desde fuera el llamante.
function llamadaAjax(url, parametros, manejadorOK, manejadorError) {
    // TODO PARA DEPURACIÓN: alert("Haciendo ajax a " + url + "\nCon parámetros " + parametros);

    var request = new XMLHttpRequest();

    request.open("POST", url);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    request.onreadystatechange = function () {
        if (this.readyState == 4) { // 4 equivale a "hemos terminado".
            if (request.status == 200) { // 200 significa "todo bien".
                manejadorOK(request.responseText);
            } else {
                if (manejadorError != null) manejadorError(request.responseText);
            }
        }
    };

    request.send(parametros);
}

function extraerId(texto) {
    return texto.split('-')[1];
}

function objetoAParametrosParaRequest(objeto) {
    // Esto convierte un objeto JS en un listado de clave1=valor1&clave2=valor2&clave3=valor3
    return new URLSearchParams(objeto).toString();
}

function debug() {
    // Esto es útil durante el desarrollo para programar el disparado de acciones concretas mediante un simple botón.
}



// ---------- MANEJADORES DE EVENTOS / COMUNICACIÓN CON PHP ----------

// TODO Estaría genial que estos métodos no metieran la mano para nada en el DOM, sino que lo hicieran todo a través de métodos domTalCosa:
// Por ejemplo: disablearCamposPersonaCrear(), enablearCamposPersonaCrear(), obtenerObjetoPersonaDeCamposPersonaCrear()...

function inicializar() {
    btnCrear.onclick = clickCrear;
    btnCerrarSesion.onclick = clickCerrarSesion;
    //$('#btnCerrarSesion').on('click', clickCerrarSesion)

    // En los "Insertar" de a continuación no se fuerza la ordenación, ya que PHP
    // nos habrá dado los elementos en orden correcto y sería una pérdida de tiempo.

    llamadaAjax("ProductoObtenerTodos.php", "",
        function(texto) {
             productosInicio = JSON.parse(texto);

            for (var i=0; i<productosInicio.length; i++) {
                domInsertar(productosInicio[i]);
                addProductoSelectFiltro("selectTipos", productosInicio[i]); //-------------------
                addProductoSelectFiltro("selTipos", productosInicio[i]); //-----------------
            }
            todosLosDatosCargados = true;
            document.getElementById("selectTipos").addEventListener("click", realizarFiltro, false);

        },
        function(texto) {
            alert(productosInicio);
            notificarUsuario("Error Ajax al cargar al inicializar: " + texto);
        }
    );
}
function realizarFiltro(e) {
    eliminarTodosLosHijosDivDatos();
    //Aqui obtengo el valor que elije el usuario para hacer el filtrado
    var filtrarPor = e.target.value;
    if (filtrarPor == "Todos" && !todosLosDatosCargados) {
        //si el usuario ha filtrado por "Todos" y no estan ya cargados todos los datos entonces muestro todos los datos
        llamadaAjax("ProductoObtenerTodos.php", "",
            function(texto) {
                productosInicio = JSON.parse(texto);
                debugger
                for (var i=0; i<productosInicio.length; i++) {
                    domInsertar(productosInicio[i]);
                }
                todosLosDatosCargados = true;
                document.getElementById("selectTipos").addEventListener("click", realizarFiltro, false);
            },
            function(texto) {
                alert(productosInicio);
                notificarUsuario("Error Ajax al cargar al inicializar: " + texto);
            }
        );
    } else {
        //si el usuario a seleccionado un filtro determinado aplico la busqueda segun dicho filtro
        llamadaAjax("ProductoObtenerFiltrados.php?filtro="+filtrarPor, "",
            function(texto) {
                var producto = JSON.parse(texto);

                for (var i=0; i<producto.length; i++) {
                    domInsertar(producto[i]);
                    addProductoSelectFiltro("selectTipos",producto[i]); //-------------------
                }
            },
            function(texto) {
                alert(productosInicio);
                notificarUsuario("Error Ajax al cargar al inicializar: " + texto);
            }
        );
    }
}

//Este metodo elimina todos los div que el metodo de obtener todos los productos crea
function eliminarTodosLosHijosDivDatos() {
    var divHijos = document.getElementById("divDatos").children;
    var numDivs = divHijos.length;
    var cont = numDivs - 1;
    while (divHijos.length > 0) {
        divHijos[cont].remove();
        cont--;
    }
    todosLosDatosCargados = false;
}

function clickCerrarSesion() {
    $.ajax({
        type: 'POST',
        url: '../sesiones/SesionCerrar.php',
        data: {'clickCerrar': true},
    })
        .done(function (resultado) {
            // $('#result').html(resultado)
            window.location.href = "../sesiones/SesionFormulario.php";
        })
        .fail(function () {
            alert('Hubo un error al cerrar sesión');
        })
}

function clickCrear() {
    inpNombre.disabled = true;
    selTipos.disabled = true;
    inpPrecio.disabled = true;
    inpStock.disabled = true;

    let producto = {
        "id" : -1,
        "denominacion" : inpNombre.value,
        "tipo" : selTipos.value,
        "precio" : inpPrecio.value,
        "stock" : inpStock.value
    }


    llamadaAjax("ProductoCrear.php", objetoAParametrosParaRequest(producto),
        function(texto) {
        debugger
            // Se re-crean los datos por si han modificado/normalizado algún valor en el servidor.
            var producto = JSON.parse(texto);
             //productosInicio = JSON.parse(texto);
            // Se fuerza la ordenación, ya que este elemento podría no quedar ordenado si se pone al final.
            domInsertar(producto, true);

            inpNombre.value = "";
            inpNombre.disabled = false;
            selTipos.value = "";
            selTipos.disabled = false;
            inpPrecio.value = "";
            inpPrecio.disabled = false;
            inpStock.value = "";
            inpStock.disabled = false;
        },
        function (texto) {
            notificarUsuario("Error Ajax al crear: " + texto);
            inpNombre.disabled = false;
            selTipos.disabled = false;
            inpPrecio.disabled = false;
            inpStock.disabled = false;
        }
    );
}

function addProductoSelectFiltro(nombreSelectHTMl, productoActual) {
    //TODO SOY CONSCIENTE de que seria mejor inicializar el select al cargar pagina
    //para no tener que hacerlo por cada elemento de la BBDD.
    var select = document.getElementById(nombreSelectHTMl);
    var optionsExistentes = select.options;
    var existe = false;
    for (let i = 0; i < optionsExistentes.length; i++) {
        if (optionsExistentes[i].value == productoActual.tipo) {
            existe = true;
        }
    }
    if (!existe) {
        var opcion = new Option(productoActual.tipo, productoActual.tipo);
        select.appendChild(opcion);
    }
    existe = false;
}
function blurModificar(input) {
    let div = input.parentElement.parentElement;
    let producto = domDivAObjeto(div);

    llamadaAjax("ProductoActualizar.php", objetoAParametrosParaRequest(producto),
        function (texto) {
            if (texto != "null") {
                // Se re-crean los datos por si han modificado/normalizado algún valor en el servidor.
                producto = JSON.parse(texto);
                domModificar(producto);
            } else {
                notificarUsuario("Error Ajax al modificar: " + texto);
            }
        },
        function (texto) {
            notificarUsuario("Error Ajax al modificar: " + texto);
        }
    );
}

function clickEliminar(id) {
    llamadaAjax("ProductoEliminar.php", "id=" + id,
        function (texto) {
            var operacionOK = JSON.parse(texto);

            debugger;
            if (operacionOK == "NOSESION") {
                alert("Nos vamos, porque no hay sesión.");
                window.location.href = "../sesiones/SesionFormularioX.php";
                alert("No debería verse esto");
            }

            if (operacionOK) {
                domEliminar(id);
                eliminarAviso(id);
            } else {
                notificarUsuario("Error Ajax al eliminar: " + texto);
            }
        },
        function (texto) {
            notificarUsuario("Error Ajax al eliminar: " + texto);
        }
    );
}



// ---------- GESTIÓN DEL DOM ----------

function domCrearDivInputText(textoValue, codigoOnblur) {
    let div = document.createElement("div");
        let input = document.createElement("input");
                input.setAttribute("type", "text");
                input.setAttribute("value", textoValue);
                input.setAttribute("onblur", codigoOnblur + " return false;");
    div.appendChild(input);

    return div;
}

function domCrearDivSelect(options, codigoOnblur) {
    let div = document.createElement("div");
    let select = document.createElement("select");
    for (let i = 0; i < options.length; i++) {
        var opcion = new Option(options[i], options[i]);
        select.appendChild(opcion);
    }
    option.setAttribute("onblur", codigoOnblur + " return false;");
    div.appendChild(select);

    return div;
}

function domCrearDivIcon(clase, codigoOnclick) {
    let div = document.createElement("div");
        let i = document.createElement("i");
                i.setAttribute("class", clase);
                i.setAttribute("onclick", codigoOnclick + " return false;");
    div.appendChild(i);

    return div;
}

function domObjetoADiv(producto) {
    let div = document.createElement("div");
            div.setAttribute("id", "producto-" + producto.id);
    div.appendChild(domCrearDivInputText(producto.denominacion, "blurModificar(this);"));
    div.appendChild(domCrearDivInputText(producto.tipo, "blurModificar(this);"));
    div.appendChild(domCrearDivInputText(producto.precio, "blurModificar(this);"));
    div.appendChild(domCrearDivInputText(producto.stock, "blurModificar(this);"));
    div.appendChild(domCrearDivIcon("fa fa-trash", "clickEliminar(" + producto.id + ");"));

    return div;
}

function domObtenerDiv(pos) {
    return divDatos.children[pos];
}

function domDivAObjeto(div) {
    return { // Devolvemos un objeto recién creado con los datos que hemos obtenido.
        "id": extraerId(div.id),
        "denominacion": div.children[0].children[0].value,
        "tipo": div.children[1].children[0].value,
        "precioUnidad": div.children[2].children[0].value,
        "stock": div.children[3].children[0].value,
    }
}

function domObtenerObjeto(pos) {
    let div = domObtenerDiv(pos);
    return domDivAObjeto(div);
}

function domEjecutarInsercion(pos, producto) {
    let divReferencia = domObtenerDiv(pos);
    let divNuevo = domObjetoADiv(producto);

    divDatos.insertBefore(divNuevo, divReferencia);
}

function domInsertar(productoNueva, enOrden=false) {
    // Si piden insertar en orden, se buscará su lugar. Si no, irá al final.ç
    if (enOrden) {
        for (let pos=0; pos < divDatos.children.length; pos++) {
            let productoActual = domObtenerObjeto(pos);
            let cadenaActual = productoActual.denominacion + productoActual.tipo + productoActual.precioUnidad + productoActual.stock + productoNueva.id;
            let cadenaNueva = productoNueva.denominacion + productoNueva.tipo + productoNueva.precioUnidad + productoNueva.stock + productoNueva.id;

            if (cadenaNueva.localeCompare(cadenaActual) == -1) {
                // Si la categoría nueva va ANTES que la actual, este es el punto en el que insertarla.
                domEjecutarInsercion(pos, productoNueva);
                return;
            }
        }
    }

    // Si llegamos hasta aquí, insertamos al final.
    domEjecutarInsercion(divDatos.children.length, productoNueva);
}

function domLocalizarPosicion(idBuscado) {
    var divs = divDatos.children;

    for (var pos=0; pos < divs.length; pos++) {
        let div = divs[pos];
        let productoActualId = extraerId(div.id);

        if (productoActualId == idBuscado) return (pos);
    }

    return -1;
}

function domEliminar(id) {
    let pos = domLocalizarPosicion(id);
    let div = domObtenerDiv(pos);
    div.remove();
}

function eliminarAviso(id) {
    alert("El producto " +  id  + " ha sido eliminado correctamente");
}

function domModificar(producto) {
    domEliminar(producto.id);

    // Se fuerza la ordenación, ya que este elemento podría no quedar ordenado si se pone al final.
    domInsertar(producto, true);
}