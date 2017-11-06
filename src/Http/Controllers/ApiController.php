<?php

namespace Hosamaldeen\LaraCRUD\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hosamaldeen\LaraCRUD\Http\Controllers\ModelGenerator ;
use Illuminate\Support\Facades\Input;

class ApiController extends Controller {
   
    function checkModel($table) 
    {
		
        $modelGenerator = new ModelGenerator ;
		
		$data = $modelGenerator->checkModel($table);
		foreach($data as $key=>$row)
		{
			unset($data[$key]['baseClass']);
		}
		return $data ;
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
		if(!Input::has('table') ||  !Input::has('file'))
		{
			$data['status'] = false ;
			$data['error'] = 'table and file input are required' ;
			echo json_encode($data);
			die ;
		}
		$table = Input::get('table');
		$file = Input::get('file');
		
		$modelGenerator = new ModelGenerator ;
		$modelGenerator->generateModel($table ,$file) ;
		$data['status'] = true ;
		echo json_encode($data);
    }

}