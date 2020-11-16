<?php

App::uses('FileControlValidator', 'Model');

class FileControlValidatorTest extends CakeTestCase
{

    public $records = array();

    public function filePath()
    {
        $untrimarray1 = array();
        $untrimarray2 = array();

        $untrimarray1 = file("../Test/Case/Model/inputs/sample.txt");
        $untrimarray2 = file("../Test/Case/Model/inputs/invalidsample.txt");

        $this->records['validrecords'] = array_map('trim', $untrimarray1);
        $this->records['invalidrecords'] = array_map('trim', $untrimarray2);
    }

    /**
     * Setup for Test
     *
     */

    public function setup()
    {
        parent::setUp();
        $this->filePath();
        $this->fileControlGoodCase = new FileControlValidator($this->records['validrecords']);
        $this->fileControlBadCase = new FileControlValidator($this->records['invalidrecords']);
    }
    /**
     * Validates File Control as a whole
     * @returns array list of empty errors
     * Assert an empty array
     *
     */
    public function testValidateGoodCase()
    {
        $this->fileControlGoodCase->validate();
        $this->assertEmpty($this->fileControlGoodCase->errors);
    }

    /**
     * Validates ACH as a whole
     * @returns array list of errors
     * Assert Not empty array
     *
     */
    public function testValidateBadCase()
    {
        $this->fileControlBadCase->validate();
        $this->assertNotEmpty($this->fileControlBadCase->errors);
    }

    /**
     * validateControlBatchCountGoodCase
     * input records with valid Batch
     * assert empty array
     *
     */
    public function testValidateControlBatchCountGoodCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlBatchCount');
        $method->setAccessible(true);
        $method->invoke($this->fileControlGoodCase);
        $this->assertEmpty($this->fileControlGoodCase->errors);
    }


    /**
     * validateControlBatchCountBadCase
     * input records with invalid Batch
     * assert not empty array
     */
    public function testValidateControlBatchCountBadCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlBatchCount');
        $method->setAccessible(true);
        $method->invoke($this->fileControlBadCase);
        $this->assertNotEmpty($this->fileControlBadCase->errors);
        $this->assertEquals('25', $this->fileControlBadCase->batchCount);
    }

    /**
     * validateControlBlockCountGoodCase
     * input records with valid control batch
     * assert empty array
     *
     */
    public function testValidateControlBlockCountGoodCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlBlockCount');
        $method->setAccessible(true);
        $method->invoke($this->fileControlGoodCase);
        $this->assertEmpty($this->fileControlGoodCase->errors);
    }

    /**
     * validateControlBlockCountBadCase
     * input records with invalid control block
     * assert not empty array
     *
     */
    public function testValidateControlBlockCountBadCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlBlockCount');
        $method->setAccessible(true);
        $method->invoke($this->fileControlBadCase);
        $this->assertNotEmpty($this->fileControlBadCase->errors);
        $this->assertEquals('6', $this->fileControlBadCase->blockCount);
    }

    /**
     * validateControlEntryCountGoodCase
     * input records with valid Entry Count
     * assert empty array
     *
     */
    public function testValidateControlEntryCountGoodCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlEntryCount');
        $method->setAccessible(true);
        $method->invoke($this->fileControlGoodCase);
        $this->assertEmpty($this->fileControlGoodCase->errors);
    }

    /**
     * validateControlEntryCountBadCase
     * input records with invalid Entry count
     * assert not empty array
     */
    public function testValidateControlEntryCountBadCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlEntryCount');
        $method->setAccessible(true);
        $method->invoke($this->fileControlBadCase);
        $this->assertNotEmpty($this->fileControlBadCase->errors);
        $this->assertEquals('24', $this->fileControlBadCase->entryCount);
    }

    /**
     * validateControlEntryHashGoodCase
     * input records with valid entry hash
     * assert empty array
     */
    public function testValidateControlEntryHashGoodCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlEntryHash');
        $method->setAccessible(true);
        $method->invoke($this->fileControlGoodCase);
        $this->assertEmpty($this->fileControlGoodCase->errors);
    }

    /**
     * validateControlEntryHashBadCase
     * input records with invalid entry hash
     * assert not empty array
     *
     */
    public function testValidateControlEntryHashBadCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlEntryHash');
        $method->setAccessible(true);
        $method->invoke($this->fileControlBadCase);
        $this->assertNotEmpty($this->fileControlBadCase->errors);
        $this->assertEquals('209232767', $this->fileControlBadCase->entryHash);
    }

    /**
     * validateControlDebitAmountGoodCase
     * input records with valid debit amount
     * assert empty array
     *
     */
    public function testValidateControlDebitAmountGoodCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlDebitAmount');
        $method->setAccessible(true);
        $method->invoke($this->fileControlGoodCase);
        $this->assertEmpty($this->fileControlGoodCase->errors);
    }

    /**
     * validateControlDebitAmountBadCase
     * input records with invalid Debit Amount
     * assert not empty array
     *
     */
    public function testValidateControlDebitAmountBadCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlDebitAmount');
        $method->setAccessible(true);
        $method->invoke($this->fileControlBadCase);
        $this->assertNotEmpty($this->fileControlBadCase->errors);
        $this->assertEquals('8889453', $this->fileControlBadCase->debitAmount);
    }

    /**
     * validateControlCreditAmountGoodCase
     * input records with valid Credit Amount
     * assert empty array
     *
     */
    public function testValidateControlCreditAmountGoodCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlCreditAmount');
        $method->setAccessible(true);
        $method->invoke($this->fileControlGoodCase);
        $this->assertEmpty($this->fileControlGoodCase->errors);
    }

    /**
     * validateControlCreditAmountBadCase
     * input records with invalid Credit Amount
     * assert Not empty array
     *
     */
    public function testValidateControlCreditAmountBadCase()
    {
        $method = new ReflectionMethod('FileControlValidator', 'validateControlCreditAmount');
        $method->setAccessible(true);
        $method->invoke($this->fileControlBadCase);
        $this->assertNotEmpty($this->fileControlBadCase->errors);
        $this->assertEquals('49421', $this->fileControlBadCase->creditAmount);
    }
}
