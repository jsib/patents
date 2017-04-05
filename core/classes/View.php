<?php

namespace Core;

class View
{
    /**
     * Store variables which we are going to use in view file
     */
    public $data;
    
    /**
     * Store full html for the view
     */
    public $html = '';
    
    /**
     * Store content for each $block in view. A block constist of content
     * between $this->start($block) and $this->stop($block) constructions.
     */
    public $blocks = [];

    /**
     * Store name of view, which is called by helper view()
     * or method View::load().
     */
    public $view = '';
    
    /**
     * Store name for parent of $this->view,
     * which is set by $this->extend() method.
     */
    public $parentView = '';

    /**
     * If variable $this->name is not found,
     * then take value from $this->data[$name]
     */
    public function __get($name) {
        if (isset($this->data[$name])) return $this->data[$name];
        return "";
    }
    
    /**
     * Process view file and return resulting content
     * 
     * @param string $view View file name, can include path relative
     *    to views root directory
     * 
     * @return string Processed content of view
     */
    public function load($view, $params = [])
    {
        //Save params values to use it in view file
        if (count($params) > 0) {
            $this->data = $params;
        }
        
        //Store the view which we work with
        $this->view = $view;
        
        //Execute child view file and collect blocks of content
        //to $this->blocks property.
        require(VIEWS_PATH.$view.'.html.php');
        
        ob_start();
        
        //Execute parent for $view file and replace collected blocks of content
        require(VIEWS_PATH.$this->parentView.'.html.php');
        
        //Return ready HTML to controller
        return ob_get_clean();
    }
    
    /**
     * Add var to use in view file
     */
    public function setVar($name, $value) {
       $this->data[$name] = $value;
    }
    
    /**
     * Define parent's view file which we extend.
     * This method should be used in the beginning of child's view file.
     */
    public function extend($parent_view)
    {
        //Save parent's buffer to take it in $this->load()
        $this->parentView = $parent_view;
    }
    
    /**
     * Start catching child view buffer.
     * This method should be used in child views before $this->start() method.
     */
    public function start($block)
    {
        ob_start();
    }
    
    /**
     * Stop catching child view buffer and save it.
     * Then start catching parent view buffer.
     * This method should be used in child views after $this->start() method.
     */
    public function stop($block)
    {
        //Catch child view buffer and save it to variable
        $this->blocks[$block] = ob_get_clean();
    }
    
    /**
     * Insert block of content from child view to parent view.
     */
    public function output($block)
    {
        if (!isset($this->blocks[$block])) {
            echo '';
        } else{
            echo $this->blocks[$block];
        }
    }
    
    /**
     * Add css asset link to view file
     */
    public function assetCSS($file)
    {
        echo '<link href="'.\ASSETS_PATH.$file.'" rel="stylesheet">'."\n";
    }
    
    /**
     * Add java script asset link to view file
     */
    public function assetJS($file)
    {
        echo '<script src="'.\ASSETS_PATH.$file.'"></script>'."\n";
    }
}
