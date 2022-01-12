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
    //$('#btnCerrarSesion').on('click', clickCerrarSesion)

    // En los "Insertar" de a continuación no se fuerza la ordenación, ya que PHP
    // nos habrá dado los elementos en orden correcto y sería una pérdida de tiempo.

    llamadaAjax("ProductoObtenerTodos.php", "",
        function(texto) {
            productosInicio = JSON.parse(texto);

            for (var i=0; i<productosInicio.length; i++) {
                domInsertar(productosInicio[i]);
                addProductoSelectFiltro("productos", productosInicio[i]); //-------------------
            }
            todosLosDatosCargados = true;
            document.getElementById("productos").addEventListener("click", realizarFiltro, false);

        },
        function(texto) {
            alert(productosInicio);
            notificarUsuario("Error Ajax al cargar al inicializar: " + texto);
        }
    );
}
function realizarFiltro(e) {
    //Aqui obtengo el valor que elije el usuario para hacer el filtrado
    var filtrarPor = e.target.value;
    if (filtrarPor == "Todos" && !todosLosDatosCargados) {
        //si el usuario ha filtrado por "Todos" y no estan ya cargados todos los datos entonces muestro todos los datos
        llamadaAjax("ProductoObtenerTodos.php", "",
            function(texto) {
                productosInicio = JSON.parse(texto);
                for (var i=0; i<productosInicio.length; i++) {
                    domInsertar(productosInicio[i]);
                }
                todosLosDatosCargados = true;
                document.getElementById("productos").addEventListener("click", realizarFiltro, false);
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
                    addProductoSelectFiltro("productos",producto[i]); //-------------------
                }
            },
            function(texto) {
                alert(productosInicio);
                notificarUsuario("Error Ajax al cargar al inicializar: " + texto);
            }
        );
    }
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
        var opcion = new Option(productoActual.denominacion, productoActual.denominacion);
        select.appendChild(opcion);
    }
    existe = false;
}
// ---------- GESTIÓN DEL DOM ----------


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

function domInsertar(productoNueva, enOrden=false) {
    // Si piden insertar en orden, se buscará su lugar. Si no, irá al final.ç
    if (enOrden) {
        for (let pos = 0; pos < divDatos.children.length; pos++) {
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
}

//funciones y eventos visuales en el article ticket

 var anadir=document.getElementById('boton');
anadir.addEventListener('click',CargarTicket);

   function CargarTicket(){
    let cantidad=document.getElementById('cantidad');
    if(cantidad.value <1){
        alert("No puedes comprar menos de 1 producto");
        cantidad.value=1;
    }else if(cantidad.value >20){
        alert("No puedes comprar mas de 20 productos");
        cantidad.value=1;
    }else{
        let posibleTicket = document.getElementById('posibleTicket');
        let producto = document.getElementById('productos').value;
        posibleTicket.innerHTML +="<p>"+ producto + "-------------------------------------------------------" + cantidad.value+"</p>";
    }
};









