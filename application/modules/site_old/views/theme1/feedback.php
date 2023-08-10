<?php
$lang = $this->lang;
?>

<script src="<?= base_url('assets/site/'.$theme.'/js/feedback.js') ?>" defer></script>

<main id="main-contenet">
<div class="container">

    <nav aria-label="breadcrumb" class="nav-bread d-flex justify-content-start align-items-baseline">
        <ol class="breadcrumb m-0">

            <?php foreach ($settings->nav as $key => $link): ?>

                <li class="breadcrumb-item <?= empty($link->url) ? 'active' : ''?>" <?= empty($link->url) ? 'aria-current="page"' : ''?> >

                    <?php if(isset($link->url)): ?>

                        <a href="<?= base_url($link->url) ?>"><?=  $link->$lang ?></a>

                    <?php else: ?>
                        <?= $link->$lang ?>


                    <?php endif; ?>

                </li>

            <?php endforeach; ?>

        </ol>
    </nav>

    <div class="row justify-content-md-center">
        <div class="col-md-8">

            <?= form_open(base_url('site/feedback/feedback_action'), 'autocomplete="off"'); ?>

                <fieldset>
                    <legend class="border-bottom pb-1 fw-bold feed-head <?= empty($this->session->flashdata('success')) ? 'mb-4' : '' ?>"><?=$settings->heading->$lang?></legend>
                    
                    <?php if ($this->session->flashdata('success')): ?>
                    
                        <p class="fw-bold text-success mb-4"><?= $this->session->flashdata('success') ?></p>
                    
                    <?php endif; ?>


                    <div class="mb-3">
                        <label for="name" class="form-label feed-name">
                            <?= $settings->full_name->$lang?>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control <?= ! empty(form_error('name')) ? 'is-invalid' : '' ?>" name="name" id="name" required value="<?= set_value('name'); ?>">
                        <div class="text-danger"><?php echo form_error('name'); ?></div>
                       
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label feed-email"><?= $settings->email->$lang?></label>
                        <input type="email" value="<?= set_value('email'); ?>" class="form-control <?= ! empty(form_error('email')) ? 'is-invalid' : '' ?>" name="email" id="email">
                        <div class="text-danger"><?php echo form_error('email');?></div>
                      
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label feed-phone"> 
                            <?= $settings->phone->$lang?>
                            <span class="text-danger">*</span>
                        </label>
                        <input type="tel" value="<?= set_value('phone'); ?>" class="form-control <?= ! empty(form_error('phone')) ? 'is-invalid' : '' ?>" name="phone" id="phone" required>
                        <div class="text-danger"><?php echo form_error('phone'); ?></div>
                       
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label feed-add"><?= $settings->address->$lang?></label>
                        <textarea class="form-control <?= ! empty(form_error('address')) ? 'is-invalid' : '' ?>" id="address" name="address" rows="3"><?= set_value('address'); ?></textarea>
                        <div class="text-danger"><?php echo form_error('address'); ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="feedback" class="form-label feed-feed"> 
                            <?= $settings->feedback->$lang?>
                            <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control <?= ! empty(form_error('feedback')) ? 'is-invalid' : '' ?>" id="feedback" name="feedback" rows="3" required><?= set_value('feedback'); ?></textarea>
                        <div class="text-danger"><?php echo form_error('feedback'); ?></div>

                    </div>
                    <div class="mb-3">
                        <label for="feedback" class="form-label feed-feed"> 
                            <?= $settings->rating->$lang?>
                        </label>
                         <section id="rating-panel">
                            <div class="star" data-value=1></div>
                            <div class="star" data-value=2></div>
                            <div class="star" data-value=3></div>
                            <div class="star" data-value=4></div>
                            <div class="star" data-value=5></div>

                            <button type="button" title="<?= $settings->clr_rating->$lang ?>" id="clear">
                                <img src="<?= base_url('assets/site/'.$theme.'/images/clear.png') ?>" alt="clear rating">
                            </button>

                        </section>
                        <input type="hidden" name="rating" value="0">
                        <div class="text-danger"><?php echo form_error('rating'); ?></div>
                    </div>

                    <div class="row mb-5">
                        <div class="col-12 col-md-6">
                            <span id="captchaParent">
                                <?= $cap['image']; ?>
                            </span>
                            
                             <button type="button" class="btn btn-sm ms-2" id="refreshCaptcha">
                                <?=$settings->cap_ref->$lang?>
                            </button>
                        </div>
                        <div class="col-12 col-md-6 position-relative mt-3 mt-md-0">
                            <label for="captcha" class="position-absolute" style="left: -3px;"><span class="text-danger">*</span></label>
                            <input type="text" class="form-control <?= ! empty(form_error('captcha')) ? 'is-invalid' : '' ?>" id="captcha" name="captcha" id="captcha"
                                placeholder="<?=$settings->cap_code->$lang?>" maxlength="6" required>
                            
                            <div class="text-danger"><?php echo form_error('captcha'); ?></div>

                        </div>
                               
                    </div>
                    
                   
                    <button  type="submit" class="btn rtps-btn btn-lg"><?=$settings->submit->$lang?></button>
                    <button type="reset" class="btn rtps-btn btn-lg"><?=$settings->reset->$lang?></button>
                </fieldset>
            </form>
        </div>
    </div>


</div>
</main>

<script>
    var captchaURL = "<?= base_url('site/feedback/refresh_captcha')?>";
    var errorMsg = 'error';
</script>
