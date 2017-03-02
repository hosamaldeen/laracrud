<?php
echo "<?php\n";
?>
namespace App\Models;

class {{$className}} extends Base\{{$className}}
{
    function __construct(array $attributes = array()) {
        parent::__construct($attributes);
        $this->rules();
    }
    
    function rules()
    {
        $this->rules = array_merge(
            $this->rules,
            [
               
            ]
        );
    }
}

?>