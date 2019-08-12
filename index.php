<?php

require_once './backend/settings.php';
require_once './backend/classes/db.php';

$db_o = new DB($settings_a);
$categories_a = $db_o->fetchCategories();
$counties_a = $db_o->fetchCounties();

?>
<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Buy and Sell</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css" />
    <link rel="stylesheet" href="./css/index.css" />
    <style>
        button,
        button:active,
        button:focus {
            box-shadow: none;
        }

        .dropdown-item:hover {
            background-color: rgba(128, 128, 128, 0.198);
        }

        .btn-outline-secondary {
            border-color: #ccc;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="#">Buy &amp; Sell</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul id="navbar-search-section" class="navbar-nav mr-auto">
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="#">Home
                        <span class="sr-only">(current)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Link</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link disabled" href="#">Disabled</a>
                </li> -->
                <li class="nav-item" style="background: none;">
                    <form class="form-inline mt-2 mt-md-0"><input id="search-text" class="form-control mr-sm-2" type="text"
                            placeholder="Sök" aria-label="Search" /></form>

                </li>

                <li class="nav-item" style="background: none;">
                    <div id="search-category" class="dropdown">
                        <button style="box-shadow: none;background: none;border: none;padding-left:0;"
                            class="btn btn-secondary dropdown-toggle" type="button" id="search-category-button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-category="0">
                            Alla kategorier
                        </button>
                        <div class="dropdown-menu scrollable-menu" aria-labelledby="search-category-button">
                            <a class="dropdown-item" href="#" data-category="0">Alla kategorier</a>
                            <?php
                                foreach($categories_a as $category_a) {
                                    echo '<a class="dropdown-item" href="#" data-category="' . $category_a['id'] . '">' . $category_a['name'] . '</a>';
                                }
                            ?>
                        </div>
                    </div>
                </li>
                <li class="nav-item" style="background: none;">
                    <div id="search-county" class="dropdown">
                        <button style="box-shadow: none;background: none;border: none;padding-left:0;"
                            class="btn btn-secondary dropdown-toggle" type="button" id="search-county-button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-county="0">
                            Hela Sverige
                        </button>
                        <div class="dropdown-menu scrollable-menu" aria-labelledby="search-county-button">
                            <a class="dropdown-item" href="#" data-county="0">Hela Sverige</a>
                            <?php
                                foreach($counties_a as $county_a) {
                                    echo '<a class="dropdown-item" href="#" data-county="' . $county_a['id'] . '">' . $county_a['name'] . '</a>';
                                }
                            ?>
                        </div>
                    </div>
                </li>
                <li class="nav-item" style="background: none;">
                    <div id="search-buy-or-sell" class="dropdown">
                        <button style="box-shadow: none;background: none;border: none;padding-left:0;"
                            class="btn btn-secondary dropdown-toggle" type="button" id="search-buy-or-sell-button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-buy-or-sell="0">
                            Säljes
                        </button>
                        <div class="dropdown-menu scrollable-menu" aria-labelledby="search-buy-or-sell-button">
                            <a class="dropdown-item" href="#" data-buy-or-sell="0">Säljes</a>
                            <a class="dropdown-item" href="#" data-buy-or-sell="1">Köpes</a>
                            <a class="dropdown-item" href="#" data-buy-or-sell="2">Uthyres</a>
                            <a class="dropdown-item" href="#" data-buy-or-sell="3">Köpes, säljes och uthyres</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item mr-3">
                    <!-- <form class="form-inline mt-2 mt-md-0">
                        <input class="form-control mr-sm-2" type="text" placeholder="Sök" aria-label="Search" />
                        
                    </form> -->
                    <button id="search-button" class="btn btn-success my-2 my-sm-0" type="button"></button>
                </li>
            </ul>

            <button id="place-add-button" class="btn btn-success my-2 my-sm-0" data-toggle="modal"
                data-target="#add-ad-modal">
                Sätt in annons
            </button>
        </div>
    </nav>

    <main role="main" class="container">
        <!-- <div class="jumbotron">
            <h1>Navbar example</h1>
            <p class="lead">This example is a quick exercise to illustrate how fixed to top navbar works. As you
                scroll, it will remain fixed
                to the top of your browser's viewport.</p>
            <a class="btn btn-lg btn-primary" href="../../components/navbar/" role="button">View navbar docs &raquo;</a>
        </div> -->

        <div class="row mb-3">
            <div class="col-sm">
                <div id="sort-dropdown" class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="sort-dropdown-button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Senaste
                    </button>
                    <div class="dropdown-menu" aria-labelledby="sort-dropdown-button">
                        <a class="dropdown-item" href="#">Bokstavsordning</a>
                        <a class="dropdown-item" href="#">Pris</a>
                        <a class="dropdown-item" href="#">Senaste</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div class="list-group">
                    <a data-toggle="modal" data-target="#item-modal" href="#"
                        class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Super Nintendo</h5>
                            <small>Idag 14.30</small>
                        </div>
                        <img style="max-height:100px;" src="./images/snes.jpg" alt="snes" />
                        <div>1000 kr</div>
                        <small>Östermalm, Stockholm</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Sega Megadrive</h5>
                            <small class="text-muted">Igår 15:40</small>
                        </div>
                        <img style="max-height:100px;" src="./images/megadrive.jpg" alt="megadrive" />
                        <div>1500 kr</div>
                        <small class="text-muted">Hägersten, Stockholm</small>
                    </a>
                    <a href="#" class="list-group-item list-group-item-action flex-column  align-items-start">
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">Sega Game Gear</h5>
                            <small class="text-muted">3 dagar sedan</small>
                        </div>
                        <img style="max-height:100px;" src="./images/gamegear.jpg" alt="game gear" />
                        <div>800 kr</div>
                        <small class="text-muted">Kallhäll, Stockholm</small>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <div id="loader"></div>

    <div class="modal fade" id="add-ad-modal" tabindex="-1" role="dialog" aria-labelledby="add-ad-modal-label"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="add-ad-modal-label">Sätt in annons</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="collapse" id="success-text">Din annons har nu blivit publicerad.</p>
                    <form id="add-ad-form">
                        <div class="form-group">
                            <!-- Säljes - Radioknapp -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="sell" value="0"
                                    checked />
                                <label class="form-check-label" for="sell-radio">Säljes</label>
                            </div>
                            <!-- Köpes - Radioknapp -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="buy" value="1" />
                                <label class="form-check-label" for="buy">Köpes</label>
                            </div>
                            <!-- Uthyres - Radioknapp -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="rent-out" value="2" />
                                <label class="form-check-label" for="rent-out">Uthyres</label>
                            </div>
                        </div>
                        <p>Obligatoriska fält = <span class="text-danger">*</span></p>
                        <!-- Välj kategori - Select -->
                        <div class="form-group">
                            <label for="category">Kategori <span class="text-danger">*</span></label>
                            <select class="form-control" id="category">
                                <option value="0">Välj kategori</option>
                                <?php
                                    foreach($categories_a as $category_a) {
                                        echo '<option value="' . $category_a['id'] . '">' . $category_a['name'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <!-- Välj län - Select -->
                        <div class="form-group">
                            <label for="county">Välj <span class="text-danger">*</span> </label>
                            <select class="form-control" id="county">
                                <option value="0">Välj län</option>
                                <?php
                                    foreach($counties_a as $county_a) {
                                        echo '<option value="' . $county_a['id'] . '">' . $county_a['name'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="header">Rubrik <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="header" placeholder="Rubrik" />
                        </div>
                        <div class="form-group">
                            <label for="body">Annonstext <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="body" rows="3"></textarea>
                        </div>
                        <div class="form-group">
                            <label for="price">Pris <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="price" placeholder="Pris" />
                        </div>
                        <div class="form-group">
                            <label for="email">E-post <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" placeholder="E-post" />
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefon</label>
                            <input type="tel" class="form-control" id="phone" placeholder="Telefon" />
                        </div>
                        <div class="form-group">
                            <label for="image">Bild</label>
                            <input type="file" class="form-control-file" id="image" />
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <input type="button" class="btn btn-secondary" data-dismiss="modal" value="Stäng" />
                    <input id="submit-ad" type="button" class="btn btn-primary" value="Skicka" />
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="item-modal" tabindex="-1" role="dialog" aria-labelledby="item-modal-label"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="item-modal-label">Super Nintendo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-3">
                        <img src="./images/snes.jpg" class="card-img-top" alt="..." />
                        <div class="card-body">
                            <h5 class="card-title">Super Nintendo</h5>
                            <p class="card-text">
                                Jag säljer ett Super Nintendo i nyskick. Har bara spelat Super
                                Mario World på den.
                            </p>
                            <p>
                                Pris: 1000 kr
                            </p>
                            <p class="card-text">
                                <small class="text-muted">Publicerades 2019-01-12</small>
                                <small class="text-muted">Östermalm, Stockholm</small>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Stäng
                    </button>
                    <button id="show-send-message-modal-button" type="button" class="btn btn-primary"
                        data-dismiss="modal">
                        Svara annonsören
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="send-message-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Svara annonsören</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="send-message-textarea">Meddelande till annonsören</label>
                        <textarea class="form-control" id="send-message-textarea" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Stäng
                    </button>
                    <button type="button" class="btn btn-primary">Skicka</button>
                </div>
            </div>
        </div>
    </div>
    <div id="block"></div>
    <script src="./js/jquery.min.js"></script>
    <script src="./js/bootstrap.bundle.min.js"></script>
    <script src="./js/index.js"></script>
</body>

</html>