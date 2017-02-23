
<script type="text/javascript">

$(document).ready(function(){
    var number_of_selected_images=0;
    var get_images_url='<?=url('image-manager/get-images')?>';
    
    getImages();
    
    $('.image_manger_choose').scroll(function() {
        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
            if (get_images_url === null){
                return false;
            }
            getImages();
        }
    });
    
    $('.image_manger_search_button').click(function(){
        
        $('.image_manger_image').remove();
        var keyword=$(this).closest('.tab-content').find('input.image_manger_search').val();
        $('input.image_manger_search').val(keyword);
        if(keyword.length>0){
            get_images_url='<?=url('image-manager/get-images')?>/'+keyword;
        }else{
            get_images_url='<?=url('image-manager/get-images')?>';
        }
        getImages();
    });
    
    $(document).on('click', '.image_manger_image', function () {   
        console.log(this);
        if($(this).attr('data-select')==='1'){
            console.log('unselect');
            unSelectImage(this);
            $(this).closest('div.modal').attr('data-count',)
        }else{
            var is_multi=$(this).closest('div.modal').attr('data-multi');
            var selected_count=$(this).closest('div.modal').attr('data-count');
            if(is_multi===0){
                if(selected_count===0){
                    console.log('select');
                selectImage(this);
                }else{
                    alert('you must choose only one Image');
                }
            }else{
                console.log('select');
                selectImage(this);
                $(this).closest('div.modal').attr('data-count','1')
            }
            
        }
        
        if(number_of_selected_images>0){
            $('.image_manger_save').show();
        }else{
            $('.image_manger_save').hide();
        }
    });
    
    $('.image_manger_save').click(function(){
        var modal_div=$(this).closest('div.modal');
        var images=modal_div.find('.image_manger_image[data-select="1"]');
        var varibale_name=$(this).attr('data-name');
       // modal_div.prev('.image_manger_inputs').html('');
        images.each(function(){
            var image_src=$(this).find('img').attr('src');
            var image_id =$(this).attr('data-id');
            modal_div.prev('.image_manger_inputs').append(''+
                '<div class="image_container" style="position:relative;border:1px solid;border-color:green;display:inlinr-block;margin:5px;float:left">'+
                    '<img src="'+image_src+'" style="width:150px" />'+
                    '<img class="image_manger_delete_image" src="<?=url('vendor/SayedNofal/ImageManager/images/close.png')?>" style="width:20px;position:absolute;left:-3px;top:-5px;cursor:pointer" />'+
                    '<input type="hidden" value="'+image_id+'" name="'+varibale_name+'" />'+
                '</div>'
            );
            unSelectImage(this);
        });
        $('.close_image_manger').trigger('click');
        $(window).resize();
    });
    
    $(document).on('click','.image_manger_delete_image',function(){
        $(this).closest('div.image_container').remove();
    })
    
    function selectImage(image){
        $(image).css({'border':'5px solid','border-color': '#03A9F4'});
        $(image).attr('data-select','1');
        number_of_selected_images++;
    }
    
    function unSelectImage(image){
        $(image).css({'border':'2px solid','border-color': '#00000'});
        $(image).attr('data-select','0');
        number_of_selected_images--;
    }
    
    function getImages(){
        $.ajax({
           url:get_images_url,
           method:'get',
           beforeSend: function (xhr) {
                $('.image_manger_choose').append('<br style="clear:both"/><img class="image_manager_loader" src="<?=url('vendor/SayedNofal/ImageManager/images/loader.gif')?>" width="100"/>')
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $('.image_manger_choose').html('<br/><span style="color:red">some thing went wrong, try to refresh the page<span>');
            },  
            complete: function (jqXHR, textStatus ) {
              $('.image_manger_choose').find('.image_manager_loader').prev().remove();              
              $('.image_manger_choose').find('.image_manager_loader').remove();                
            },        
           success:function(response){
               response=jQuery.parseJSON(response);
               if(!response.status=='ok'){
                   alert('something went wrong , try to refresh the page');
                   return false;
               }
               get_images_url=response.data.next_page_url;
               for(var i in response.data.data){
                    $('.image_manger_choose').append('<div class="col-md-3 image_manger_image" data-id="'+response.data.data[i].id+'"   style="margin:7px;border:2px solid;overflow:hidden;"><img src="'+response.upload_path+'/'+response.data.data[i].name+'" style="width:150px;" /></div>');
               }  
           }


       }); 
    }
});
</script>