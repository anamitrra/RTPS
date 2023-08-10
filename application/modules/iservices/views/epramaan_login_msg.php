<style>
    .epramaan_msg {
        font-size: 17px;
        text-align: justify
    }
</style>
<div class="container epramaan_msg" style="min-height:62vh">
    <div class="col-md-6 mx-auto">
        <div class="card shadow mt-5">
            <div class="card-header bg-dark text-center text-white">Citizen Login</div>
            <div class="card-body">
                <p>Now we are upgrading to e-Pramaan SSO login. If you are an existing user of e-Pramaan, you can login using e-Pramaan. Otherwise register with it using your RTPS linked mobile number.</p>
                <p class="text-center"><?= $this->epramaan->epramaan_login_btn('citizen'); ?></p>
            </div>
        </div>
    </div>
</div>