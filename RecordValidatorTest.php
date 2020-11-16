<?php

app::uses('RecordValidator', 'Model');
app::uses('FileValidator', 'Model');

/**
 * Class RecordValidatorTest
 */
class RecordValidatorTest extends CakeTestCase {

    public $yamlRules;
    public $records = array(
        '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
        '5225BEVELLED COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001',
        '627082001247546738417695     00001013185899324        BORATED GAMES         S 0122039360052108',
        '822500000100082001240000001013180000000000006323642DAE                         122039360000001',
        '5225VIOMYCIN RECOVER4041234567          6323642DAEWEBARC TRANSA130722130722   1122039360000002'
    );
    public $emptyRecords = array();

    /**
     * Create the objects of RecordValidator
     */
    public function setUp() {
        parent::setUp();
        $this->yamlRules = new RecordValidator('../Yaml/ach.yml');
    }

    /**
     * Valid file with YAML Rules.
     */
    public function testConstructorGoodCase() {
        $this->yamlRules = New RecordValidator('../Yaml/ach.yml');
        $this->assertEqual(array(), $this->yamlRules->errors);
    }

    /**
     * Empty file with No YAML rules.
     */
    public function testConstructorBadCase() {
        $this->yamlRules = New RecordValidator('');
        $this->assertEqual('File Does Not Exists', $this->yamlRules->errors);
    }

    /**
     * Record Type Code Starting with File Header Record: 1,
     * Company/Batch Header Record: 5, Entry Detail Record: 6,
     * Company/Batch Control Record: 8, and File Control Record: 9.
     */
    public function testGetRecordTypeCode() {
        $method = $this->getPrivateMethod(
                'RecordValidator', '__getRecordTypeCode'
        );
        $result = $method->invokeArgs($this->yamlRules, $this->records);
        $this->assertEquals(1, $result);
    }

    /**
     * Validates rules
     *
     * @throws Exception if rules are erroneous.
     */
    public function testValidateFieldRulesGoodCase() {
        $method = new ReflectionMethod(
                'RecordValidator', '__validateFieldRules'
        );
        $method->setAccessible(true);
        $yamlGoodRules = Array
            (
            '0' => Array
                (
                'contents' => 1,
                'data_element_name' => 'RECORD TYPE CODE',
                'field_inclusion_requirement' => 'M',
                'position_begin' => 1,
                'position_end' => 1
            ),
            '1' => Array
                (
                'contents' => 'Numeric',
                'data_element_name' => 'PRIORITY CODE',
                'field_inclusion_requirement' => 'R',
                'position_begin' => 2,
                'position_end' => 3
            ),
            '2' => Array
                (
                'contents' => 'bTTTTAAAC',
                'data_element_name' => 'IMMEDIATE DESTINATION',
                'field_inclusion_requirement' => 'M',
                'position_begin' => 4,
                'position_end' => 13
            )
        );
        $method->invoke($this->yamlRules, $yamlGoodRules);
        $this->assertEmpty($this->yamlRules->errors);
    }

    /**
     * Empty Field In the YAML Rules to check the errors.
     */
    public function testValidateFieldRulesBadCase() {
        $method = new ReflectionMethod(
                'RecordValidator', '__validateFieldRules'
        );
        $method->setAccessible(true);
        $yamlBadRules = Array
            (
            '0' => Array
                (
                'contents' => 1,
                'data_element_name' => 'RECORD TYPE CODE',
                'field_inclusion_requirement' => '',
                'position_begin' => 1,
                'position_end' => 1
            ),
            '1' => Array
                (
                'contents' => 'Numeric',
                'data_element_name' => '',
                'field_inclusion_requirement' => '',
                'position_begin' => 2,
                'position_end' => 3
            ),
            '2' => Array
                (
                'contents' => 'bTTTTAAAC',
                'data_element_name' => '',
                'field_inclusion_requirement' => '',
                'position_begin' => 4,
                'position_end' => 13
            )
        );
        $method->invoke($this->yamlRules, $yamlBadRules);
        $this->assertNotEmpty($this->yamlRules->errors);
    }

    /**
     * Validate the fields using the FieldValidator such as Numeric, Alphameric.
     */
    public function testValidateFieldGoodCase() {
        $method = new ReflectionMethod(
                'RecordValidator', '__validateField'
        );
        $method->setAccessible(true);
        $yamlGoodRules = array(
            '0' => Array
                (
                'contents' => 1,
                'data_element_name' => 'RECORD TYPE CODE',
                'field_inclusion_requirement' => 'M',
                'position_begin' => 1,
                'position_end' => 1
            ),
            '1' => Array
                (
                'contents' => 'Numeric',
                'data_element_name' => 'PRIORITY CODE',
                'field_inclusion_requirement' => 'R',
                'position_begin' => 2,
                'position_end' => 3
            ),
            '2' => Array
                (
                'contents' => 'bTTTTAAAC',
                'data_element_name' => 'IMMEDIATE DESTINATION',
                'field_inclusion_requirement' => 'M',
                'position_begin' => 4,
                'position_end' => 13
            ),
            '3' => Array
                (
                'contents' => 'bTTTTAAAAC',
                'data_element_name' => 'IMMEDIATE ORIGIN',
                'field_inclusion_requirement' => 'M',
                'position_begin' => 14,
                'position_end' => 23
            ),
            '4' => Array
                (
                'contents' => 'YYMMDD',
                'data_element_name' => 'FILE CREATION DATE',
                'field_inclusion_requirement' => 'M',
                'position_begin' => 24,
                'position_end' => 29
            ),
            '5' => Array
                (
                'contents' => 'HHMM',
                'data_element_name' => 'FILE CREATION TIME',
                'field_inclusion_requirement' => 'O',
                'position_begin' => 30,
                'position_end' => 33
            ),
            '6' => Array
                (
                'contents' => 'UPPERCASEAZNUMERIC09',
                'data_element_name' => 'FILE ID MODIFIER',
                'field_inclusion_requirement' => 'M',
                'position_begin' => 34,
                'position_end' => 34
            ),
            '7' => Array
                (
                'contents' => '094',
                'data_element_name' => 'Record SIZE',
                'field_inclusion_requirement' => 'M',
                'position_begin' => 35,
                'position_end' => 37
            ),
        );
        $result = $method->invoke($this->yamlRules, $yamlGoodRules);
        $this->assertEqual(array(), $result);
    }

    /**
     * Empty Records so that the object show the errors.
     */
    public function testValidateFieldBadCase() {
        $method = new ReflectionMethod(
                'RecordValidator', '__validateField'
        );
        $method->setAccessible(true);
        $method->invoke($this->yamlRules, $this->emptyRecords);
        $this->assertNotEmpty($this->yamlRules->errors);
    }

    /**
     * Validates the inclusion requirements, contents, and lengths of data fields
     * per NACHA Record format specifications.
     */
    public function testValidateGoodCase() {
        $method = new ReflectionMethod('RecordValidator', '__validate');
        $method->setAccessible(true);
        $result = $method->invoke($this->yamlRules, $this->records);
        $this->assertEqual(array(), $result);
    }

    /**
     * Empty field inclusion requirements, contents and lengths of data fields.
     */
    public function testValidateBadCase() {
        $method = new ReflectionMethod('RecordValidator', '__validate');
        $method->setAccessible(true);
        $method->invoke($this->yamlRules, $this->emptyRecords);
        $this->assertNotEmpty($this->yamlRules->errors);
    }

    /**
     * Test the substring with valid position Begin and Length of the string
     */
    public function testSubStringGoodCase() {
        $this->yamlRules->record = $this->records[0];
        $method = new ReflectionMethod('RecordValidator', '__subString');
        $method->setAccessible(true);
        $result = $method->invoke($this->yamlRules, 4, 13);
        $this->assertEqual(' 021407912', $result);
    }

    /**
     * Test the substring with valid position Begin and Length of the string
     */
    public function testSubStringBadCase() {
        $this->yamlRules->record = $this->records[0];
        $method = new ReflectionMethod('RecordValidator', '__subString');
        $method->setAccessible(true);
        $result = $method->invoke($this->yamlRules, 4, 13);
        $this->assertNotEquals('123021407912', $result);
    }

    /**
     * @param $className
     * @param $methodName
     *
     * @return ReflectionMethod
     */
    public function getPrivateMethod($className, $methodName) {
        $reflector = new ReflectionClass($className);
        $property = $reflector->getMethod($methodName);
        $property->setAccessible(true);

        return $property;
    }

    /**
     * Reset the RecordValidator.
     */
    public function tearDown() {
        unset($this->RecordValidator);
        parent::tearDown();
    }

}
