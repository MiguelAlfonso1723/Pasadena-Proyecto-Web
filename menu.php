<header class="py-3 mb-4 border-bottom">
    <div class="navbar navbar-expand-lg container d-flex flex-wrap justify-content-center">
        <a href="index.php" class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto text-dark text-decoration-none">
            <span class="fs-4"><img src="./images/logoPasadena.png" width="180"></span>

        </a>
        &emsp;
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader"
                aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarHeader" style="text-align:center">
            <ul class="navbar-nav me-auto mb-2 mg-lg-0">
                <li class="nav-item">
                    <a href="index.php" class="nav-link active">Catalogo</a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link ">Contacto</a>
                </li>
            </ul>

            <?php if (isset($_SESSION['user_id'])) { ?>

                <div class="dropdown me-5">
                    <a href="#" style="color: #d92429; background-color: rgba(255,213,0,0.71);" class="btn btn-success btn-sm dropdown-toggle" role="button" id="btn_session"
                       data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="./images/user-nav.png" width="40"><br><?php echo $_SESSION['user_name']; ?>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="btn_session">
                        <li><a class="dropdown-item" href="compras.php">Mis compras</a></li>
                        <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </div>

                <!--<a href="#" style="color:#d92429;" class="me-5"><img src="./images/user-nav.png"
                                                                     width="40"><br><?php /*echo $_SESSION['user_name']; */?>
                </a>-->

            <?php } else { ?>
                <a href="login.php" style="color:#d92429;" class="me-5"><img src="./images/user-nav.png" width="40"><br>Ingresar</a>
            <?php } ?>

            <a href="checkout.php"><img src="./images/3144456.png" width="40"><br><span id="num_cart"
                                                                                        class="badge rounded-pill bg-danger"
                                                                                        style="col">
            <?php echo $num_cart; ?>
          </span></a>


        </div>
    </div>


</header>