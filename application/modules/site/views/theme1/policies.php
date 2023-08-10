<?php
$lang = $this->lang;
// $data = $faq;
// pre($data);
?>
<style>
    /* Style the tab */
    .tab {
        float: left;
        border: 1px solid #ccc;
        background-color: #f1f1f1;
        width: 30%;
        height: fit-content;
        border-right-color: darkorange;
        border-right-width: 5px;

    }

    /* Style the buttons inside the tab */
    .tab button {
        display: block;
        color: black;
        padding: 2em;
        width: 100%;
        border: none;
        outline: none;
        text-align: left;
        cursor: pointer;
        transition: 0.3s;
        font-size: 1rem;

    }

    /* Change background color of buttons on hover */
    .tab button:hover {
        background-color: #e3e1fc;
    }

    /* Create an active/current "tab button" class */
    .tab button.active {
        background-color: rgb(130, 86, 226);
        color: whitesmoke;
        font-weight: bold;
    }

    /* Style the tab content */
    .tabcontent {
        float: left;
        padding: 1em;
        border: 1px solid #ccc;
        width: 70%;
        /* border-left-color: darkorange; */
        border-left: none;
        border-right: none;
        border-bottom: none;
        height: fit-content;
        /* border-bottom: none; */
    }

    @media (max-width: 450px) {
        .tab button {
            padding: 0.5em;
        }
    }
</style>

<!-- Breadcrumb Start -->
<div class="container-fluid bg-primary mb-5 page-header">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-10 text-start">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">

                        <?php foreach ($data->nav as $key => $link) : ?>

                            <li class="breadcrumb-item text-white <?= empty($link->url) ? 'active' : '' ?>" <?= empty($link->url) ? 'aria-current="page"' : '' ?>>

                                <?php if (isset($link->url)) : ?>
                                    <a class="text-white" href="<?= base_url($link->url) ?>"><?= $link->$lang ?></a>

                                <?php else : ?>
                                    <?= $link->$lang ?>


                                <?php endif; ?>

                            </li>
                        <?php endforeach; ?>

                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Ends here -->

<main id="main-contenet">

    <div class="container-xxl py-3">
        <div class="container extra-margin-bottom">
            <div class="row g-5">
                <div class="col-lg-12 wow fadeInUp" data-wow-delay="0.3s">

                    <!-- BODY content  -->

                    <h1 class="pb-2 border-4 border-bottom mb-3" style="letter-spacing: .1em;"><?= $data->heading->$lang ?></h1>

                    <!-- Vertical Tabs -->
                    <section class="tab">
                        <?php foreach ($data->policies as $key => $val) : ?>

                            <button class="tablinks" onclick="openTabContent(event, 'tab-<?= $key ?>')" <?= ($key == 0) ? 'id="defaultOpen"' : '' ?>>
                                <?= $val->$lang ?>
                            </button>

                        <?php endforeach; ?>
                    </section>


                    <!-- Tab conents -->
                    <?php foreach ($data->policies as $key => $val) : ?>
                        <article id="tab-<?= $key ?>" class="tabcontent">
                            <!-- <h3><?= $val->$lang ?></h3> -->
                            <?= $val->content->$lang ?>
                        </article>
                    <?php endforeach; ?>




                </div>
            </div>
        </div>
    </div>

</main>

<script>
    function openTabContent(evt, cityName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active";
    }

    // Get the element with id="defaultOpen" and click on it
    document.getElementById("defaultOpen").click();
</script>