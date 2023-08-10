<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">

            <!-- Page Heading & Nav breadcrumb  -->
            <div class="row mb-4">
                <div class="col-sm-8">
                    <h1 class="m-0 text-dark">User Logs</h1>
                </div>
                <div class="col-sm-4">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="<?= base_url("site/admin/dashboard"); ?>">Home</a></li>
                        <li class="breadcrumb-item active">User Logs</li>
                    </ol>
                </div>
            </div>

            <div class="row border mb-4 py-3">
                <div class="col-12 col-md-8 table-responsive">
                    <h5 class="text-dark text-capitalize mb-4">Last 7 Days Hit Count -</h5>

                    <table class="table table-striped table-hover shadow-sm">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Hits</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($final as  $finals) : ?>

                                <tr>
                                    <td> <?= $finals->date ?></td>
                                    <td> <?= $finals->count ?></td>

                                </tr>

                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </div>


        </div><!-- /.container-fluid -->
    </div><!-- Main content -->

    <!-- /.content -->
</div>