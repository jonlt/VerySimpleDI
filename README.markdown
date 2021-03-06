Very Simple Dependency Injection for PHP
========================================

Injects dependencies into the contructor upon instantiation.
Lets say you have the following classes:

    class A { 
      [...]
    }

    class B { 
      public function __construct(A $a) {
        $this->_a = $a;
      }
      [...]
    }

then you can use VerySimpleDI class like this

    $vsdi = new VerySimpleDI();
    $b = $vsdi->getInstance("B");

This will instantiate a A-object and a B-object using the A object.

By default a new object will be instantiated every time "getInstance" is called

    $b1 = $vsdi->getInstance("B");
    $b2 = $vsdi->getInstance("B");
    var_dump($b1 === $b2); // bool(false)

But if you want **singletons** you can do this:

    $vsdi->register("B", true); // true is for singleton
    $b1 = $vsdi->getInstance("B");
    $b2 = $vsdi->getInstance("B");
    var_dump($b1 === $b2); // bool(true)