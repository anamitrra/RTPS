<?php
$lang = $this->lang;

// pre($dept_list);

?>

<main id="main-contenet">

    <section class="container">

        <form action="<?= base_url("site/add_service_action") ?>" method="post">

            <input class="form-control" type="text" name="service_id" placeholder="Enter service Id" required >

            <label for="dept_id">Choose a Department:</label>

            <select name="dept_id">
            <?php foreach ($dept_list as $dept) : ?>
                <option value="<?= $dept->department_id ?>"><?= $dept->department_name->en; ?></option>
            <?php endforeach; ?>
            </select>

            <div class="col-12">
                <div class="form-group">
                    <label for="">Service Name: </label>
                    <div>
                        <input name="service_en" type="text" placeholder="english" required>
                    </div>
                    <div>
                        <input name="service_as" type="text" placeholder="assamese" required>
                    </div>
                    <div>
                        <input name="service_bn" type="text" placeholder="bengali" required>
                    </div>
                </div>
            </div>

            <h6>Available Online ? </h6>
            <input type="radio" id="true" name="online" value="true">
            <label for="true"> Yes</label>
            <input type="radio" id="false" name="online" value="false" checked>
            <label for="false"> No</label><br>

            <button class="btn btn-primary" type="submit">Add Service</button>
        </form>

    </section>

</main>