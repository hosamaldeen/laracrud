@extends('laraCRUD::layout')
@section('content')
<?php
use Hossamahmed\LaraCRUD\helpers\helper ;
?>
<div class="container" >
    
<h1>Model Generator </h1>
<div class='row'>
    <div class="col-md-4 " >
    <?php if(isset($success)){ ?>
    <div class="alert alert-success" >Operation Success</div>
    <?php } ?>
    <form>
        <label>Table Name</label>
        <br/>
        
        <input type='checkbox' name='all_tables' value='1'  <?= helper::checked(@$_GET['all_tables'],  1)?> >
        <label>All Tables</label>
        <br/>
        <div class='select_table'>
            <label>Or</label>
        
            <select name="table" class="form-control select2">
                    <option value="" >Select Table</option>
                <?php 
                if(isset($tables))
                foreach($tables as $table){ ?>
                    <option <?= helper::selected($table, @$_GET['table'])?> value="{{$table}}" >{{$table}}</option>
                <?php } ?>
            </select>
            <br/><br/>
        </div>
        
        <input type='checkbox' name='create_base' value='1'  <?= helper::checked($isBaseModelExist, FALSE )?> >
        Create Base Model 
        <br/><br/>
        <?php if(isset($files)){ ?>
            <table class='table table-borderd files_table' >
                <tr>
                    <td>File</td>
                    <td>Status</td>
                    <td><input type="checkbox" class='checkall'></td>
                </tr>
                <?php
                if(isset($baseClass))
                {
                    drawTrFile($baseClass ,'base');
                }
                ?>
            <?php
            foreach($files as $file)
            {
                
                if(\Request::input('all_tables'))
                {
                    ?>
                        
                    <tr style='background: #dcdcdc'  >
                        <td colspan="5" style="text-align: center;" ><?=$file['table']?></td>
                    </tr>     
                    <?php
                    foreach($file['files'] as $file0)
                    {
                        drawTrFile($file0 , $file['table']);
                    }
                }
                else
                {
                    drawTrFile($file , \Request::input('table'));
                }
            }
            ?>
            </table>
            <input type="submit" name='generate' class="btn btn-success" value="Generate">                
        <?php } ?>
            <input type="submit" name='preview' class="btn btn-primary" value="Preview">    

    </form>
</div>
</div>
</div>


<script>
$(document).ready(function(){
    $('input[name=all_tables]').click(function(){
       $('.select_table').toggle('slow');
    })
    
    $(".checkall").click(function(){
        $('.files_table input:checkbox').not(this).prop('checked', this.checked);
    });
});
</script>
<?php
function drawTrFile($file , $table)
{
    if(!$file['exist'])
    {
        $status = "New";
        $checkbox = "<input type='checkbox' name='file[".$table."][]' checked value='".$file['file']."' >";
        $background = 'rgba(59, 241, 0, 0.18)';
    }
    else if($file['changed'])
    {
        $status = "Changed";
        $checkbox = "<input type='checkbox' name='file[".$table."][]' value='".$file['file']."' >";
        $background = 'rgba(241, 216, 0, 0.18)';
    }
    else
    {
        $status = "Unchanged";
        $checkbox = "";
        $background = '#fff';
    }
    ?>
        <tr style='background: <?=$background?>' >
            <td><?=$file['file']?></td>
            <td><?=$status?></td>
            <td><?=$checkbox?></td>
        </tr>        
    <?php
}
?>
@stop()

