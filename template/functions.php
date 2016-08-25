<?php
    function validatePost($values)
    {
        $errors = array();
        foreach ($values as $value) {
            if (!isset($_POST[$value]) || !is_string($_POST[$value]) || empty($_POST[$value])) {
                $errors[] = $value;
            }
        }

        return $errors;
    }
