<?php

App::uses('AchValidator', 'Model');

class AchValidatorTest extends CakeTestCase
{

    public $testObj;

    public function setup()
    {
        parent::setUp();
    }

    public function testValidAchFileGoodCase()
    {
        $this->achFileGoodSample = new AchValidator('../Test/Case/Model/inputs/sample.txt');
        $method = new ReflectionMethod('AchValidator', 'validateFile');
        $method->setAccessible(true);
        $method->invoke($this->achFileGoodSample);
        $this->assertEmpty($this->achFileGoodSample->errors);

    }

    public function testValidAchFileBadCase()
    {
        $this->achFileBadSample = new AchValidator('../Test/Case/Model/inputs/invalidsample.txt');
        $method = new ReflectionMethod('AchValidator', 'validateFile');
        $method->setAccessible(true);
        $method->invoke($this->achFileBadSample);
        $this->assertNotEmpty($this->achFileBadSample->errors);
    }

    public function testValidAchFileEmptyCase()
    {
        $this->setExpectedException('Exception');
        $this->achFileBadSample = new AchValidator('../Test/Case/Model/inputs/emptysample.txt');
        $method = new ReflectionMethod('AchValidator', 'validateFile');
        $method->setAccessible(true);
        $method->invoke($this->achFileBadSample);
    }
}
