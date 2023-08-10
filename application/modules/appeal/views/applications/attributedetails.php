<pre>
<?php //print_r((array)$attribute_detais);?>
</pre>
<?php
foreach($attribute_details as $key=>$value){
if(!is_array($value)){?>
<div class="row">
    <div class="col-md-5"><label><?=ucfirst(str_replace("_", " ", $key));?></label></div>
    <div class="col-md-7"><?=$value?></div>
</div>
<?php }else{
    $keys=array();
    $ki=0;
    $int_array=(array)$value[0];
?>
<h6 class="text-center"><?= ucfirst(str_replace("_", " ", $key)) ?></h6>
<table class="table text-font10">
    <thead>
        <tr>
            <th scope="col">#</th>
            <?php foreach($int_array as $k => $v){?>
            <th scope="col"><?=ucwords($k);?></th>
            <?php $keys[$ki]=$k;$ki++; } ?>
        </tr>
    </thead>
    <tbody>
        <?php
        //pre($keys);
    
    ?>
 <tr>
            
            <?php foreach($value as $key => $v){
                $arr_v=(array)$v;?>
                <td scope="col"><?=($key+1)?></td>
                <?php
                foreach($arr_v as $key_3=>$val_3){ ?>
                <td scope="col"><?=print_r($val_3);?></td>
                <?php }
                ?>
                
            <?php }?>
        </tr>
        <?php 
?>
    </tbody>
</table>
<?php
}
}
?>
