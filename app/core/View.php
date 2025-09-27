<?php
/**
 * View class for rendering templates
 */
class View
{
    private $data = [];
    private $layout = 'app';

    public function render($template, $data = [])
    {
        $this->data = array_merge($this->data, $data);
        
        // Extract data to variables
        extract($this->data);
        
        // Start output buffering
        ob_start();
        
        // Include the template
        $templatePath = APP_PATH . '/app/views/' . $template . '.php';
        if (file_exists($templatePath)) {
            include $templatePath;
        } else {
            throw new Exception("View template '{$template}' not found");
        }
        
        // Get content from buffer or from $content variable
        $bufferContent = ob_get_clean();
        if (empty($bufferContent) && isset($content)) {
            $content = $content;
        } else {
            $content = $bufferContent;
        }
        
        // Include layout if specified
        if ($this->layout) {
            $layoutPath = APP_PATH . '/app/views/layouts/' . $this->layout . '.php';
            if (file_exists($layoutPath)) {
                include $layoutPath;
            } else {
                echo $content;
            }
        } else {
            echo $content;
        }
    }

    public function with($key, $value = null)
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }
        return $this;
    }

    public function layout($layout)
    {
        $this->layout = $layout;
        return $this;
    }

    public function noLayout()
    {
        $this->layout = null;
        return $this;
    }

    public static function make($template, $data = [])
    {
        $view = new self();
        return $view->render($template, $data);
    }
}
