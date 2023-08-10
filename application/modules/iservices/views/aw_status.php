<style>
.rtps-container {
    width: 1145px;
    margin: 0 auto;
    padding: 1rem 0.5rem;
    max-width: 100%;
}
.rtps-container form {
    display: flex;
    flex-flow: column nowrap;
    align-items: center;
    justify-content: center;
    border: 1px solid lightgray;
    border-radius: 0.25rem;
    padding: 1.2rem;
}
.form-container {
    width: 65%;
    margin: 0.8rem 0;
}
.form-container label {
    display: inline-block;
    cursor: default;
    font-size: larger;
    font-weight: bold;
    padding-bottom: 0.3em;
}
.form-container input, .form-container select {
    display: block;
    width: 100%;
    height: 37px;
    border-radius: 0.25rem;
    font-size: medium;
    padding: 0.5em;
    outline: none;
}
.error-message {
    padding-top: 0.3em;
    margin: 0;
    color: red;
    font-weight: lighter;
    display: none;
}
#track-btn {
    min-width: 200px;
    font-size: medium;
}
.my-datatable-button {
    display: block;
    min-width: 150px;
    background: #C06C84;
    border-radius: .30rem;
    color: aliceblue !important;
    box-shadow: 0 3px 2px 0 rgba(0, 0, 0, 0.1);
    cursor: pointer;
    text-align: center;
    padding: 8px;
    text-transform: uppercase;
    margin: 3px 0 8px 0;
    border: none;
}
.track-data {
    margin: 2rem 0;
}
.track-error {
    /* display: none; */
    text-align: center;
    text-transform: capitalize;
    font-weight: bold;
    font-size: larger;
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
    padding: 1rem;
}
.status-container {
  display: flex;
  flex-flow: column nowrap;
  align-items: center;
  justify-content: center;
  border: 1px solid lightgray;
  border-radius: 0.25rem;
  padding: 1.2rem;
}
.s-header {
  padding: 15px;
}
</style>


<!-- HTML Body -->

<div id="includedContent_header"></div>
<div class="status-container">
<main class="rtps-container">

  <section class="track-data">
    <div class="content-wrapper">


    <?php if (empty($result)): ?>
      <div class="track-error">
        <i class="fa fa-exclamation-circle" ></i>
        <span>Either application number is invalid or unable to get data from Server. Please try with correct Number or try after sometime</span>
      </div>
    <?php else: ?>
      <h2 class="s-header">Application Status (<?=$result->app_ref_no?>)</h2>
      <table class="table bordered">
        <tr>
          <th>Sl No</th>
          <th>User Name</th>
          <th>User Designation</th>
          <th>User Office</th>
          <th>Received Time</th>
          <th>Executed Time</th>
          <th>Remark</th>
          <th>Action</th>
        </tr>
        <?php foreach ($result->task_details as $key => $value): ?>
          <tr>
            <td><?=$key+1?></td>
            <td><?=$value->user_name?></td>
            <td><?=$value->user_designation?></td>
            <td><?=$value->user_office?></td>
            <td><?=$value->received_time?></td>
            <td><?=$value->executed_time?></td>
            <td><?=$value->remark?></td>
            <td>
            <?=$value->action?>
              <?php if ($value->action === "Query"): ?>
                <a target="_BLANK" href="<?=$value->url?>?data=<?=$result->app_ref_no?>" class="btn btn-info btn-sm">Reply</a>
              <?php endif; ?>
              <?php if ($value->action === "Complete"): ?>
                <a target="_BLANK" href="<?=$value->url?>?data=<?=$result->app_ref_no?>" class="btn btn-success btn-sm">Download</a>
              <?php endif; ?>

            </td>
          </tr>
        <?php endforeach; ?>
      </table>
    <?php endif; ?>

    <div>

  </section>


</main>
  </div>

  <!-- <script>
    $(function(){
        $.post("https://auwssb.online/track.asmx/Track",
  {
    data: "ds4qLdbh4TgqMH+GwRZsaX+hJqLTd5J9d6zAOYzB+Rlo2YgXaxGR8+pYAzxIyGq67SjhgcBwjCkOZfZJnjGxXQ=="
  },
  function(data, status){
    alert("Data: " + data + "\nStatus: " + status);
  });
        
    })
      
</script> -->