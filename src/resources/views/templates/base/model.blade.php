<?php
echo "<?php\n";
?>
namespace App\Models\Base;

//Comments
abstract class {{$className}} extends BaseModel
{
    protected $table = '{{$tableName}}';
	protected $guarded = ['id'];
	//timestamp
	
    //Rules
    
    //Default
    
    //Relations
}

?>