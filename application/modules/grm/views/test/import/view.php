<div class="container">
    <form method="POST" action="<?=base_url('grm/test/import/data')?>" enctype="multipart/form-data">
    <input type="text" name="test" value="data">
        <input type="file" name="excel" id="" class="form-control">
        <button type="submit" name="submit" class="btn btn-outline-dark">submit</button> 
    </form>
</div>