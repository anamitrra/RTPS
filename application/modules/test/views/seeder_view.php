<div class="container my-2">
    <form method="POST" action="<?=base_url('appeal/test_app/seeder')?>">
        <div class="row form-group">
            <div class="col-12">
                <label for="json">JSON</label>
                <textarea name="json" id="json" cols="30" rows="10" class="form-control" placeholder="JSON or JSON Array Only"></textarea>
            </div>
            <input type="hidden" name="parsedJson" id="parsedJson">
        </div>
        <button type="submit" class="btn btn-outline-success">Seed</button>
    </form>
</div>

<script>
    let jsonRef = document.getElementById('json');
    let parsedJsonRef = document.getElementById('parsedJson');
    jsonRef.addEventListener('change',function (e){
        parsedJsonRef.value = JSON.stringify(JSON.parse(e.target.value));
    });
</script>