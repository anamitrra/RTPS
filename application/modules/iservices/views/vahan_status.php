<style>
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
.form-container label::before {
    content: "* ";
    color: red;
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

#appl-no:focus, #state:focus {
   border: 2px solid #1479bd;
}

#track-btn {
    min-width: 200px;
    font-size: medium;
}

.error-message {
    padding-top: 0.3em;
    margin: 0;
    color: red;
    font-weight: lighter;
    display: none;
}

/* CSS during Data Loading  */
.disable-btn {
    opacity: 0.4;
    cursor: not-allowed;
}

.track-data {
    margin: 2rem 0;
}

.track-error {
    display: block;
    text-align: center;
    text-transform: capitalize;
    font-weight: bold;
    font-size: larger;
    color: #721c24;
    background-color: #f8d7da;
    border-color: #f5c6cb;
    padding: 1rem;
}

.track-success {
    display: none;
    padding: 1.7rem;
    border-radius: 0.25rem;
    color: #383d41;
    background-color: #f8f8f8;
    border-color: #d6d8db;
}

.track-success li {
    padding: 0.8rem 0;
    border-bottom: 1px solid lightgray;
}
.track-success li:last-child {
    border: none;
}

.appl-track-label {
    display: inline-block;
    min-width: 45%;
    font-size: 1rem;
    font-weight: bold;
    text-align: end;
    padding: 0.2rem;
}

.appl-track-data {
    display: inline-block;
    max-width: 50%;
    padding-left: 0.7rem;
}



/* For mobile views */
@media all and (max-width: 576px) {
    .form-container {
        width: 100%;
    }
}

span.transport-status {
    display: inline-flex;
    flex-flow: column nowrap;
}
.rtps-container {
    width: 1145px;
    margin: 0 auto;
    padding: 1rem 0.5rem;
    max-width: 100%;
}

.s-header {
  padding: 5px;
  text-align: center;
}
</style>
<?php //var_dump($result->currentlist[0]->registeredAt);die; ?>

<!-- HTML Body -->

<div id="includedContent_header"></div>

<main class="rtps-container">

  <section class="track-data">
    <div class="content-wrapper">
    <div class="status-container">

    <?php if (empty($result)): ?>
      <div class="track-error">
        <i class="fa fa-exclamation-circle" ></i>
        <span>Either application number is invalid or unable to get data from Server. Please try with correct Number or try after sometime</span>
      </div>
    <?php else: ?>
      <!-- <h2 class="s-header">Application Status </h2> -->

      <section class="track-data">
            <div class="track-success " style="display: block;">
      <ul>
        <li>
          <span class="appl-track-label" data-i18n="rtps-transport-track-appl-no">Application No. :</span>
          <span class="appl-track-data"><?=$result->currentlist[0]->appl_no?></span>
        </li>
        <li>
          <span class="appl-track-label" data-i18n="rtps-transport-track-appl-date">Application Date :</span>
          <span class="appl-track-data"><?=$result->currentlist[0]->appl_dt?></span>
        </li>
        <li>
          <span class="appl-track-label" data-i18n="rtps-transport-track-reg-no">Registration No. :</span>
          <span class="appl-track-data"><?=$result->currentlist[0]->regno?></span>
        </li>
        <li>
          <span class="appl-track-label" data-i18n="rtps-transport-track-desc">Description :</span>
          <span class="appl-track-data"><?=$result->currentlist[0]->purCdDescr?></span>
        </li>
        <li>
          <span class="appl-track-label" data-i18n="rtps-transport-track-reg-at">Registered At :</span>
          <span class="appl-track-data"><?=$result->currentlist[0]->registeredAt?></span>
        </li>
        <li>
          <span class="appl-track-label" data-i18n="rtps-transport-track-curr-status">Current Status :</span>
          <span class="appl-track-data transport-status">
          <?php foreach ($result->detaillist as $key => $value): ?>
                <span ><?=$value->statusDesc?></span>
          <?php endforeach; ?>
        </span>
          <!-- <span class="appl-track-data transport-status"><span>qwe</span><span>qwe</span><span>qwe</span><span>qwe</span><span>qwe</span><span>qwe</span><span>qwe</span></span> -->
        </li>
      </ul>
    </div>
        </section>



        <!-- <div class="track-success">
      <ul>
        <li>
          <span class="appl-track-label">Application No. :</span>
          <span class="appl-track-data"><?=$result->currentlist[0]->appl_dt?></span>
        </li>
        <li>
          <span class="appl-track-label">Application Date :</span>
          <span class="appl-track-data"><?=$result->currentlist[0]->appl_no?></span>
        </li>
        <li>
          <span class="appl-track-label">Registration No. :</span>
          <span class="appl-track-data"><?=$result->currentlist[0]->regno?></span>
        </li>
        <li>
          <span class="appl-track-label">Description :</span>
          <span class="appl-track-data"><?=$result->currentlist[0]->purCdDescr?></span>
        </li>
        <li>
          <span class="appl-track-label">Registered At :</span>
          <span class="appl-track-data"><?=$result->currentlist[0]->registeredAt?></span>
        </li>
        <li>
          <span class="appl-track-label">Current Status :</span>

        </li>
        <?php foreach ($result->detaillist as $key => $value): ?>
              <span class="appl-track-data transport-status"><?=$value->statusDesc?></span>
        <?php endforeach; ?>

      </ul>
    </div> -->
    <?php endif; ?>

    <div>
    </div>
  </section>

</main>
