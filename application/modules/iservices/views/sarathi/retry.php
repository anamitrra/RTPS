<div id="includedContent_header"></div>

<main class="rtps-container">
    <form action="<?= $action ?>" name="frm1" id="frm1" method="post">
        <div class="form-container">
            <div class="row">
                <input type="hidden" name="agId" id="agId" value="<?=$agId?>">
                <input type="hidden" name="agencyPassword" id="agencyPassword" value="<?=$agencyPassword?>">
                <input type="hidden" name="tkNo" id="tkNo" value="<?=$tkNo?>">
                <input type="hidden" name="agCd" id="agCd" value="<?=$agCd?>">
                <input type="hidden" name="serCd" id="serCd" value="<?=$serCd?>">
                <input type="hidden" name="kioskId" id="kioskId" value="<?=$kioskId?>">
                <input type="hidden" name="sarAppl" id="sarAppl" value="<?=$sarAppl?>">
            </div>
        </div>

        <p>Redirecting</p>
    </form>


</main>
<script>
    document.getElementById('frm1').submit();
</script>