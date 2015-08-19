<?php

class A {

  public $MyProperty;

}

class Test {

  static function Execute() {
    $a = new A();
    $a->MyProperty = 2;
    self::ModifyPropOfObjectA($a);
    echo $a->MyProperty;
  }

  static function ModifyPropOfObjectA(A $a) {
    $a->MyProperty = 3;
  }
}

$test = Test::Execute();
