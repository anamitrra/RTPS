<html>

<head></head>

<body>
    <div style="border: 2px solid #808080;padding: 10px;">
        <div style="width: 100%;">
            <table width="100%">
                <tr>
                    <td>Certificate Number:<br> <b><?= $certificate_no ?></b></td>
                    <td><img src="<?= FCPATH . "assets/frontend/images/assam_govt.png" ?>" style="width:60px;"></td>
                </tr>
            </table>
            <p style="text-align:center">GOVERNMENT OF ASSAM</p>
            <table  width="100%">
                <tr>
                    <td style="width:10%"></td>
                    <td style="vertical-align: top;text-align: center;">
                        <p style="text-align:center">OFFICE OF THE DEPUTY COMMISSIONER, <?= $data[0]->form_data->pa_district_name ?> DISTRICT</p>

                        <p style="font-weight:bold;font-size:16px">{Sec.2(c) of the National Commission for Minorities Act, 1992 (19 of 1992)}</p>
                        <br>
                        <p style="font-weight:bold;font-size:20px">Minority Community Certificate</p>
                    </td>
                    <td style="width:12%;vertical-align: top;"><img src="<?= FCPATH . $data[0]->form_data->passport_photo ?>" style="width:82px;height:98px"></td>
                </tr>
            </table>
        </div>
        <img src="<?php echo FCPATH . $qr ?>" alt="">
    </div>
</body>

</html>