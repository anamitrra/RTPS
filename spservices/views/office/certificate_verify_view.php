<!DOCTYPE html>
<html>
<head>
    <title>Certificate Verification</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <style>
        * {
            /* font-weight: bold; */
        }
    </style>
</head>
<body >
    <div class="container-fluid">
        <p class="text-center bg-dark p-2 mt-2 text-white">Minority Certificate Data</p>
        <table class="table table-striped table-bordered table-sm">
            <tr>
                <td>RTPS Ref. No.</td>
                <td><?= $data[0]->service_data->appl_ref_no; ?></td>
            </tr>
            <tr>
                <td>Certificate No.</td>
                <td><?= $data[0]->certificate_no; ?></td>
            </tr>
            <tr>
                <td>Issue Date</td>
                <td><?= date('d/m/Y', strtotime($this->mongo_db->getDateTime($data[0]->execution_data[0]->task_details->executed_time??''))); ?></td>
            </tr>
            <tr>
                <td>Name</td>
                <td><?= $data[0]->form_data->applicant_name; ?></td>
            </tr>
            <tr>
                <td>Community</td>
                <td><?= $data[0]->form_data->community; ?></td>
            </tr>
            <tr>
                <td>Address</td>
                <td><?= $data[0]->form_data->pa_village; ?></td>
            </tr>
            <tr>
                <td>District</td>
                <td><?= $data[0]->form_data->pa_district_name; ?></td>
            </tr>
        </table>
    </div>
</body>
</html>