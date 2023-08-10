<style>
body {
  overflow-x: hidden; 
}
</style>
<div class="content-wrapper">
    <main class="rtps-container">
        <div class="row">
            <div class="col">
                <div class="container my-2">
                    <div class="card" style="">
                        <h3 class="card-header text-center fw-semibold">
                            Admin Information
                        </h3>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><span style="font-weight: bold;">Name : </span> <?php echo ($admin_details[0]->name) ?></li>
                            <li class="list-group-item"><span style="font-weight: bold;">Mobile : </span> <?php echo ($admin_details[0]->mobile) ?></li>
                            <li class="list-group-item"><span style="font-weight: bold;">Email : </span> <?php echo ($admin_details[0]->email) ?></li>
                            <li class="list-group-item"><span style="font-weight: bold;">Password : </span> &#9734; &#9734; &#9734; &#9734; &#9734; </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>