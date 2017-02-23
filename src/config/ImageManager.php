<?php
return [
    
    /*
     * path where you should upload images
     * (.) =>refere to public folder
     */
    'upload_path'=>'./uploads',
    
    /*
     * allowed Image type
     */
    'alloweed_types'=>[
        'png',
        'jpeg',
        'jpg',
    ],
    
    
    /*
     * enable create thumbnail from uploaded image
     * you can set the thumbnail size as array [width,hight] , you can set hight=0 as auto detect  
     * for best image size let hight=0
     */
    'enable_thumbs'=>TRUE,
    'thumb_size'=>[600,0],
    
    /*
     * enable create small thumbnail from uploaded image
     * you can set the thumbnail size as array [width,hight] , you can set hight=0 as auto detect  
     * for best image size let hight=0
     */
    'enable_small_thumbs'=>TRUE,
    'small_thumbs_size'=>[250,0],
    
    /*
     * applay middelware groupe to this package controller 
     * ex :- 'middelware_group'=>'backend' or 'middelware_group'=>'web'
     */
    'middelware_group'=>''
    
    
];

