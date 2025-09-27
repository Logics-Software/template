<?php
/**
 * Response class for handling HTTP responses
 */
class Response
{
    private $headers = [];
    private $statusCode = 200;

    public function json($data, $statusCode = 200)
    {
        $this->statusCode = $statusCode;
        $this->header('Content-Type', 'application/json');
        $this->sendHeaders();
        echo json_encode($data);
        exit;
    }

    public function view($template, $data = [])
    {
        $view = new View();
        $view->render($template, $data);
    }

    public function redirect($url, $statusCode = 302)
    {
        $this->statusCode = $statusCode;
        $this->header('Location', $url);
        $this->sendHeaders();
        exit;
    }

    public function download($file, $filename = null)
    {
        if (!file_exists($file)) {
            $this->json(['error' => 'File not found'], 404);
        }

        $filename = $filename ?: basename($file);
        $this->header('Content-Type', 'application/octet-stream');
        $this->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $this->header('Content-Length', filesize($file));
        $this->sendHeaders();
        readfile($file);
        exit;
    }

    public function header($name, $value)
    {
        $this->headers[$name] = $value;
    }

    private function sendHeaders()
    {
        if (!headers_sent()) {
            http_response_code($this->statusCode);
            foreach ($this->headers as $name => $value) {
                header("{$name}: {$value}");
            }
        }
    }

    public function status($code)
    {
        $this->statusCode = $code;
        return $this;
    }
}
