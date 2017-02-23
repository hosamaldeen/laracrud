<?php

namespace Hosamaldeen\LaraCRUD\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hosamaldeen\LaraCRUD\Http\Controllers\ModelGenerator ;

class LaraCrudController extends Controller {
   
    function index() 
    {
        return view('laraCRUD::index');
    }

    function model() 
    {
        $modelGenerator = new ModelGenerator ;
        $data['tables'] = $modelGenerator->getDbTables();
        if(isset($_GET['create_base']))
            $data['isBaseModelExist'] = false ;
        else
            $data['isBaseModelExist'] = $modelGenerator->checkBaseModelFile();
        
        if(\Request::input('preview'))
        {
            if(\Request::input('all_tables'))
            {
                foreach($data['tables'] as $table)
                {
                    // extract baseModelClass from files
                    $files = $modelGenerator->checkModel($table) ;
                    foreach($files as $key=>$file)
                    {
                        if(isset($file['baseClass']) && $file['baseClass']===true)
                        {
                            $data['baseClass'] = $file ;
                            unset($files[$key]);
                        }
                        
                    }
                    //===============
                    
                    
                    $data['files'][] = [
                        'table'=> $table,
                        'files'=> $files
                    ];
                }
            }
            else
            {
                $table = \Request::input('table') ;
                $data['files'] = $modelGenerator->checkModel($table);
            }
        }
        
        if(\Request::input('generate'))
        {
            if(\Request::input('file'))
            foreach(\Request::input('file') as $table=>$files)
            {
                foreach($files as $file)
                    $modelGenerator->generateModel($table ,$file) ;   
            }
            $data['success'] = true ;
        }
        
        return view('laraCRUD::model' , $data);
    }
    
    function crud()
    {
        $data = [] ;
        return view('laraCRUD::crud' , $data);
    }
}