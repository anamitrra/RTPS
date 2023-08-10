<?php
$lang = $this->lang;
// pre($settings);
// pre($search);
// print_r($pagination);
?>

<main id="main-contenet">

    <div class="container">

        <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline">
            <ol class="breadcrumb m-0">

                <?php foreach ($settings->nav as $key => $link) : ?>

                    <li class="breadcrumb-item <?= empty($link->url) ? 'active' : '' ?>" <?= empty($link->url) ? 'aria-current="page"' : '' ?>>

                        <?php if (isset($link->url)) : ?>
                            <a href="<?= base_url($link->url) ?>"><?= $link->$lang ?></a>

                        <?php else : ?>
                            <?= $link->$lang ?>


                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>

            </ol>
        </nav>

    <section class="text-center py-2">
        <p class="mb-1">
            <span class="text-capitalize text-muted"><?= $settings->heading->$lang  ?></span>
            <span class="serach-elk fs-4 mark">"<?= $this->session->userdata("elk_query") ?? ""  ?>"</span>
        </p>

        <?php if ($pagination['display_info']): ?>

            <span class="text-muted">
            <?= str_ireplace('[n]', strval( $search['hits']['total']['value'] ), $settings->result_count_msg->$lang); ?>
            </span>

            <span class="text-muted"><?= $settings->time_taken->$lang ?>  <?= $search['took'] ?> ms. </span>
        <?php endif; ?>
        
    </section>

    <section class="mt-3">

        <?php if ($search['hits']['total']['value'] > 0): ?>

            <ul class="list-group list-group-flush my-4">

                <?php foreach ($search['hits']['hits'] as $result) : ?>

                    <li class="elk-result list-group-item py-3 px-0">

                        <h5 class="text-primary"><?= $result['fields']["$lang.title"][0] ?></h5>
                            
                        <p class="small">

                            <?php foreach ($result['highlight'] as $item) : ?>

                                <?php foreach ($item as $value) : ?>
                                
                                    <?= $value . '... ' ?>

                                <?php endforeach; ?>

                            <?php endforeach; ?>

                        </p>

                        <a 
                        href="<?= ($result['fields']['url.target'][0] == '_self') ? base_url($result['fields']['url.link'][0]) : $result['fields']['url.link'][0] ?>" 

                        class="stretched-link" target="<?= $result['fields']['url.target'][0] ?>"  ></a>

                    </li>

                <?php endforeach; ?>

            </ul>

            
            <nav class="elk-pages"  aria-label="Page navigation example">
                <ul class="pagination justify-content-end">

                    <li class="page-item <?= ($pagination['from'] <= 0) ? 'disabled' : ''  ?>">

                        <a class="elk-page page-link text-capitalize" href="<?= base_url('site/elk_search/' . strval($pagination['from'] - $pagination['size']) ) ?>"><?= $settings->prev->$lang  ?></a>

                    </li>
                    
                    <li class="page-item <?= ($pagination['from'] + $pagination['size'] >= $search['hits']['total']['value']) ? 'disabled' : ''  ?>">

                        <a class="elk-page page-link text-capitalize" href="<?= base_url('site/elk_search/' . strval($pagination['from'] + $pagination['size']) ) ?>"><?= $settings->next->$lang  ?></a>

                    </li>
                </ul>
            </nav>
    

        
        <?php else: ?>

            <img class="img-fluid rounded mx-auto d-block"  src="<?= base_url('assets/site/'.$theme.'/images/nf.png') ?>" alt="result not found">

        <?php endif; ?>
    
    </section>

    </div>

</main>