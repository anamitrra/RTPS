<div class="row">
    <div class="col-md-5"><label>Application Ref. Number</label></div>
    <div class="col-md-7"><?= $ref_no ?></div>
</div>



<ol class="breadcrumb mb-4 col-md-12">
    <li class="breadcrumb-item active">Account Details</li>
</ol>
<table class="table text-font10">
    <thead>
        <tr>
            <th scope="col">Account 1</th>
            <th scope="col">Major Head</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td scope="col"><?= !empty($data['ACCOUNT1']) ?? $data['ACCOUNT1'] ?></td>
            <td scope="col"><?= $data['MAJOR_HEAD'] ?></td>
        </tr>
    </tbody>
</table>