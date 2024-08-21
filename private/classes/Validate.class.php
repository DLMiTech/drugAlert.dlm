<?php
trait Validate{
    private function validateName($name): bool
    {
        $pattern = '/^[A-Za-z\- ]+$/';

        if (preg_match($pattern, $name)) {
            return true;
        } else {
            return false;
        }
    }


    private function validatePhoneNumber($number): bool
    {
        $pattern = '/^\d{10,13}$/';

        if (preg_match($pattern, $number)) {
            return true;
        } else {
            return false;
        }
    }

    private function validatePassword($password): bool
    {
        $pattern = '/^.{6,}$/';

        if (preg_match($pattern, $password)) {
            return true;
        } else {
            return false;
        }
    }
}
