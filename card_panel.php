<?php require_once "vistas/parte_superior.php"; ?>



   <div class="container">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Bienvenido</h1>
    </div>

    <div class="card-container">

        <div class="card" id="card-operador">
            <div class="card-content">
                <h2>Echeverria</h2>
                <p>Juan Ignacio Gonzales</p>
                <p><i class="fas fa-clock"></i> Ingreso 08:00:00</p>
            </div>
        </div>

         <div class="card" id="card-opciones">
            <div class="card-content">
                <h2>Opciones</h2>
                <a class="btn" >Ver asistencia </a>
                <br>
                <a class="btn" >Cancelar</a>
            </div>
        </div>

        <div class="card">
            <div class="card-content">
                <h2>Usuario</h2>
                <p>Juana Peralta</p>
            </div>
        </div>
        <div class="card" id="proximaMedicacion">
            <div class="card-content">
                <h2>Próxima Medicación</h2>
                <p><i class="fas fa-clock"></i> 12:00:00<p>
            </div>
        </div>

        <div class="card" id="tarjetaMedicacion">
            <div class="card-content">
                <h2>Medicación</h2>
                <p>Olanzapina (mg): 1</p>
                <p>Lorazepan (mg): 1 1/2</p>
                <p>Carbomanepina (mg): 1 1/2</p>
                <p>respiridona (mg): 1 1/2</p>
            </div>
        </div>

         <div class="card-flex" data-toggle="modal" data-target="#myModal">
            <div class="card-content-flex">
                <div class="info">
                    <h2>Última Medicación</h2>
                    <p><i class="fas fa-clock"></i> 08:00:00 <p>
                    <p>Responsable:  <br> Maria Gutierrez</p>
                </div>
                <div class="image">
                    <img id="img" src="medicacion.jpeg" alt="Medicación">
                </div>
            </div>
        </div>

        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Historial del Medicacion</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Contenido del modal aquí -->
                <div class="card-flex">
                    <div class="card-content-flex">
                        <div class="info">
                            <h2>Última Medicación</h2>
                            <p><i class="fas fa-clock"></i> 08:00:00 <p>
                            <p>Responsable: Maria Gutierrez</p>
                        </div>
                        <div class="image">
                            <img id="img" src="medicacion.jpeg" alt="Medicación">
                        </div>
                    </div>
                </div>
                <br>
                <div class="card-flex">
                    <div class="card-content-flex">
                        <div class="info">
                            <h2>Última Medicación</h2>
                            <p><i class="fas fa-clock"></i> 08:00:00 <p>
                            <p>Responsable: Maria Gutierrez</p>
                        </div>
                        <div class="image">
                            <img id="img" src="medicacion.jpeg" alt="Medicación">
                        </div>
                    </div>
                </div>
                <br>
                <div class="card-flex">
                    <div class="card-content-flex">
                        <div class="info">
                            <h2>Última Medicación</h2>
                            <p><i class="fas fa-clock"></i> 08:00:00 <p>
                            <p>Responsable: <br> Maria Gutierrez</p>
                        </div>
                        <div class="image">
                            <img id="img" src="medicacion.jpeg" alt="Medicación">
                        </div>
                    </div>
                </div>
                <!-- Agrega más contenido de tarjetas dentro del modal según tus necesidades -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
        <div class="card">
            <div class="card-content">
                <h2>Horarios de Medicación</h2>
                <table class="table">
    <thead>
        <tr>
            <th>Turno</th>
            <th>Horario</th>
            <th>Estado</th>

        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Mañana</td>
            <td>08:00</td>
            <td>(Tomada) <i class="bi bi-check-circle-fill text-primary"></i></td>
        </tr>
        <tr>
            <td>Mediodía</td>
            <td>12:00</td>
            <td>(Próxima) <i class="bi bi-clock text-warning"></i></td>
        </tr>
        <tr>
            <td>Tarde</td>
            <td>15:00</td>
            <td>(Próxima) <i class="bi bi-clock text-warning"></i></td>
        </tr>
        <tr>
            <td>Noche</td>
            <td>20:00</td>
            <td>(Próxima) <i class="bi bi-clock text-warning"></i></td>
        </tr>
    </tbody>
</table>
   
            </div>
        </div>
    </div>
</div>


<style>
.card-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
}

 .card, .card-flex {
    border: 1px solid #ccc;
    border-radius: 25px; /* Todos los bordes con radio de 25px */
    padding: 10px;
    background-color: #478F8F;
    color: aliceblue;
    fill: var(--e-global-color-primary);
    color: var(--e-global-color-primary);
    border-style: solid;
    border-width: 2px;
    border-color: var(--e-global-color-primary);
    box-shadow: 6px 6px 0px -1px #000000;
}
.btn {
    display: block;
    width: 90%;
    padding: 10px;
    font-size: 20px;
    font-family: initial;
    text-align: center;
    background-color: #478F8F;
    color: #fff;
    border: 1px solid #000;
    text-decoration: none;
    border-radius: 25px;
    border-style: solid;
    border-width: 2px;
    border-color: var(--e-global-color-primary);
    box-shadow: 6px 6px 0px -1px #000000;
    transition: transform 0.3s; /* Agrega una transición a la transformación */
}
#card-opciones {
    display: none; /* Oculta el elemento por defecto */
}

#card-operador:hover {
    display: none; /* Oculta #card-operador al hacer hover */
}

#card-operador:hover ~ #card-opciones {
    display: block; /* Muestra #card-opciones al hacer hover en #card-operador */
}
#card-operador {
    background-color: #478F8F;
    transition: all 0.3s; /* Agrega una transición suave para todas las propiedades */
}
table tr, th, td {
    color: aliceblue;
}
#card-operador:hover {
    background-color: black; /* Cambia el fondo a negro al hacer hover */
    transform: translateX(100%) rotate(-45deg); /* Gira y desplaza hacia la derecha */
    opacity: 0; /* Establece la opacidad a 0 para que desaparezca */
}



#tarjetaMedicacion {
    display: none; /* Oculta el elemento por defecto */
}

#proximaMedicacion:hover #tarjetaMedicacion {
    display: block; /* Muestra #tarjetaMedicacion al hacer hover en #proximaMedicacion */
}

#proximaMedicacion {
    background-color: #478F8F;
    transition: all 0.3s; /* Agrega una transición suave para todas las propiedades */
}

#proximaMedicacion:hover {
    background-color: black; /* Cambia el fondo a negro al hacer hover */
    transform: translateX(100%) rotate(-45deg); /* Gira y desplaza hacia la derecha */
    opacity: 0; /* Establece la opacidad a 0 para que desaparezca */
}




.btn:hover {
 
}
    .card-content h2, .card-content-flex h2 {
        font-family: Arial;
        font-size: 16px;
        font-weight: bold;
        color: aliceblue;
        align-items: center;

        margin-left: 10px;
    }

    .card-content-flex {
        display: flex;
        align-items: center;
    }

    .info {
        flex: 1;
        padding: 10px;
    }

    .image {
        width: 35%;
    }

    .card-content p {
        font-family: Arial;
        font-size: 14px;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgb(51,167,181);
        color: aliceblue;
        margin-left: 15px;
    }
    .card-content-flex p {
        font-family: Arial;
        font-size: 14px;
        font-weight: bold;
        text-shadow: 1px 1px 2px rgb(51,167,181);
        color: aliceblue;
        margin-left: 15px;
    }

    #img {
        width: 100%;
        border-radius: 15px;
    }
    @media (max-width: 768px) {
        .card-container {
            grid-template-columns: 1fr;
        }
    }
</style>
<script>
    // Obtiene las referencias a los elementos
    const cardOperador = document.getElementById("card-operador");
    const cardOpciones = document.getElementById("card-opciones");

    // Agrega un controlador de eventos al hacer hover en card-operador
    cardOperador.addEventListener("mouseover", () => {
        cardOperador.style.display = "none"; // Oculta card-operador
        cardOpciones.style.display = "block"; // Muestra card-opciones
    });

    // Agrega un controlador de eventos al hacer hover en card-opciones
    cardOpciones.addEventListener("mouseout", () => {
        cardOperador.style.display = "block"; // Muestra card-operador
        cardOpciones.style.display = "none"; // Oculta card-opciones
    });

    const tarjetaMedicacion = document.getElementById("tarjetaMedicacion");
    const proximaMedicacion = document.getElementById("proximaMedicacion");

    // Agrega un controlador de eventos al hacer hover en proximaMedicacion
    proximaMedicacion.addEventListener("mouseover", () => {
        proximaMedicacion.style.display = "none"; // Oculta proximaMedicacion
        tarjetaMedicacion.style.display = "block"; // Muestra tarjetaMedicacion
    });

    // Agrega un controlador de eventos al hacer hover en tarjetaMedicacion
    tarjetaMedicacion.addEventListener("mouseout", () => {
        proximaMedicacion.style.display = "block"; // Muestra proximaMedicacion
        tarjetaMedicacion.style.display = "none"; // Oculta tarjetaMedicacion
    });




</script>
<?php require_once "vistas/parte_inferior.php"; ?>