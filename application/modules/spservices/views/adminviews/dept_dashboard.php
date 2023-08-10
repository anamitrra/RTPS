<style>
    body {
        overflow-x: hidden;
    }

    .card-body {
        padding: 30px 0;
        font-weight: bold
    }

    .card-body .count_data {
        font-size: 30px;
    }
</style>
<div class="content-wrapper">
    <div class="container-fluid pt-4">
        <div class="row ">
            <div class="col-md-4">
                <div class="card text-center bg-info">
                    <div class="card-body">
                        <h5>Total Users</h5>
                        <span class="count_data"><?= ($counts) ? $counts[0]->total : 0 ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-primary">
                    <div class="card-body">
                        <h5>Active Users</h5>
                        <span class="count_data"><?= ($counts) ? $counts[0]->active : 0 ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center bg-danger">
                    <div class="card-body">
                        <h5>Inactive Users</h5>
                        <span class="count_data"><?= ($counts) ? $counts[0]->inactive : 0 ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>