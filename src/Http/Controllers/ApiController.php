<?php

namespace Hosamaldeen\LaraCRUD\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hosamaldeen\LaraCRUD\Http\Controllers\ModelGenerator ;

class ApiController extends Controller {
   
    function checkModel($table) 
    {
		
        $modelGenerator = new ModelGenerator ;
		
		$data = $modelGenerator->checkModel($table);
		foreach($data as $key=>$row)
		{
			unset($data[$key]['baseClass']);
		}
		echo json_encode($data);
    } 
	
	function checkBaseModel() 
    {
		$modelGenerator = new ModelGenerator ;
		$data['exist'] =$modelGenerator->checkBaseModelFile();
		echo json_encode($data);
    }
	
	function generateBaseModel(){
		$modelGenerator = new ModelGenerator ;
		$file = app_path().'/Models/Base/BaseModel.php' ;
		$modelGenerator->generateModel('' ,$file) ;
		$data['status'] = true ;
		echo json_encode($data);
	}
	
	function generate() 
    {
		$table = input::get('table');
		//$file
		echo $table ;
		die ;
		$modelGenerator->generateModel($table ,$file) ;
		$data['status'] = true ;
		echo json_encode($data);
    }

}