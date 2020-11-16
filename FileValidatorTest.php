<?php

App::uses('FileValidator', 'Model');

/**
 * Class FileValidatorTest
 */
class FileValidatorTest extends CakeTestCase
{

    public $records = array(
        'ValidAch' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001',
            '627082001247546738417695     00001013185899324        BORATED GAMES         S 0122039360052108',
            '822500000100082001240000001013180000000000006323642DAE                         122039360000001',
            '5225VIOMYCIN RECOVER4041234567          6323642DAEWEBARC TRANSA130722130722   1122039360000002',
            '637083903726249015288486     000030084030120127       FURIES COLLEGE        S 0122039360052109',
            '822500000100083903720000003008400000000000006323642DAE                         122039360000002',
            '9999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999',
            '9999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999',
            '9999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999'
        ),
        'EmptyAch' => array(),
        'Valid94' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'Invalid94' => array(
            '1101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '55225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'ValidRecord' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'InvalidRecord' => array(
            '01 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'InvalidCreationDate' => array(
            '01 0214079124013137ZST1372221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'ValidRecord1Exist' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'InvalidRecord1Exist' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '125BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'ValidRecord1' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'InvalidRecord1' => array(
            '01 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'ValidMultiple10' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001',
            '3', '4', '5', '6', '7', '8', '9', '1'
        ),
        'InvalidValidMultiple10' => array(
            '01 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'Valid58' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001',
            '627082001247546738417695     00001013185899324        BORATED GAMES         S 0122039360052108',
            '822500000100082001240000001013180000000000006323642DAE                         122039360000001'
        ),
        'InvalidValid58' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001',
            '527082001247546738417695     00001013185899324        BORATED GAMES         S 0122039360052108',
            '8225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000002',
            '822500000100082001240000001013180000000000006323642DAE                         122039360000001'
        ),
        'Valid19' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001',
            '627082001247546738417695     00001013185899324        BORATED GAMES         S 0122039360052108',
            '822500000100082001240000001013180000000000006323642DAE                         122039360000001',
            '9999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999999'
        ),
        'InValid19' => array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'ValidBatchCount' => array(
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        ),
        'InValidBatchCount' => array(
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001',
            '5225BEVELLED COLLEGE4041234567          6323642DAETELARC TRANSA130722130722   1122039360000001'
        )
    );

    /**
     * Setup for Test
     */
    public function setup()
    {
        parent::setUp();
    }

    /**
     * Validates ACH as a whole
     * @returns array list of errors
     * Assert an empty array
     *
     */
    public function testValidateInputFileGoodCase()
    {
        $this->testObj = new FileValidator($this->records['ValidAch']);
        $this->testObj->validate();
        $this->assertEmpty($this->testObj->errors);
    }

    /**
     * Validates ACH as a whole
     * @returns array list of errors
     * Assert an empty array
     *
     */
    public function testValidateInputFileBadCase()
    {
        $this->testObj = new FileValidator($this->records['Invalid94']);
        $this->testObj->validate();
        $this->assertNotEmpty($this->testObj->errors);
    }

    /**
     * validateAllLinesHave94CharsGoodCase
     * input records with valid 94 character.
     * assert empty array
     */
    public function testValidateAllLinesHave94CharsGoodCase()
    {
        $this->testObj = new FileValidator($this->records['Valid94']);
        $method = new ReflectionMethod('FileValidator', 'validateAllLinesHave94Chars');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertEmpty($this->testObj->errors);
    }

    /**
     * validateAllLinesHave94CharsBadCase
     * input records with invalid 94 characters
     * assert nonempty array
     */
    public function testValidateAllLinesHave94CharsBadCase()
    {
        $this->testObj = new FileValidator($this->records['Invalid94']);
        $method = new ReflectionMethod('FileValidator', 'validateAllLinesHave94Chars');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertNotEmpty($this->testObj->errors);
    }

    /**
     * validateAllRecordTypeCodesGoodCase
     * input records with valid ach file
     * assert empty array
     */
    public function testValidateAllRecordTypeCodesGoodCase()
    {
        $this->testObj = new FileValidator($this->records['ValidRecord']);
        $method = new ReflectionMethod('FileValidator', 'validateAllRecordTypeCodes');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertEmpty($this->testObj->errors);
    }

    /**
     * validateAllRecordTypeCodesBadCase
     * input records with valid 94 characters
     * assert Not empty array
     */
    public function testValidateAllRecordTypeCodesBadCase()
    {
        $this->testObj = new FileValidator($this->records['InvalidRecord']);
        $method = new ReflectionMethod('FileValidator', 'validateAllRecordTypeCodes');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertNotEmpty($this->testObj->errors);
    }

    /**
     * validateOnlyRecord1ExistGoodCase
     * input: ACH file with record type 15689
     * input: ACH file with record type 15685689
     * input: ACH file with record type 1566856689
     * assert: output is empty array
     */
    public function testValidateOnlyRecord1ExistGoodCase()
    {
        $this->testObj = new FileValidator($this->records['ValidRecord1Exist']);
        $method = new ReflectionMethod('FileValidator', 'validateOnlyRecord1Exist');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertEmpty($this->testObj->errors);
    }

    /**
     * validateOnlyRecord1ExistBadCase
     * input records with valid record code
     * assert Not empty array
     *
     */
    public function testValidateOnlyRecord1ExistBadCase()
    {
        $this->testObj = new FileValidator($this->records['InvalidRecord1Exist']);
        $method = new ReflectionMethod('FileValidator', 'validateOnlyRecord1Exist');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertNotEmpty($this->testObj->errors);
    }

    /**
     * validateFirstLineRecord1GoodCase
     * input records with first record 1
     * assert empty array
     *
     */
    public function testValidateFirstLineRecord1GoodCase()
    {
        $this->testObj = new FileValidator($this->records['InvalidRecord1Exist']);
        $method = new ReflectionMethod('FileValidator', 'validateFirstLineRecord1');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertEmpty($this->testObj->errors);
    }

    /**
     * validateFirstLineRecord1BadCase
     * input records with first record of other than 1 type
     * assert Notempty array
     *
     */
    public function testValidateFirstLineRecord1BadCase()
    {
        $this->testObj = new FileValidator($this->records['InvalidRecord1']);
        $method = new ReflectionMethod('FileValidator', 'validateFirstLineRecord1');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertNotEmpty($this->testObj->errors);
    }

    /**
     * validateTotalNumberOfLinesEqualMultipleOf10GoodCase
     * input records with valid multiple of 10 records
     * assert empty array
     *
     */
    public function testValidateTotalNumberOfLinesEqualMultipleOf10GoodCase()
    {
        $this->testObj = new FileValidator($this->records['ValidMultiple10']);
        $method = new ReflectionMethod('FileValidator', 'validateTotalNumberOfLinesEqualMultipleOf10');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertEmpty($this->testObj->errors);
    }

    /**
     * validateTotalNumberOfLinesEqualMultipleOf1BadCase
     * input records with invalid multiple of 10 records
     * assert not empty array
     *
     */
    public function testValidateTotalNumberOfLinesEqualMultipleOf10BadCase()
    {
        $this->testObj = new FileValidator($this->records['InvalidValidMultiple10']);
        $method = new ReflectionMethod('FileValidator', 'validateTotalNumberOfLinesEqualMultipleOf10');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertNotEmpty($this->testObj->errors);
    }

    /**
     * validateAllBatchesAreEnclosedWithinRecord5and8GoodCase
     * input records with valid Batches
     * assert empty array
     *
     */
    public function testValidateAllBatchesAreEnclosedWithinRecord5and8GoodCase()
    {
        $this->testObj = new FileValidator($this->records['Valid58']);
        $method = new ReflectionMethod('FileValidator', 'validateAllBatchesAreEnclosedWithinRecord5and8');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertEmpty($this->testObj->errors);
    }

    /**
     * validateAllBatchesAreEnclosedWithinRecord5and8GoodCase
     * input records with valid Batches
     * assert Notempty array
     * *
     */
    public function testValidateAllBatchesAreEnclosedWithinRecord5and8BadCase()
    {
        $this->testObj = new FileValidator($this->records['InvalidValid58']);
        $method = new ReflectionMethod('FileValidator', 'validateAllBatchesAreEnclosedWithinRecord5and8');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertNotEmpty($this->testObj->errors);
    }

    /**
     * validateWholeFilesEnclosedWithinRecord1and9GoodCase
     * input records with valid records
     * assert empty array
     *
     */
    public function testValidateWholeFilesEnclosedWithinRecord1and9GoodCase()
    {
        $this->testObj = new FileValidator($this->records['Valid19']);
        $method = new ReflectionMethod('FileValidator', 'validateWholeFilesEnclosedWithinRecord1and9');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertEmpty($this->testObj->errors);
    }

    /**
     * validateWholeFilesEnclosedWithinRecord1and9GoodCase
     * input records with invalid records
     * assert Notempty array
     *
     */
    public function testValidateWholeFilesEnclosedWithinRecord1and9BadCase()
    {
        $this->testObj = new FileValidator($this->records['InValid19']);
        $method = new ReflectionMethod('FileValidator', 'validateWholeFilesEnclosedWithinRecord1and9');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertNotEmpty($this->testObj->errors);
    }

    /**
     * validateHeaderCreationDateGoodCase
     * input records with valid 94 characters
     * assert empty array
     *
     */
    public function testValidateHeaderCreationDateGoodCase()
    {
        $this->testObj = new FileValidator($this->records['ValidAch']);
        $method = new ReflectionMethod('FileValidator', 'validateHeaderCreationDate');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertEmpty($this->testObj->errors);
    }

    /**
     * validateHeaderCreationDateBadCase
     * input records with valid 94 characters
     * assert empty array
     *
     */
    public function testValidateHeaderCreationDateBadCase()
    {
        $this->testObj = new FileValidator($this->records['InvalidCreationDate']);
        $method = new ReflectionMethod('FileValidator', 'validateHeaderCreationDate');
        $method->setAccessible(true);
        $method->invoke($this->testObj);
        $this->assertNotEmpty($this->testObj->errors);
    }
}
