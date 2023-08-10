<?php
$lang = $this->lang;
?>

<main id="main-contenet">
    <section class="container">

        <h1>A Demo Page For Testing</h1>
        <hr>

        <?php echo form_open(base_url('site/demo')); ?>

        <p>
            <label class="fw-bold">Username:</label>
            <input type="text" name="username" required size="50">
        </p>
        <p>
            <label class="fw-bold">Password:</label>
            <input type="password" name="password" required size="50">
        </p>
        <p>
            <label class="fw-bold">User Type: </label>
            <select name="user_type" required>
                <option value="SA">State Admin</option>
                <option value="DA">Department Admin</option>
                <option value="OA">Office Admin</option>
                <option value="MA">MIS Admin</option>
            </select>
        </p>
        <p>
            <label class="fw-bold">Gender:</label>
            <input type="radio" name="gender" value="M"> Male
            <input type="radio" name="gender" value="F"> Female
            <input type="radio" name="gender" value="O"> Other
        </p>
        <p>
            <label class="fw-bold">Email Address:</label>
            <input type="email" name="email" required size="50">
        </p>

        <input type="submit" value="Submit" class="btn btn-lg btn-outline-danger">

        </form>

    </section>
</main>