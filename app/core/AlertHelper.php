<?php
/**
 * Alert Helper - Centralized alert and notification system for PHP
 * Handles all types of alerts, flash messages, and API responses
 */
class AlertHelper
{
    /**
     * Show success alert
     */
    public static function success($message, $redirect = null)
    {
        Session::flash('success', $message);
        if ($redirect) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    /**
     * Show error alert
     */
    public static function error($message, $redirect = null)
    {
        Session::flash('error', $message);
        if ($redirect) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    /**
     * Show warning alert
     */
    public static function warning($message, $redirect = null)
    {
        Session::flash('warning', $message);
        if ($redirect) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    /**
     * Show info alert
     */
    public static function info($message, $redirect = null)
    {
        Session::flash('info', $message);
        if ($redirect) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    /**
     * JSON success response
     */
    public static function jsonSuccess($message, $data = [], $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
        exit;
    }

    /**
     * JSON error response
     */
    public static function jsonError($message, $data = [], $statusCode = 400)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'error' => $message,
            'data' => $data
        ]);
        exit;
    }

    /**
     * Handle API response with alert
     */
    public static function handleApiResponse($response, $successMessage = null, $errorMessage = null)
    {
        if ($response['success']) {
            $message = $successMessage ?: $response['message'] ?: 'Operation completed successfully';
            self::jsonSuccess($message, $response['data'] ?? []);
        } else {
            $message = $errorMessage ?: $response['error'] ?: $response['message'] ?: 'An error occurred';
            self::jsonError($message, $response['data'] ?? []);
        }
    }

    /**
     * Validate and show errors
     */
    public static function validate($data, $rules, $messages = [])
    {
        $validator = new Validator($data, $rules);
        $validator->validate();
        
        if ($validator->hasErrors()) {
            $errors = $validator->errors();
            $request = new Request();
            if ($request->isAjax()) {
                self::jsonError('Validation failed', ['errors' => $errors], 422);
            } else {
                Session::flash('errors', $errors);
                return false;
            }
        }
        
        return true;
    }

    /**
     * Show validation errors
     */
    public static function showValidationErrors($errors)
    {
        $request = new Request();
        if ($request->isAjax()) {
            self::jsonError('Validation failed', ['errors' => $errors], 422);
        } else {
            Session::flash('errors', $errors);
        }
    }

    /**
     * Flash message with redirect
     */
    public static function flash($type, $message, $redirect = null)
    {
        Session::flash($type, $message);
        if ($redirect) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    /**
     * Get flash message HTML
     */
    public static function getFlashHtml($type, $message)
    {
        $icons = [
            'success' => 'fas fa-check-circle',
            'error' => 'fas fa-exclamation-triangle',
            'warning' => 'fas fa-exclamation-triangle',
            'info' => 'fas fa-info-circle'
        ];

        $icon = $icons[$type] ?? $icons['info'];
        $alertType = $type === 'error' ? 'danger' : $type;

        return "
            <div class=\"alert alert--{$alertType} alert--dismissible alert--fade show\" role=\"alert\">
                <i class=\"alert__icon {$icon}\"></i>
                <span class=\"alert__content\">" . htmlspecialchars($message) . "</span>
                <button type=\"button\" class=\"alert__close\" data-bs-dismiss=\"alert\" aria-label=\"Close\">
                    <span aria-hidden=\"true\">&times;</span>
                </button>
            </div>
        ";
    }
}
?>
