<?php

require_once './backend/settings.php';
require_once './api/classes/db.php';

$db_o = new DB($settings_a);
$categories_a = $db_o->fetchCategories();
$counties_a = $db_o->fetchCounties();
//print_r($counties_a);
// If query parameter "id" is present in the URL together with a value,
// fetch data about this ad from the database.
$ad_aa;
if(!empty($_GET['id'])) {
    if(is_numeric($_GET['id'])) {
        $ad_aa = $db_o->fetchAd($_GET['id']);
        //print_r($ad_aa);
    }
}

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

        .invalid {
            border: 1px solid red;
            border-radius: 4px;
            display: inline-block;
            padding: 4px;
        }
        input[type="file"] {
            display: none;
        }
        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
        }
        .type-input-group {
            border: 1px solid white;
            border-radius: 4px;
            padding: 5px
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
                            data-toggle="dropdown" value="0" aria-haspopup="true" aria-expanded="false" data-category="0">
                            Alla kategorier
                        </button>
                        <div class="dropdown-menu scrollable-menu" aria-labelledby="search-category-button">
                            <a class="dropdown-item" href="#" value="0">Alla kategorier</a>
                            <?php
                                foreach($categories_a as $category_a) {
                                    echo '<a class="dropdown-item" href="#" value="' . $category_a['id'] . '">' . $category_a['name'] . '</a>';
                                }
                            ?>
                        </div>
                    </div>
                </li>
                <li class="nav-item" style="background: none;">
                    <div id="search-county" class="dropdown">
                        <button style="box-shadow: none;background: none;border: none;padding-left:0;"
                            class="btn btn-secondary dropdown-toggle" type="button" id="search-county-button"
                            data-toggle="dropdown" value="0" aria-haspopup="true" aria-expanded="false" data-county="0">
                            Hela Sverige
                        </button>
                        <div class="dropdown-menu scrollable-menu" aria-labelledby="search-county-button">
                            <a class="dropdown-item" href="#" value="0">Hela Sverige</a>
                            <?php
                                foreach($counties_a as $county_a) {
                                    echo '<a class="dropdown-item" href="#" value="' . $county_a['id'] . '">' . $county_a['name'] . '</a>';
                                }
                            ?>
                        </div>
                    </div>
                </li>
                <li class="nav-item" style="background: none;">
                    <div id="search-type" class="dropdown">
                        <button style="box-shadow: none;background: none;border: none;padding-left:0;"
                            class="btn btn-secondary dropdown-toggle" type="button" id="search-type-button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" value="0">
                            Säljes, Köpes eller Uthyres
                        </button>
                        <div class="dropdown-menu scrollable-menu" aria-labelledby="search-type-button">
                            <a class="dropdown-item" href="#" value="1">Säljes</a>
                            <a class="dropdown-item" href="#" value="2">Köpes</a>
                            <a class="dropdown-item" href="#" value="3">Uthyres</a>
                            <a class="dropdown-item" href="#" value="0">Köpes, säljes eller uthyres</a>
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
                data-target="#post-new-ad-modal">
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
                <div id="search-result" class="list-group">
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

    <div class="modal fade" id="post-new-ad-modal" tabindex="-1" role="dialog" aria-labelledby="post-new-ad-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="post-new-ad-modal-label">Sätt in annons</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="collapse" id="success-text"></div>
                    <form id="post-new-ad-form">
                        <div id="type" class="form-group type-input-group">
                            <!-- Säljes - Radioknapp -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="sell" value="1" />
                                <label class="form-check-label" for="sell-radio">Säljes</label>
                            </div>
                            <!-- Köpes - Radioknapp -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="buy" value="2" />
                                <label class="form-check-label" for="buy">Köpes</label>
                            </div>
                            <!-- Uthyres - Radioknapp -->
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="type" id="rent-out" value="3" />
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
                            <textarea class="form-control" id="body" rows="3" placeholder="Annonstext"></textarea>
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
                            <label for="image-upload" class="btn btn-success">Ladda upp bild</label>
                            <input type="file" class="form-control-file" id="image-upload" />
                            <span id="filename"></span>
                        </div>
                        <div class="modal-footer">
                            <input id="close-new-ad-button" type="button" class="btn btn-secondare" data-dismiss="modal" value="Stäng" />
                            <input id="post-new-ad-button" type="submit" class="btn btn-primary" value="Skicka" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="item-modal" tabindex="-1" role="dialog" aria-labelledby="item-modal-label"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="item-modal-header">
                        <?= empty($ad_aa) ? '' : $ad_aa['header'] ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-3">
                        <div id="item-modal-image">
                            <?php if(!empty($ad_aa) && $ad_aa['image'] !== 'no image') { ?>
                                <img src="<?= './uploads/' . $ad_aa['id'] . '.' . $ad_aa['image'] ?>"
                                     class="card-img-top" 
                                     alt="..." />
                            <?php } ?>
                        </div>
                        <div class="card-body">
                            <div id="item-modal-body" class="card-text">
                                <?= empty($ad_aa) ? '' : $ad_aa['body'] ?>
                            </div>
                            <div>
                                Pris: <span id="item-modal-price"><?= empty($ad_aa) ? '' : $ad_aa['price'] ?></span> kr
                            </div>
                            <div class="card-text">
                                <small class="text-muted">Publicerades <?= empty($ad_aa) ? '' : $ad_aa['published'] ?></small>
                                <br />
                                <small class="text-muted">
                                    <?php 
                                        if(!empty($ad_aa)) {
                                            foreach($counties_a as $county_a) {
                                                if($county_a['id'] == $ad_aa['county']) {
                                                    echo $county_a['name'];
                                                    break;
                                                }
                                            }
                                        }
                                    ?>
                                </small>
                            </div>
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
    <?php if(!empty($ad_aa)) { ?>
        <script>
            $('#item-modal').modal('show');
        </script>
    <?php } ?>
</body>

</html>