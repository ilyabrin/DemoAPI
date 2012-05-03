<?php 

class template 
{
    
    var $template_vars;
    
    var $templatesDir;

    function template() 
    { 
        $this->template_vars = array();
    } 

    function assign($var_array) 
    { 
        if (!is_array($var_array)) 
        { 
            die('<b>Ошибка при обработке шаблона.</b>');
        } 
        $this->template_vars = array_merge($this->template_vars, $var_array); 
    } 

    function parse($template_file)  { 
        if (!is_file($template_file))   { 
            die('template::parse() - "' . $template_file . '" does not exist or is not a file.'); 
        }
        
        $template_content = file_get_contents($template_file); 

        foreach ($this->template_vars AS $var => $content)  { 
            $template_content = str_replace('{' . $var . '}', $content, $template_content); // parse values
            }
        
        return $template_content;
    } 

    function render($template_file) 
    { 
        echo $this->parse($template_file); 
    } 
} 
?>