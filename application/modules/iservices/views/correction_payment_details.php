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


    
      <h2 class="s-header">Payment Details (<?=$app_ref_no?>)</h2>
      <div style="padding-left: 15px;">
          <?php
            
            echo "Upaid Amount  :₹".$amount."<br/>"; 
          ?>
        <a href="<?=base_url("iservices/Paymentcorrection/payment/".$rtps_trans_id)?>" class="btn btn-primary white" style="margin-top: 39px;color: white;">Pay Now (₹ <?=$amount?>)</a>
      </div>
     

    <div>

  </section>


</main>
  </div>
