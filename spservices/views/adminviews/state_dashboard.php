<style>
    body {
        overflow-x: hidden;
    }

    .card-body {
        /* padding: 30px 0; */
        font-weight: bold
    }

    .card-body .count_data {
        font-size: 35px;
    }

    .card-icon {
        font-size: 40px;
    }

    .card-icon .fa {
        border: 2px solid #ddd;
        border-radius: 20%;
        padding: 5px;
    }

    .card-icon :hover {
        background: #ddd;
    }
</style>
<div class="content-wrapper mt-2 p-2">
    <div class="row ">
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5>All Users</h5>
                    <div class="card-icon">
                        <i class="fa fa-users text-info"></i>
                    </div>
                    <span class="count_data"><?= $counts[0]->total ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5>Active Users</h5>
                    <div class="card-icon">
                        <i class="fa fa-user-check text-success"></i>
                    </div>
                    <span class="count_data"><?= $counts[0]->active ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <h5>Inactive Users</h5>
                    <div class="card-icon">
                        <i class="fa fa-user-slash text-danger"></i>
                    </div>
                    <span class="count_data"><?= $counts[0]->inactive ?></span>
                </div>
            </div>
        </div>
    </div>
</div>