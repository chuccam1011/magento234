<?php


namespace Mageplaza\HelloWorld\Plugin;


class MyUtilityPlugin
{
    public function aroundSave(\Mageplaza\HelloWorld\Model\MyUtility $subject, callable $proceed, SomeType $obj, $args)
    {
      //do something
       $proceed($args);

    }
}

