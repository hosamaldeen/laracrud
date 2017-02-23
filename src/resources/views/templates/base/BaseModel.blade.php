<?php
echo "<?php\n";
?>
namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    public function validate($data=NULL) {
        
        if($data==NULL)
            $data = $this->toArray();
        
        $this->unique_issue();
        
        // make a new validator object
        $v = \Validator::make($data, $this->rules);

        // check for failure
        if ($v->fails()) {
            // set errors and return false
            $this->errors = $v->errors();
            return false;
        }

        // validation pass
        return true;
    }

    public function errors() {
        return $this->errors;
    }
    
    private function unique_issue(){
        // fixing unique issue 
        foreach($this->rules as $key=>$value)
        {
            if(floatval(\Illuminate\Foundation\Application::VERSION) <= 5.3)
                $this->rules[$key] = preg_replace('/unique:([\w,]+)/i', 'unique:$1,'.$this->id, $this->rules[$key]);
            else
            {
                if(strstr($this->rules[$key], 'unique'))
                {
                    $this->rules[$key] = preg_replace('/unique:([\w,]+)/i', '', $this->rules[$key]);
                 //   $this->rules[$key] = preg_replace('/unique:([\w,]+)/i', 'unique:$1,'.$this->id, $this->rules[$key]);
                    $rules = explode('|' , $this->rules[$key] );
                    $rules[] = \Illuminate\Validation\Rule::unique($this->table)->ignore($this->id) ;
                    $this->rules[$key] = $rules ;
                }
            }
                
            
        }
        //dd($this->rules) ;
    }

  
}
?>