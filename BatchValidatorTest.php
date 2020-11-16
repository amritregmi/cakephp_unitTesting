<?php

app::uses('BatchValidator', 'Model');

/**
 * Class BatchValidatorTest
 */
class BatchValidatorTest extends CakeTestCase {

    /**
     * @var
     */
    public $batchObj;

    /**
     * @var array
     */
    public $records = array(
        '5220BEVELLED COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001',
        '5225BEVELLED COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001',
        '5225BEVELLED COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001',
        '822500000100082001240000001013180000000000006323642DAE                         122039360000001',
        '5200VIOMYCIN RECOVER4041234567          6323642DAEWEBARC TRANSA130722130722   1122039360000002'
    );

    /**
     * @var array
     */
    public $recordsBadCases = array(
        '125BEVELLED COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001',
        '6125BEVELLED COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001',
        '9325BEVELLED COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001',
        '642500000100082001240000001013180000000000006323642DAE                         122039360000001',
        '9525VIOMYCIN RECOVER4041234567          6323642DAEWEBARC TRANSA130722130722   1122039360000002'
    );

    /**
     * @var array
     */
    public $recordsWithControlEntryClass = array(
        '125BE       COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001',
        '6125B      D COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001'
    );

    /**
     * @var array
     */
    public $recordsWithControlHash = array(
        '822500000100000000000000001013180000000000006323642DAE                         122039360000001',
        '82250000010          000001013180000000000006323642DAE                         122039360000001',
        '82250000010ABCDEFGHIK000001013180000000000006323642DAE                         122039360000001'
    );

    /**
     * @var array
     */
    public $recordsWithDebitAmount = array(
        '822500000100082001240000000000000000000000006323642DAE                         122039360000001',
        '822500000100082001240ACDEFGHIJKLR000000000006323642DAE                         122039360000001'
    );

    /**
     * @var array
     */
    public $recordsWithCreditAmount = array(
        '822500000100082001240000001013180............323642DAE                         122039360000001',
        '822500000100082001240000001013180ABCDEFGHJKLM323642DAE                         122039360000001'
    );

    /**
     * @var array
     */
    public $recordsWithCompanyIdenti = array(
        '822500000100082001240000001013180000000000006000000000                         122039360000001',
        '822500000100082001240000001013180000000000006(@#$%^&*(                         122039360000001'
    );

    /**
     * @var array
     */
    public $emptyRecords = array();

    /**
     *
     */
    public function setUp() {
        parent::setUp();
        $this->batchObj = new BatchValidator($this->records);
    }

    /**
     * The Batch validator with empty errors.
     */
    public function testConstructGoodCase() {
        $this->assertEqual(array(), $this->batchObj->errors);
    }

    /**
     * The Batch validator with Empty Records Found Message.
     */
    public function testConstructBadCase() {
        $this->batchObj = new BatchValidator($this->emptyRecords);
        $this->assertEqual('Empty Records Found', $this->batchObj->errors);
    }

    /**
     * The records starting with 5 as Batch Header and 8 as Batch Control in Record Type Code.
     */
    public function testValidateGoodCase() {
        $method = new ReflectionMethod('BatchValidator', '__validate');
        $method->setAccessible(true);
        $result = $method->invoke($this->batchObj, $this->records);
        $this->assertEqual(array(), $result);
    }

    /**
     * The records starting with 1, 6, and 9 as Record Type Code.
     */
    public function testValidateBadCase() {
        $method = new ReflectionMethod('BatchValidator', '__validate');
        $method->setAccessible(true);
        $this->batchObj = new BatchValidator($this->recordsBadCases);
        $result = $method->invoke($this->batchObj, $this->recordsBadCases);
        $this->assertEqual(
                'Record type for a Batch Must be either 5 or 8', $result
        );
    }

    /**
     * Service Class code contains 200, 220 and 225.
     */
    public function testValidateHeaderServiceClassCodeGoodCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateServiceClassCode'
        );
        $method->setAccessible(true);
        $result = $method->invoke($this->batchObj, $this->records);
        $this->assertEqual(array(), $result);
    }

    /**
     * Service class contains except 200, 220 and 225.
     */
    public function testValidateHeaderServiceClassCodeBadCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateServiceClassCode'
        );
        $method->setAccessible(true);
        $this->batchObj = new BatchValidator($this->recordsBadCases);
        $result = $method->invoke($this->batchObj, $this->recordsBadCases);
        $this->assertEqual('Invalid Service Code', $result);
    }

    /**
     * Validate total number of entry detail in ach file ie. number of line with
     * record type 6.
     */
    public function testValidateControlEntryCountGoodCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateControlEntryCount'
        );
        $method->setAccessible(true);
        $result = $method->invoke($this->batchObj, $this->records);
        $this->assertEqual(array(), $result);
    }

    /**
     * Bad case for Control Entry count i.e. number of line with record type 6.
     */
    public function testValidateControlEntryCountBadCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateControlEntryCount'
        );
        $method->setAccessible(true);
        $batchBadControlEntry = new BatchValidator(
                $this->recordsWithControlEntryClass
        );
        $method->invoke(
                $batchBadControlEntry, $this->recordsWithControlEntryClass
        );
        $this->assertNotEmpty($batchBadControlEntry->errors);
    }

    /**
     *  Validate sum of all Entry Hash fields in the ACH file with record type 6
     * i.e. Entry Detail . If the total contains more digits than the field size allows.
     * Only use the final 10 positions in the entry.
     */
    public function testValidateControlEntryHashGoodCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateControlEntryHash'
        );
        $method->setAccessible(true);
        $method->invoke($this->batchObj, $this->records);
        $this->assertEmpty($this->batchObj->errors);
    }

    /**
     * Bad case of the Entry hash fields in the ACH file with record type 6.
     *  Entry hash with zero records.
     */
    public function testValidateControlEntryHashBadCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateControlEntryHash'
        );
        $method->setAccessible(true);
        $batchBadEntryHash = new BatchValidator($this->recordsWithControlHash);
        $method->invoke($batchBadEntryHash, $this->recordsWithControlHash);
        $this->assertNotEmpty($batchBadEntryHash->errors);
    }

    /**
     * Validate entry detail amount for debits.
     * A batch record with entry total debit entry dollar amount more than 1.
     */
    public function testValidateControlDebitAmountGoodCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateControlDebitAmount'
        );
        $method->setAccessible(true);
        $method->invoke($this->batchObj, $this->records);
        $this->assertEmpty($this->batchObj->errors);
    }

    /**
     * Bad Case for entry detail amount for debits.
     * A batch record with total debit entry dollar amount equal to 0.
     * A batch record with empty total debit entry dollar amount.
     * debit amount must be zero.
     */
    public function testValidateControlDebitAmountBadCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateControlDebitAmount'
        );
        $method->setAccessible(true);
        $batchBadDebitAmount = new BatchValidator(
                $this->recordsWithDebitAmount
        );
        $method->invoke($batchBadDebitAmount, $this->recordsWithDebitAmount);
        $this->assertNotEmpty($batchBadDebitAmount->errors);
    }

    /**
     * Validate entry detail amount for credits.
     * A batch record with entry total credit entry dollar amount  more than 1.
     */
    public function testValidateControlCreditAmountGoodCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateControlCreditAmount'
        );
        $method->setAccessible(true);
        $method->invoke($this->batchObj, $this->records);
        $this->assertEmpty($this->batchObj->errors);
    }

    /**
     * validate entry detail amount for credits.
     * A batch record with total credit dollar amount equal to zero.
     * A batch record with empty total credit entry dollar amount.
     */
    public function testValidateControlCreditAmountBadCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateControlCreditAmount'
        );
        $method->setAccessible(true);
        $batchBadCreditAmount = new BatchValidator(
                $this->recordsWithCreditAmount
        );
        $method->invoke($batchBadCreditAmount, $this->recordsWithCreditAmount);
        $this->assertNotEmpty($batchBadCreditAmount->errors);
    }

    /**
     * Validate company identification match.
     *  A batch record with valid company identification.
     */
    public function testValidateCompanyIdentificationMatchGoodCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateCompanyIdentificationMatch'
        );
        $method->setAccessible(true);
        $method->invoke($this->batchObj, $this->records);
        $this->assertEmpty($this->batchObj->errors);
    }

    /**
     * validate company identification match.
     * A batch record with empty company identification.
     */
    public function testValidateCompanyIdentificationMatchBadCase() {
        $method = new ReflectionMethod(
                'BatchValidator', '__validateCompanyIdentificationMatch'
        );
        $method->setAccessible(true);
        $batchBadCompanyIdenti = new BatchValidator(
                $this->recordsWithCompanyIdenti
        );
        $method->invoke($batchBadCompanyIdenti, $this->recordsWithCompanyIdenti);
        $this->assertNotEmpty($batchBadCompanyIdenti->errors);
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

}
