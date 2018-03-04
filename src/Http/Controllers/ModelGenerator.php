<?php

namespace Hosamaldeen\LaraCRUD\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ModelGenerator extends Controller {
   
    function __construct() 
    {
        $this->createFolders();
    }
    
    public function getDbTables()
    {
        $tables = \DB::select('SHOW TABLES');        
        $result = [] ;
        foreach ($tables as $table) {
            $result[] = reset($table) ;   
        }
        return $result ;
    }
   
    public function checkModel($table)
    {
        $model = $this->getModelFromTable($table) ;
        $modelDir = $this->getModelDir() ;
        $data['className'] = $model ;
        $data['tableName'] = $table ;
        foreach($this->getModelsFiles($model) as $modelFile)
        {
            if(isset($modelFile['isBaseClass']))
                $baseClass = true ;
            else
                $baseClass = false ;
            
            if(!file_exists($modelFile['location']))
            {
                $return[] = [
                    'file'=> $modelFile['location'] ,
                    'exist'=>  false ,
                    'changed'=>  false,
                    'baseClass'=>$baseClass
                ] ; 
                continue ;
            }
            $temp = view($modelFile['template'] , $data );
            if(isset($modelFile['isBaseModel']) &&  $modelFile['isBaseModel'] === true )
            {
                
                $relations = $this->getTableRelations($table);
                $default = $this->getTableDefault($table);
                $rules = $this->getTableRules($table);
                $comment = $this->getTableComment($table);
                
                $temp = str_replace('//Rules' , $rules , $temp);
                $temp = str_replace('//Default' , $default , $temp);
                $temp = str_replace('//Comments' , $comment , $temp);
                $temp = str_replace('//Relations' , $relations , $temp);
            }
            file_put_contents($modelFile['location_new'], $temp) ;
            $changed = $this->checkDiffrence($modelFile['location'],  $modelFile['location_new']);
            $return[] = [
                'file'=> $modelFile['location'] ,
                'changed'=>  $changed,
                'exist'=>  true ,
                'baseClass'=>$baseClass
            ] ;
            unlink($modelFile['location_new']);
        }
        return $return ;       
    }
    
    public function generateModel($table , $file)
    {
       
        $model = $this->getModelFromTable($table) ;
        
        
        $data['className'] = $model ;
        $data['tableName'] = $table ;
        
        foreach($this->getModelsFiles($model) as $modelFile)
        {
            if($modelFile['location'] !=$file)
            {
                continue ;
            }
            
            $temp = view($modelFile['template'] , $data );
            //dd($modelFile);
            if(isset($modelFile['isBaseModel']) &&  $modelFile['isBaseModel'] === true )
            {
                $timeStamp = $this->getTableTimeStamp($table);
                $relations = $this->getTableRelations($table);
                $default = $this->getTableDefault($table);
                $rules = $this->getTableRules($table);
                $comment = $this->getTableComment($table);
                
                $temp = str_replace('//timestamp' , $timeStamp , $temp);
                $temp = str_replace('//Rules' , $rules , $temp);
                $temp = str_replace('//Default' , $default , $temp);
                $temp = str_replace('//Comments' , $comment , $temp);
                $temp = str_replace('//Relations' , $relations , $temp);
            }
            file_put_contents($modelFile['location'], $temp) ;
        }
            
    }
    
    
    /* Table Schema */
    private function getTableRelations($table)
    {
        $database = \Config::get('database.connections.mysql.database') ;
        
        $query = "SELECT  table_name,  column_name ,  referenced_table_name,  referenced_column_name 
            FROM INFORMATION_SCHEMA.key_column_usage 
            WHERE referenced_table_schema = '".$database."' 
              AND referenced_table_name IS NOT NULL 
              and ( referenced_table_name = '".$table."' or table_name='".$table."' )
            ORDER BY table_name, column_name ";
        
        $relations = \DB::select($query);
        $return = '//=========Relations===============
        ';
        
        foreach($relations as $relation)
        {
            $referenced = '';
            if($relation->referenced_column_name !='id')
                $referenced = ' , \''.$relation->referenced_column_name.'\' ';
                
            if($relation->table_name  == $table) // belong to 
            {
                
                $model = $this->getModelFromTable($relation->referenced_table_name) ;
                
                $return .= '
    public function '.camel_case(str_singular($relation->referenced_table_name)).'()
    {
        return $this->belongsTo(\'\App\Models\\'.$model.'\', \''.$relation->column_name.'\''.$referenced.' );
    }
    ';
            }
            else //one to many
            {
                $model = $this->getModelFromTable($relation->table_name) ;
                
                $return .= '
    public function '.camel_case(str_plural(str_replace($table , '' , $relation->table_name))).'()
    {
        return $this->hasMany(\'\App\Models\\'.$model.'\', \''.$relation->column_name.'\''.$referenced.' );
    }
    ';
                
            }
        }
        
        return $return ;
        
    }
    
    private function getTableDefault($table)
    {
        $query = "SHOW COLUMNS FROM ".$table." ";
        $columns = \DB::select($query);
        $hasDefault = FALSE ;
        
        $return = '//=========Default Values===============
    public $attributes=[';
        foreach($columns as $column)
        {
            
            if($column->Default != '')
            {
                $hasDefault = TRUE ;
                $return .= '
        \''.$column->Field.'\'=>\''.$column->Default.'\',';
            }
                
           
        }
        
      
        $return.='
    ];'; 
        if($hasDefault)
            return $return ;
        else
            return '' ;
            
    }
    private function getTableRules($table)
    {
        $query = "SHOW COLUMNS FROM ".$table." ";
        
        $columns = \DB::select($query);
        $return = '//=========Rules===============
    public $rules=[';
      
        foreach($columns as $column)
        {
            if($column->Key == 'PRI') continue;
            preg_match('/(\w+)\(([\d,]+)\)|(\w+)/' , $column->Type, $matches) ;
            $rules = [];
            
            if(!isset($matches[1]))continue ;
            if(isset($matches[3]) && $matches[1]=='') $matches[1] = $matches[3] ;
            
            if(in_array($column->Field, ['created_at' , 'updated_at' ]) )
				continue ;
            
            // Get The Rules 
            if($column->Null == 'NO') 
                $rules[] = 'required' ;
			else
                $rules[] = 'nullable' ;
            
            
            if($matches[1] == 'tinyint' && @$matches[2]=='1') 
                $rules[] = 'boolean' ;
            
            if($matches[1] == 'date' ) 
                $rules[] = 'date' ;
            
            if($matches[1] == 'int' ) 
                $rules[] = 'integer' ;
            
            //if($matches[1] == 'varchar' && @$matches[2]>0) 
              //  $rules[] = 'size:'.$matches[2] ;
            
            if($column->Key == 'UNI') 
                $rules[] = 'unique:'.$table ;
                
            if($column->Field == 'email')
                $rules[] = 'email' ;
                
            if(in_array($column->Field, ['image' , 'img' , 'photo']) )
                $rules[] = 'image' ;
                
                
            
            //-------------------------------
            
            
            if(!empty($rules))
            $return .= '
        \''.$column->Field.'\'=>\''.implode('|',$rules).'\',';
           
        }

        $return.='
    ];'; 
        
//        echo '<pre>';
//        echo $return ;
//        die ;
        
        return $return ;
        
    }
    
    private function getTableComment($table)
    {
        $query = "SHOW COLUMNS FROM ".$table." ";
        
        $columns = \DB::select($query);
        $return = '/**
 * This is the model class for table "'.$table.'".
 *
';
        foreach($columns as $column)
        {
            preg_match('/(\w+)\(([\d,]+)\)|(\w+)/' , $column->Type, $matches) ;
            
            
            if(isset($matches[1]) && ($matches[1] == 'int' || $matches[1] == 'tinyint' ) )
                $type = 'integer' ;
            else
                $type = 'string' ;
            $return.=' * @property '.$type.' $'.$column->Field.'
';
        }
        
        $return.='*
 */' ;
        
        return $return ;        
    }
    
	private function getTableTimeStamp($table){
		$return = '';
		$query = "SHOW COLUMNS FROM ".$table." ";
        $columns = \DB::select($query);
		$columns = array_map(function($v){
			return $v->Field ;
		} , $columns);
		
		if(!in_array('created_at' , $columns) || !in_array('updated_at' , $columns) )
		{
			$return .= 'public $timestamps = false;
	' ;
		}
		
		if(in_array('deleted_at' , $columns))
		{
			$return .= 'use \Illuminate\Database\Eloquent\SoftDeletes;' ;
		}
		
		return $return ;
	}
    
    /* Helpers */
    private function getModelFromTable($table)
    {
        return ucfirst( camel_case($table) );   
    }
    
    private function getModelDir()
    {
        return app_path().'/Models' ;
    }
    
    private function getModelsFiles($model='')
    {
        $modelDir = $this->getModelDir() ;
        if(isset($_GET['create_base']) || $model=='')
        $return[] = [
            'template'=>'laraCRUD::templates.base.BaseModel',
            'isBaseClass'=>true,
            'location'=>$modelDir.'/Base/BaseModel.php',
            'location_new'=>$modelDir.'/Base/BaseModel_new.php',
            'location_diff'=>$modelDir.'/Base/BaseModel_diff.php',
        ];
        if($model!='')
        {
            $return[] = [
                'template'=>'laraCRUD::templates.model',
                'location'=>$modelDir.'/'.$model.'.php',
                'location_new'=>$modelDir.'/'.$model.'_new.php',
                'location_diff'=>$modelDir.'/'.$model.'_diff.php',
            ];
            $return[] = [
                'template'=>'laraCRUD::templates.base.model',
                'isBaseModel'=>true ,
                'location'=>$modelDir.'/Base/'.$model.'.php',
                'location_new'=>$modelDir.'/Base/'.$model.'_new.php',
                'location_diff'=>$modelDir.'/Base/'.$model.'_diff.php',
            ];
        }
        
        return $return ;
    }
    
    private function checkDiffrence($location , $location_new)
    {
        $oldFile = file_get_contents($location) ;
        $newFile = file_get_contents($location_new) ;
       
        if($oldFile==$newFile)
            return false;
        else
            return true;            
    }
    
    private function createFolders()
    {
        $modelDir = $this->getModelDir() ;
        if(!is_dir($modelDir))
            mkdir($modelDir);

        if(!is_dir($modelDir.'/Base'))
            mkdir($modelDir.'/Base');
    }
    
    public function checkBaseModelFile()
    {
        $model = $this->getModelsFiles();
        return file_exists($model[0]['location'])  ;
    }
}