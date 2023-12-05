<?php require_once "vistas/parte_superior.php"; ?>
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
        background-color: aliceblue;
        color: #000;
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
        background-color: aliceblue;
        color: #000;
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
    #card-operador {
        background-color: aliceblue;
        color: #000;
    }
    table tr, th, td {
        color: #000;
    }
    #proximaMedicacion {
        background-color: aliceblue;
       /* Agrega una transición suave para todas las propiedades */
        color: #000;
    }
        .card-content h2, .card-content-flex h2 {
            font-family: Arial;
            font-size: 16px;
            font-weight: bold;
            color: #000;
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
            color: #000;
            margin-left: 15px;
        }
        .card-content-flex p {
            font-family: Arial;
            font-size: 14px;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgb(51,167,181);
            color: #000;
            margin-left: 15px;
        }
        
        /* Estilo para centrar la imagen en el modal */
        #imagenMostrada {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            max-width: 100%;
            max-height: auto; /* Ajusta el valor según tu preferencia */
            overflow: hidden;
        }


        /* Estilo para aplicar sombreado a la imagen */
        #imagenSubida {
        max-width: 100%;
        height: auto;
        box-shadow: 3px 3px 5px 0px rgba(0, 0, 0, 0.5);
        border-radius: 15px 15px 15px 15px;
       
        }
        #imagenSubidaContainer{
            max-width: 100%;
            height: auto;
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
<div class="container">
    <!-- Page Heading -->
    <?php if (!empty($mensaje)) : ?>
            <div class="alert alert-success"><?php echo $mensaje; ?></div>
        <?php endif; ?>
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Bienvenido </h1>
        </div>

    <div class="card-container">
        <?php include_once "card_operador.php"; ?>
        <?php include_once "card_usuario.php"; ?>
        <?php include_once "card_proximaMedicacion.php" ?>
        <?php include_once "card_medicacion.php" ?>
        <?php include_once "card_opciones.php" ?>
        <?php include_once "card_horarios_medicacion.php" ?>
    </div>
</div>
<?php require_once "vistas/parte_inferior.php"; ?>