<?php
/**
 * Validation class for form data
 */
class Validator
{
    private $data;
    private $rules;
    private $errors = [];

    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function validate()
    {
        foreach ($this->rules as $field => $rule) {
            $this->validateField($field, $rule);
        }
        return empty($this->errors);
    }

    private function validateField($field, $rules)
    {
        $rules = explode('|', $rules);
        $value = $this->data[$field] ?? null;

        foreach ($rules as $rule) {
            $this->applyRule($field, $value, $rule);
        }
    }

    private function applyRule($field, $value, $rule)
    {
        $params = explode(':', $rule);
        $ruleName = $params[0];
        $ruleValue = $params[1] ?? null;

        switch ($ruleName) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, 'The ' . $field . ' field is required.');
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, 'The ' . $field . ' field must be a valid email address.');
                }
                break;

            case 'min':
                if (!empty($value) && strlen($value) < $ruleValue) {
                    $this->addError($field, 'The ' . $field . ' field must be at least ' . $ruleValue . ' characters.');
                }
                break;

            case 'max':
                if (!empty($value) && strlen($value) > $ruleValue) {
                    $this->addError($field, 'The ' . $field . ' field must not exceed ' . $ruleValue . ' characters.');
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, 'The ' . $field . ' field must be numeric.');
                }
                break;

            case 'unique':
                if (!empty($value)) {
                    $table = $ruleValue;
                    $db = Database::getInstance();
                    $count = $db->count($table, "{$field} = :value", ['value' => $value]);
                    if ($count > 0) {
                        $this->addError($field, 'The ' . $field . ' field must be unique.');
                    }
                }
                break;

            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if (!isset($this->data[$confirmField]) || $value !== $this->data[$confirmField]) {
                    $this->addError($field, 'The ' . $field . ' field confirmation does not match.');
                }
                break;
        }
    }

    private function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function hasErrors()
    {
        return !empty($this->errors);
    }

    public function getError($field)
    {
        return $this->errors[$field][0] ?? null;
    }
}
