<?php

namespace Hosamaldeen\LaraCRUD\helpers;

class helper {

    /**
     * match 2 selected options for html select
     * @param string $option1 first option value
     * @param string $option2 second option value
     * @return string  return value selected=selected
     */
    static function selected($option1, $option2) {
        if (is_array($option2)) {
            if (in_array($option1, $option2))
                return "selected='selected'";
        }
        else
        if ($option1 == $option2) {
            return "selected='selected'";
        }
    }
    
    /**
     * match 2 selected options for html select
     * @param string $option1 first option value
     * @param string $option2 second option value
     * @return string  return value checked=checked
     */
    static function checked($option1, $option2) {
        if (is_array($option2)) {
            if (in_array($option1, $option2))
                return "checked='checked'";
        }
        else
        if ($option1 == $option2) {
            return "checked='checked'";
        }
    }

}

