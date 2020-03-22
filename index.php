<?php

require_once './backend/settings.php';
require_once './backend/utilities/db.php';

$db_o = new DB($settings_aa);
$categories_a = $db_o->fetchCategories();
$counties_a = $db_o->fetchCounties();
$tempCounties_a = [];
foreach($counties_a as $county_aa) {
    $tempCounties_a[$county_aa['id']] = $county_aa['name'];
}
//print_r($tempCounties_a);
//print_r($counties_a);
//print_r($counties_a);
// If query parameter "id" is present in the URL together with a value,
// fetch data about this ad from the database.
$ad_aa;
$header_s;
if(!empty($_GET['id'])) {
    if(is_numeric($_GET['id'])) {
        $ad_aa = $db_o->fetchAd($_GET['id']);
        //print_r($ad_aa);
    }
} elseif(!empty($_GET['delete'])) {
    $header_s = $db_o->deleteAd($_GET['delete']);
}

?>
<!DOCTYPE html>
<html lang="sv">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Buy and Sell</title>
    <link rel="stylesheet" href="./frontend/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./frontend/css/index.css" />
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

        .custom-file-upload {
            border: 1px solid #ccc;
            display: inline-block;
            padding: 6px 12px;
            cursor: pointer;
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
        
        .list-group-item:hover {
            background-color: #eee;
        }

        .type-input-group {
            border: 1px solid white;
            border-radius: 4px;
            padding: 5px
        }
        #terms {
            font-weight: bold;
            color: cornflowerblue;
            cursor: pointer;
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
                <div id="search-result" class="list-group"></div>
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
                            <div class="input-group">
                                <input type="text" class="form-control" id="price" placeholder="Pris" />
                                <div class="input-group-append"><span class="input-group-text">kr</span></div>
                            </div>
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
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="terms-checkbox">
                            <!-- <label class="form-check-label" for="">Jag har läst och godkänner <span id="terms">villkoren</span>.</label> -->
                            <label class="form-check-label" for="terms-checkbox">Jag har läst och godkänner
                            <a href="#"
                               data-animation="true"
                               data-html="true"
                               data-placement="top"
                               data-trigger="click hover"
                               data-content="<p>buyandsell.se bär inte ansvaret för annonsens innehåll.</p><p>buyandsell.se frånsäger sig ångerrätt\n\n (som är normalt sätt 14 dagar vid köp av varor eller tjänst via internet).</p><p>Olagliga varor eller tjänster som (vapen, alkohol, tobak, narkotika, pornografi, läkemedel) kommer raderas och polisanmälas.</p><p>Det är förbjudet att lägga upp i annonsen stötande eller kränkande för folkgrupper och/eller enskilda individer bilder eller text.</p>"
                               data-toggle="popover" >villkoren</a>.</label>
                        </div>
                        <div class="modal-footer"> 
                            <input id="close-new-ad-button" type="button" class="btn btn-secondary" data-dismiss="modal" value="Stäng" />
                            <input id="post-new-ad-button" type="submit" class="btn btn-success" value="Skicka" />
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
                    <h5 class="modal-title" id="item-modal-header"><?= empty($ad_aa) ? '' : $ad_aa['header'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card mb-3">
                        <img id="item-modal-image" <?= (empty($ad_aa) || $ad_aa['image'] === 'no image') ? 'style="display: none"' : '' ?>  src="<?= (!empty($ad_aa) && $ad_aa['image'] !== 'no image') ? './uploads/' . $ad_aa['id'] . '.' . $ad_aa['image'] : '' ?>" class="card-img-top" alt="..." />
                        <div class="card-body">
                            <div id="item-modal-body" class="card-text"><?= empty($ad_aa) ? '' : $ad_aa['body'] ?></div>
                            <div <?= !empty($ad_aa) ? $ad_aa['price'] === -1 ? 'style="display:none"' : '' : '' ?>>Pris: <span id="item-modal-price"><?= empty($ad_aa) ? '' : $ad_aa['price'] ?></span> kr</div>
                            <div class="card-text">
                                <small class="text-muted">Publicerades <span id="item-modal-published"><?= empty($ad_aa) ? '' : $ad_aa['published'] ?></span></small>
                                <br />
                                <small class="text-muted"><span id="item-modal-county"><?= empty($ad_aa) ? '' : $tempCounties_a[$ad_aa['county']] ?></span></small>
                                <?php
                                    $type = '';
                                    if(!empty($ad_aa)) {
                                        switch($ad_aa['type']) {
                                            case 1: $type_s = 'Säljes'; break;
                                            case 2: $type_s = 'Köpes'; break;
                                            case 3: $type_s = 'Uthyres'; break;
                                        }
                                    }
                                ?>
                                <small id="item-modal-type" style="float:right" class="text-muted"><?= $type_s ?></small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Stäng
                    </button>
                    <button id="show-send-message-modal-button" type="button" class="btn btn-success"
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
                        <label for="send-message-text">Meddelande till annonsören</label>
                        <textarea class="form-control" id="send-message-text" rows="3"></textarea>
                    </div>
                    <div id="send-message-response"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Stäng
                    </button>
                    <button id="send-message-button" type="button" class="btn btn-success">Skicka</button>
                </div>
            </div>
        </div>
    </div>

    <div id="delete-ad-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Annons borttagen</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Din annons med rubriken "<?= $header_s ?>" är nu borttagen.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Stäng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="block"></div>
    <script src="./frontend/js/jquery.min.js"></script>
    <script src="./frontend/js/bootstrap.bundle.min.js"></script>
    <script src="./frontend/js/index.js?<?= rand() ?>"></script>
    <?php if(!empty($ad_aa)) { ?>
        <script>
            $('#item-modal').modal('show');
        </script>
    <?php } elseif(!empty($header_s)) { ?>
        <script>
            $('#delete-ad-modal').modal('show');
        </script>
    <?php } ?>
</body>

</html>