<?php
namespace Tests;

abstract class ValidationTestCase extends TestCase {
    abstract protected function data();
    
    protected function dataWithout($field) {
        $data = $this->data();
        unset($data[$field]);
        return $data;
    }
    
    protected function dataWith($field, $value) {
        $data = $this->data();
        $data[$field] = $value;
        return $data;
    }
}