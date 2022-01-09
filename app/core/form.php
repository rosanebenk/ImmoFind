<?php
class Form
{

    public $controller;

    public function __construct($controller)
    {
        $this->controller = $controller;
    }

    public function input($name, $label, $options = array())
    {
        $error = false;
        $classError = '';
        if(isset($this->errors[$name])){
            $errors = $this->errors[$name];
            $classError = 'error';
        }
        if (!isset($this->controller->request->data->$name)){
            $value = '';
        }else {
            $value = $this->controller->request->data->$name;
        }
        if ($label == 'hidden') {
            return '<input type="hidden" name="' . $name . '" value="' . $value . '"'.' id="'.$name .'" >';
        }
        $html = '<div class="'.$classError.'">
                        <div class="form-group">
                        <label>' . $label . '</label> ';
        $attr = ' ';
        foreach ($options as $k => $v) {
            if ($k != 'type' && $k != 'select') {
                $attr .= "$k =\"$v\"";
            }
        }
        if (!isset($options['type'])) {
            $html .= '<input type="text" id="input_' . $name . '" name="' . $name . '" value="' . $value . '" ' . $attr . '>';
        } elseif ($options['type'] == 'textarea') {
            $html .= '<textarea id="input_' . $name . '" name="' . $name . '" ' . $attr . '>' . $value . '</textarea>';
        }elseif($options['type'] == 'select'){
            $html .= '<select id="input_' . $name . '" name="' . $name . '" > <option value="'. $value .'">--Choisir une option--</option>' ;
            foreach($options as $k=>$v){
                if ($k != 'type') {
                    $html .= '<option>'.$v.' </option>';
                }
            }
            $html .= '</select>';
        }elseif($options['type'] == 'password'){
            $html .= '<input type="password" id="input_' . $name . '" name="' . $name . '" value="' . $value . '" ' . $attr . '>';
        }
        elseif($options['type'] == 'file'){
            $html .= '<input type="file" id="input_' . $name . '" name="' . $name . '" value="' . $value . '" ' . $attr . '>';
        }
        if($error){
            $html .= '<span >'.$errors.'</span>';
        }
        $html .= '</div></div>';
        return $html;
    }
}
