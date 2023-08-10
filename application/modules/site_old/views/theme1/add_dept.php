<?php
$lang = $this->lang;

// pre($settings);

?>


<main id="main-contenet">

    <section class="container">

        <form action="<?= base_url("site/add_dept_action") ?>" method="post">
            <input class="form-control" type="text" name="department_id" required placeholder="Enter department Id" />
            
                <div class="col-12">
                    <div class="form-group">
                        <label for="">Department Name: </label>
                        <div>
                            <input name="en" type="text" placeholder="english" required>
                        </div>
                        <div>
                            <input name="as" type="text" placeholder="assamese" required>
                        </div>
                        <div>
                            <input name="bn" type="text" placeholder="bengali" required>
                        </div>
                    </div>
                </div>

            <input class="form-control" type="text" name="department_short_name" required placeholder="Enter department short name" />
            <input class="form-control" type="text" name="icon" required placeholder="Enter department icon name" />
            <h6>Available Online ? </h6>
            <input type="radio" id="true" name="online" value="true">
            <label for="true"> Yes</label>
            <input type="radio" id="false" name="online" value="false" checked>
            <label for="false"> No</label><br>

            <button class="btn btn-primary" type="submit">Add Department</button>
        </form>

    </section>

</main>
