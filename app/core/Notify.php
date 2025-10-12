<?php

/**
 * Unified Notification System - Simple & Effective
 * Replaces complex AlertHelper with 5 simple methods
 */
class Notify
{
    /**
     * Show success notification
     */
    public static function success($message, $redirect = null)
    {
        self::flash('success', $message, $redirect);
    }

    /**
     * Show error notification
     */
    public static function error($message, $redirect = null)
    {
        self::flash('error', $message, $redirect);
    }

    /**
     * Show warning notification
     */
    public static function warning($message, $redirect = null)
    {
        self::flash('warning', $message, $redirect);
    }

    /**
     * Show info notification
     */
    public static function info($message, $redirect = null)
    {
        self::flash('info', $message, $redirect);
    }

    /**
     * Flash message with redirect
     */
    public static function flash($type, $message, $redirect = null)
    {
        Session::flash($type, $message);
        
        if ($redirect) {
            header("Location: " . $redirect);
            exit;
        }
    }

    /**
     * Render flash message HTML
     */
    public static function render()
    {
        $html = '';
        
        // Success message
        $successMessage = Session::getFlash('success');
        if ($successMessage) {
            $html .= self::getNotifyHtml('success', $successMessage);
        }

        // Error message
        $errorMessage = Session::getFlash('error');
        if ($errorMessage) {
            $html .= self::getNotifyHtml('error', $errorMessage);
        }

        // Warning message
        $warningMessage = Session::getFlash('warning');
        if ($warningMessage) {
            $html .= self::getNotifyHtml('warning', $warningMessage);
        }

        // Info message
        $infoMessage = Session::getFlash('info');
        if ($infoMessage) {
            $html .= self::getNotifyHtml('info', $infoMessage);
        }

        // Validation errors
        $validationErrors = Session::getFlash('errors');
        if ($validationErrors) {
            $html .= self::getValidationErrorsHtml($validationErrors);
        }

        return $html;
    }

    /**
     * Get notification HTML
     */
    private static function getNotifyHtml($type, $message)
    {
        $icons = [
            'success' => 'fas fa-check-circle',
            'error' => 'fas fa-exclamation-triangle',
            'warning' => 'fas fa-exclamation-triangle',
            'info' => 'fas fa-info-circle'
        ];

        $icon = $icons[$type] ?? $icons['info'];

        // Determine auto-dismiss duration based on type
        $duration = match($type) {
            'success' => 2000,
            'error' => 4000,
            'warning' => 3000,
            'info' => 3000,
            default => 3000
        };

        return "
        <div class=\"notify notify--{$type} notify-flash\" role=\"alert\" data-auto-dismiss=\"{$duration}\">
            <div class=\"notify__content\">
                <i class=\"notify__icon {$icon}\"></i>
                <span class=\"notify__message\">" . htmlspecialchars($message) . "</span>
            </div>
            <button type=\"button\" class=\"notify__close\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
            </button>
        </div>";
    }

    /**
     * Get validation errors HTML
     */
    private static function getValidationErrorsHtml($errors)
    {
        $html = '<div class="notify notify--error notify-flash" role="alert" data-auto-dismiss="8000">';
        $html .= '<div class="notify__content">';
        $html .= '<i class="notify__icon fas fa-exclamation-triangle"></i>';
        $html .= '<div class="notify__message">';
        $html .= '<ul class="mb-0" style="margin: 0; padding-left: 1rem;">';
        
        foreach ($errors as $field => $fieldErrors) {
            foreach ($fieldErrors as $error) {
                $html .= '<li>' . htmlspecialchars($error) . '</li>';
            }
        }
        
        $html .= '</ul>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '<button type="button" class="notify__close" aria-label="Close">';
        $html .= '<span aria-hidden="true">&times;</span>';
        $html .= '</button>';
        $html .= '</div>';

        return $html;
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
     * Handle API response
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
}
