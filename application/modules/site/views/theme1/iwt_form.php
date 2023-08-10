<?php
$lang = $this->lang;
?>

<style id="styleToPrint">
    #printable {
        border: 1px solid lightgray;
        border-radius: 0.25rem;
        padding: 0.5rem;
        margin: .2rem;
    }

    span {
        font-weight: bolder;
    }

    .printableTable {
        width: 100%;
    }

    tr td h3 {
        text-align: center;
    }

    p {
        text-align: justify;
    }

    .control-panel {
        top: 5px;
        z-index: 10;
    }

    p {
        line-height: 2.2;
    }

    input {
        font-size: 1rem;
        padding: 0.3rem;
        border: none;
        outline: none;
        border-bottom: 1px solid black;
    }

    input:focus {
        border: none;
        outline: none;
        border-bottom: 2px solid rgb(192, 108, 132);
    }

    .dark-mode {
        background-color: black;
        color: aliceblue;
    }

    @keyframes wobbly {
        0% {
            transform: rotateZ(-5deg);
        }

        25% {
            transform: rotateZ(0);
        }

        50% {
            transform: rotateZ(5deg);
        }

        100% {
            transform: rotateZ(0);
        }
    }

    .error {
        border: 2px solid red;
        animation: wobbly 1s linear 3;
    }
</style>

<div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModal" aria-hidden="true" data-bs-keyboard="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-rtps" data-bs-dismiss="modal">
                    <?= $header->login_model->c_btn->$lang ?>
                </button>
            </div>
        </div>
    </div>

</div>


<main id="main-contenet">
    <div class="container">

        <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline mb-3 mb-md-0">
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

        <form id="printableForm">
            <div class="control-panel position-sticky text-end mb-2">
                <button id="generatePDF" type="button" class="btn btn-rtps"><?= $settings->print_btn->$lang ?></button>
                <button id="resetFormDesign" type="reset" class="btn btn-rtps"><?= $settings->reset_btn->$lang ?></button>
            </div>
            <div id="printable">
                <h4 style="text-align: center;">DECLARATION OF OWNERSHIP</h4>
                <br>
                <br>
                <p>I <input type="text" placeholder="Name of Owner" id="applicantName" required> <span id="applicantNameSpan" style="display: none;"></span> son/daughter of <input type="text" placeholder="Father's name" id="fatherName" required> <span id="fatherNameSpan" style="display: none;"></span> of town/village
                    <input type="text" placeholder="town/village Name" id="townVillageName" required> <span id="townVillageNameSpan" style="display: none;"></span>district<input type="text" placeholder="district name" id="district" required> <span id="districtSpan" style="display: none;"></span>subject of the state of <input type="text" placeholder="Enter state " id="stateName" required> <span id="stateNameSpan" style="display: none;"></span> residing permanently at <input type="text" placeholder="Full address" id="fullAddressName" required> <span id="fullAddressNameSpan" style="display: none;"></span>do hereby declare that <input type="text" placeholder="Name of vessel" id="name" required> <span id="nameSpan" style="display: none;"></span> was built at <input type="text" placeholder="Place of built" id="at" required> <span id="atSpan" style="display: none;"></span> by<input type="text" placeholder="Name of builder" id="builderName" required> <span id="builderNameSpan" style="display: none;"></span> in the year
                    <input type="text" placeholder="Enter year" id="yearOfEstablishment" required> <span id="yearOfEstablishmentSpan" style="display: none;"></span> and was purchased by me for Rs <input type="text" placeholder="Enter amount" id="amount" required>
                    <span id="amountSpan" style="display: none;"></span> I wish to have the said vessel registered in my name at
                    the port/place <input type="text" placeholder="Enter the port address" id="portAddress" required> <span style="display: none;" id="portAddressSpan"></span>I declare that I am the sole owner of the said vessel. I
                    further declare that the vessel is intended to ply in the port of <input type="text" placeholder="Enter the port name" id="portName" required> <span style="display: none;" id="portNameSpan"></span>.
                </p>

                <p>Made and subscribed on <input type="text" placeholder="mm/dd/yyyy" id="subscribedDate" required pattern="^\d{2}\/\d{2}\/\d{4}$"> <span style="display: none;" id="subscribedDateSpan"></span> by <input type="text" placeholder="Owner's name" id="aboveNamed" required> <span style="display: none;" id="aboveNamedSpan"></span>
                    in the presence of <input type="text" placeholder="Name of witness" id="presenceOf" required> <span style="display: none;" id="presenceOfSpan"></span>
                    son/daughter of <input type="text" placeholder="Father's name" id="fatherName2" required> <span id="fatherName2Span" style="display: none;"></span> of village/town
                    <input type="text" placeholder="town/village Name" id="townVillageName2" required> <span id="townVillageNameSpan2" style="display: none;"></span> district <input type="text" placeholder="district name" id="district2" required> <span id="districtSpan2" style="display: none;"></span> state <input type="text" placeholder="state name" id="stateName2" required> <span id="stateNameSpan2" style="display: none;"></span>.
                </p>

                <table style="width:100%; margin-top: 3rem;">
                    <tr>
                        <td width="50%"></td>
                        <td style="text-align: center;">
                            Signature of owner
                            <br>
                            <input type="text" id="ownerSignature" placeholder="">
                            <span style="display: none;" id="ownerSignatureSpan"></span>
                            <br>
                        </td>
                    </tr>
                </table>

            </div>
            <div id="toPrint"></div>
        </form>

    </div>

</main>

<script>
    var printableFormRef = document.getElementById('printableForm');
    var applicantNameRef = document.getElementById('applicantName');
    var applicantNameSpanRef = document.getElementById('applicantNameSpan');
    var fatherNameRef = document.getElementById('fatherName');
    var fatherNameSpanRef = document.getElementById('fatherNameSpan');
    var townVillageNameRef = document.getElementById('townVillageName');
    var townVillageNameSpanRef = document.getElementById('townVillageNameSpan');
    var districtRef = document.getElementById('district');
    var districtSpanRef = document.getElementById('districtSpan');
    var stateNameRef = document.getElementById('stateName');
    var stateNameSpanRef = document.getElementById('stateNameSpan');
    var fullAddressNameRef = document.getElementById('fullAddressName');
    var fullAddressNameSpanRef = document.getElementById('fullAddressNameSpan');
    var nameRef = document.getElementById('name');
    var nameSpanRef = document.getElementById('nameSpan');
    var atRef = document.getElementById('at');
    var atSpanRef = document.getElementById('atSpan');
    var builderNameRef = document.getElementById('builderName');
    var builderNameSpanRef = document.getElementById('builderNameSpan');
    var amountRef = document.getElementById('amount');
    var amountSpanRef = document.getElementById('amountSpan');
    var yearOfEstablishmentRef = document.getElementById('yearOfEstablishment');
    var yearOfEstablishmentSpanRef = document.getElementById('yearOfEstablishmentSpan');
    var portAddressRef = document.getElementById('portAddress');
    var portAddressSpanRef = document.getElementById('portAddressSpan');
    var portNameRef = document.getElementById('portName');
    var portNameSpanRef = document.getElementById('portNameSpan');
    var subscribedDateRef = document.getElementById('subscribedDate');
    var subscribedDateSpanRef = document.getElementById('subscribedDateSpan');
    var aboveNamedRef = document.getElementById('aboveNamed');
    var aboveNamedSpanRef = document.getElementById('aboveNamedSpan');
    var presenceOfRef = document.getElementById('presenceOf');
    var presenceOfSpanRef = document.getElementById('presenceOfSpan');
    var fatherName2Ref = document.getElementById('fatherName2');
    var fatherName2SpanRef = document.getElementById('fatherName2Span');
    var townVillageName2Ref = document.getElementById('townVillageName2');
    var townVillageNameSpan2Ref = document.getElementById('townVillageNameSpan2');
    var district2Ref = document.getElementById('district2');
    var districtSpan2Ref = document.getElementById('districtSpan2');
    var stateName2Ref = document.getElementById('stateName2');
    var stateNameSpan2Ref = document.getElementById('stateNameSpan2');
    var ownerSignatureRef = document.getElementById('ownerSignature');
    var ownerSignatureSpanRef = document.getElementById('ownerSignatureSpan');


    document.getElementById('generatePDF').onclick = function() {
        let isAnyEmpty = false;

        for (let i = 0; i < printableFormRef.elements.length; i++) {
            if (printableFormRef.elements[i].tagName !== 'BUTTON' && printableFormRef.elements[i].id !== 'ownerSignature' && printableFormRef.elements[i].value == '') {
                isAnyEmpty = true;
                break;
            }
        }

        if (!isAnyEmpty) {

            let hasError = false;

            applicantNameRef.style.display = 'none';
            applicantNameSpanRef.innerText = applicantNameRef.value.trim();
            applicantNameSpanRef.style.display = 'inline';
            fatherNameRef.style.display = 'none';
            fatherNameSpanRef.innerText = fatherNameRef.value.trim();
            fatherNameSpanRef.style.display = 'inline';
            townVillageNameRef.style.display = 'none';
            townVillageNameSpanRef.innerText = townVillageNameRef.value.trim();
            townVillageNameSpanRef.style.display = 'inline';
            districtRef.style.display = 'none';
            districtSpanRef.innerText = districtRef.value.trim();
            districtSpanRef.style.display = 'inline';
            stateNameRef.style.display = 'none';
            stateNameSpanRef.innerText = stateNameRef.value.trim();
            stateNameSpanRef.style.display = 'inline';
            fullAddressNameRef.style.display = 'none';
            fullAddressNameSpanRef.innerText = fullAddressNameRef.value.trim();
            fullAddressNameSpanRef.style.display = 'inline';
            nameRef.style.display = 'none';
            nameSpanRef.innerText = nameRef.value.trim();
            nameSpanRef.style.display = 'inline';
            atRef.style.display = 'none';
            atSpanRef.innerText = atRef.value.trim();
            atSpanRef.style.display = 'inline';
            builderNameRef.style.display = 'none';
            builderNameSpanRef.innerText = builderNameRef.value.trim();
            builderNameSpanRef.style.display = 'inline';

            yearOfEstablishmentRef.style.display = 'none';
            yearOfEstablishmentSpanRef.innerText = yearOfEstablishmentRef.value;
            yearOfEstablishmentSpanRef.style.display = 'inline';

            amountRef.style.display = 'none';
            amountSpanRef.innerText = amountRef.value;
            amountSpanRef.style.display = 'inline';

            /* check year as 4 digits */
            /* const yearValue = Number(yearOfEstablishmentRef.value.trim());
            yearOfEstablishmentRef.classList.remove('error');

            if (Number.isNaN(yearValue) || yearOfEstablishmentRef.value.trim().length !== 4) {
                // show error

                hasError = true;

                setTimeout(function() {
                    yearOfEstablishmentRef.classList.add('error');
                }, 500);
            } else {
                yearOfEstablishmentRef.style.display = 'none';
                yearOfEstablishmentSpanRef.innerText = yearOfEstablishmentRef.value;
                yearOfEstablishmentSpanRef.style.display = 'inline';
            } */

            /* check amount as number */
            /* const amountValue = Number(amountRef.value.trim());
            amountRef.classList.remove('error');

            if (Number.isNaN(amountValue) || amountValue <= 0) {
                // show error

                hasError = true;

                setTimeout(function() {
                    amountRef.classList.add('error');
                }, 500);
            } else {
                amountRef.style.display = 'none';
                amountSpanRef.innerText = amountRef.value;
                amountSpanRef.style.display = 'inline';
            } */

            portAddressRef.style.display = 'none';
            portAddressSpanRef.innerText = portAddressRef.value.trim();
            portAddressSpanRef.style.display = 'inline';
            portNameRef.style.display = 'none';
            portNameSpanRef.innerText = portNameRef.value.trim();
            portNameSpanRef.style.display = 'inline';

            /* Validate Date Value */
            const subscribedDateValue = subscribedDateRef.value.trim();
            const pattern = /^\d{2}\/\d{2}\/\d{4}$/;
            subscribedDateRef.classList.remove('error');
            if (!pattern.test(subscribedDateValue)) {
                // show error

                hasError = true;

                setTimeout(function() {
                    subscribedDateRef.classList.add('error');
                }, 500);
            } else {
                subscribedDateRef.style.display = 'none';
                subscribedDateSpanRef.innerText = subscribedDateRef.value.trim();
                subscribedDateSpanRef.style.display = 'inline';

            }

            aboveNamedRef.style.display = 'none';
            aboveNamedSpanRef.innerText = aboveNamedRef.value.trim();
            aboveNamedSpanRef.style.display = 'inline';
            presenceOfRef.style.display = 'none';
            presenceOfSpanRef.innerText = presenceOfRef.value.trim();
            presenceOfSpanRef.style.display = 'inline';
            fatherName2Ref.style.display = 'none';
            fatherName2SpanRef.innerText = fatherName2Ref.value.trim();
            fatherName2SpanRef.style.display = 'inline';
            townVillageName2Ref.style.display = 'none';
            townVillageNameSpan2Ref.innerText = townVillageName2Ref.value.trim();
            townVillageNameSpan2Ref.style.display = 'inline';
            district2Ref.style.display = 'none';
            districtSpan2Ref.innerText = district2Ref.value.trim();
            districtSpan2Ref.style.display = 'inline';
            stateName2Ref.style.display = 'none';
            stateNameSpan2Ref.innerText = stateName2Ref.value.trim();
            stateNameSpan2Ref.style.display = 'inline';
            ownerSignatureRef.style.display = 'none';
            ownerSignatureSpanRef.innerText = ownerSignatureRef.value.trim();
            ownerSignatureSpanRef.style.display = 'inline';


            if (hasError) {
                // window.parent.showAlert(false);
                // invalid data

                $('#alertModal .modal-body').html(
                    "<h3><?= $settings->inv_data->$lang ?></h3>"
                );

                const myModalEl = document.getElementById('alertModal');
                const modal = new bootstrap.Modal(myModalEl, {
                    keyboard: true,
                    backdrop: 'static'
                });
                modal.show();
            } else {
                // no error 
                /* Display save pdf dialog */

                var divToPrint = document.getElementById('printable');
                var htmlToPrint = '' +
                    '<style type="text/css">' +
                    $('#styleToPrint').html() +
                    '</style>';
                htmlToPrint += divToPrint.outerHTML;
                newWin = window.open("");
                newWin.document.write(htmlToPrint);
                newWin.print();
                newWin.close();
            }

        } else {
            // window.parent.showAlert(true);
            // incomplete form

            $('#alertModal .modal-body').html(
                "<h3><?= $settings->inc_data->$lang ?></h3>"
            );

            const myModalEl = document.getElementById('alertModal');
            const modal = new bootstrap.Modal(myModalEl, {
                keyboard: true,
                backdrop: 'static'
            });
            modal.show();

        }
    };

    document.getElementById('resetFormDesign').onclick = function() {
        applicantNameRef.style.display = 'inline';
        applicantNameSpanRef.style.display = 'none';
        fatherNameRef.style.display = 'inline';
        fatherNameSpanRef.style.display = 'none';
        townVillageNameRef.style.display = 'inline';
        townVillageNameSpanRef.style.display = 'none';
        yearOfEstablishmentRef.classList.remove('error');
        yearOfEstablishmentRef.style.display = 'inline';
        yearOfEstablishmentSpanRef.style.display = 'none';
        townVillageNameRef.style.display = 'inline';
        townVillageNameSpanRef.style.display = 'none';
        amountRef.classList.remove('error');
        amountRef.style.display = 'inline';
        amountSpanRef.style.display = 'none';
        subscribedDateRef.classList.remove('error');
        subscribedDateRef.style.display = 'inline';
        subscribedDateSpanRef.style.display = 'none';
        districtRef.style.display = 'inline';
        districtSpanRef.style.display = 'none';
        stateNameRef.style.display = 'inline';
        stateNameSpanRef.style.display = 'none';
        fullAddressNameRef.style.display = 'inline';
        fullAddressNameSpanRef.style.display = 'none';
        nameRef.style.display = 'inline';
        nameSpanRef.style.display = 'none';
        atRef.style.display = 'inline';
        atSpanRef.style.display = 'none';
        builderNameRef.style.display = 'inline';
        builderNameSpanRef.style.display = 'none';
        aboveNamedRef.style.display = 'inline';
        aboveNamedSpanRef.style.display = 'none';
        presenceOfRef.style.display = 'inline';
        presenceOfSpanRef.style.display = 'none';
        portAddressRef.style.display = 'inline';
        portAddressSpanRef.style.display = 'none';
        portNameRef.style.display = 'inline';
        portNameSpanRef.style.display = 'none';
        fatherName2Ref.style.display = 'inline';
        fatherName2SpanRef.style.display = 'none';
        townVillageName2Ref.style.display = 'inline';
        townVillageNameSpan2Ref.style.display = 'none';
        district2Ref.style.display = 'inline';
        districtSpan2Ref.style.display = 'none';
        stateName2Ref.style.display = 'inline';
        stateNameSpan2Ref.style.display = 'none';
        ownerSignatureRef.style.display = 'inline';
        ownerSignatureSpanRef.style.display = 'none';

    }
</script>