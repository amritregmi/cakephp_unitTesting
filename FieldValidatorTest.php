<?php

App::uses('FieldValidator', 'Model');

class FieldValidatorTest extends CakeTestCase
{

    /**
     * create the object for FieldValidator.
     */
    public $fieldObject;

    public function setUp()
    {
        parent::setUp();
        $this->fieldObject = new FieldValidator();
    }

    /**
     * Validate as defined by NACHA:
     * Zero filled
     * digits containing 0-9.
     */
    public function testIsNumericGoodCases() {
        $numericGoodCases = array('123456', '000000000');

        foreach ($numericGoodCases as $numericGoodCase) {
            $result = $this->fieldObject->isNumeric($numericGoodCase);
            $this->assertEquals(true, $result);
        }
    }

    /**
     * Validate as defined by NACHA:
     * Negative signed.
     * space filled.
     * Alphabet.
     * Decimal.
     * Dollar sign.
     */
    public function testIsNumericBadCases() {
        $numericBadCases = array(
            '-055555', '0000-555', '500 500', 'Abcedf', '$50000', '456.7'
        );

        foreach ($numericBadCases as $numericBadCase) {
            $result = $this->fieldObject->isNumeric($numericBadCase);
            $this->assertEquals(false, $result);
        }
    }

    /**
     * Validate as defined by NACHA:
     * Space filled.
     * Both Digits and UPPER CASE.
     * Zero filled.
     * Digits only.
     * UPPER CASE letters only.
     */
    public function testIsAlphamericGoodCases() {
        $alphmericGoodCases = array(
            'ASDFGH123', 'ASFGH1234', '1234567', '555 abcdf', ' 89899999',
            '0000000000', 'ABCDEFGH'
        );

        foreach ($alphmericGoodCases as $alphmericGoodCase) {
            $result = $this->fieldObject->isAlphameric($alphmericGoodCase);
            $this->assertEquals(true, $result);
        }
    }

    /**
     * Validate as defined by NACHA:
     * special characters.
     * empty field.
     */
    public function testIsAlphamericBadCases() {
        $alphmericBadCases = array('#$&%^@-', '');

        foreach ($alphmericBadCases as $alphmericBadCase) {
            $result = $this->fieldObject->isAlphameric($alphmericBadCase);
            $this->assertEquals(false, $result);
        }
    }

    /**
     * Validate as defined by NACHA:
     *  blank field.
     */
    public function testIsBlankGoodCase() {
        $result = $this->fieldObject->isBlank('');
        $this->assertEquals(true, $result);
    }

    /**
     * Validate as defined by NACHA.
     * integer field.
     * decimal field.
     *  letters field.
     */
    public function testIsBlankBadCases() {
        $isBlankBadCases = array('12234', 'Hhhhddd', '45.67');

        foreach ($isBlankBadCases as $isBlankBadCase) {
            $result = $this->fieldObject->isBlank($isBlankBadCase);
            $this->assertEquals(false, $result);
        }
    }

    /**
     * validate as defined by NACHA.
     * The date is expressed in a "YYMMDDD" format.
     * The date you created the input file.
     */
    public function testIsYYMMDDGoodCase() {
        $isYYMMDDGoodCase = '150512';
        $result = $this->fieldObject->isYYMMDD($isYYMMDDGoodCase);
        $this->assertEquals(true, $result);
    }

    /**
     * The date is expressed in  "DDMMYY" and its different format.
     * The MM is greater than 12 and DD is greater than 31.
     */
    public function testIsYYMMDDBadCases() {
        $isYYMMDDBadCases = array('000000', '152323', '041072', '231575');

        foreach ($isYYMMDDBadCases as $isYYMMDDBadCase) {
            $result = $this->fieldObject->isYYMMDD($isYYMMDDBadCase);
            $this->assertEquals(false, $result);
        }
    }

    /**
     * Validate as defined by NACHA:
     * The time is expressed in a "HHMM"(24-clock format).
     * Time of day you created the input file.
     * This field is used to distinguish between input files if you submit more
     * thant one per day.
     */
    public function testIsHHMMDDGoodCase() {
        $isHHMMGoodCase = '2340';
        $result = $this->fieldObject->isHHMM($isHHMMGoodCase);
        $this->assertEquals(true, $result);
    }

    /**
     * The time is express in a "HH:MM" and hours is greater than 23 and minutes
     * is grater than 59.
     */
    public function testIsHHMMDDBadCases() {
        $isHHMMBadCases = array('12:40', '2540', '1363');

        foreach ($isHHMMBadCases as $isHHMMBadCase) {
            $result = $this->fieldObject->isHHMM($isHHMMBadCase);
            $this->assertEquals(false, $result);
        }
    }

    /**
     * Validate as defined by NACHA: Begins with a blank, followed by the four
     * digit Federal Reserve Routing Symbol,
     * the four digit ABA Institution Identifier, and the Check Digit.
     */
    public function testIsbTTTTAAAACGoodCases() {
        $gooodCases = array(' 123477789', ' 041076548');

        foreach ($gooodCases as $goodCase) {
            $result = $this->fieldObject->isbTTTTAAAAC($goodCase);
            $this->assertEquals(true, $result);
        }
    }

    /**
     * Begins with numbers or digits and total length greater than 10.
     */
    public function testIsbTTTTAAAACBadCases() {
        $badCases = array('123456789', '04107654823', 'ABCDEFGH');

        foreach ($badCases as $badCase) {
            $result = $this->fieldObject->isbTTTTAAAAC($badCase);
            $this->assertEquals(false, $result);
        }
    }

    /**
     * Validate as defined by NACHA: the four digit Federal Reserve Routing Symbol,
     * the four digit ABA Institution Identifier.
     */
    public function testIsTTTTAAAAGoodCase() {
        $goodCase = '12345678';
        $result = $this->fieldObject->isTTTTAAAA($goodCase);
        $this->assertEquals(true, $result);
    }

    /**
     *  Greater than 8 digits and no string and white space.
     */
    public function testIsTTTTAAAABadCases() {
        $badCases = array('123456788', ' 12345678', 'ABCDSEFGH');

        foreach ($badCases as $badCase) {
            $result = $this->fieldObject->isTTTTAAAA($badCase);
            $this->assertEquals(false, $result);
        }
    }

    /**
     * Validate as defined by NACHA: space filled, 0-9, A-Z, and UPPER CASE.
     */
    public function test__isUPPERCASEAZNUMERIC09GoodCase() {
        $goodCases = array('1234ABC', 'ABCDFH78', '1234567', '1234 HDDHDD');

        foreach ($goodCases as $goodCase) {
            $result = $this->fieldObject->isUPPERCASEAZNUMERIC09($goodCase);
            $this->assertEquals(true, $result);
        }
    }

    /**
     * Validate as defined by NACHA: zero filled, Special characters and LOWER CASE.
     */
    public function test__isUPPERCASEAZNUMERIC09BadCase() {
        $badCases = array('jhk1234', '$%^&*()', '0000000000');

        foreach ($badCases as $badCase) {
            $result = $this->fieldObject->isUPPERCASEAZNUMERIC09($badCase);
            $this->assertEquals(false, $result);
        }
    }

    /**
     * validate defined by NACHA: Right Justified, unsigned, and 10 positions.
     */
    public function testIsAmountGoodCases() {
        $goodCases = array('1234567890', '0000007897');

        foreach ($goodCases as $goodCase) {
            $result = $this->fieldObject->isAmount($goodCase);
            $this->assertEquals(true, $result);
        }
    }

    /**
     * validate defined by NACHA: zero filled, float numbers, space filled
     * and more than 10 positions.
     */
    public function testIsAmountBadCases() {
        $badCases = array(
            '12345678901', '2540.12345', '12345 5678 99999', '0000000000'
        );

        foreach ($badCases as $badCase) {
            $result = $this->fieldObject->isAmount($badCase);
            $this->assertEquals(false, $result);
        }
    }

    /**
     * Validate as defined by NACHA. Example: Record 1, Field 8 has value of '094'.
     */
    public function testIsStaticStringGoodCase() {
        $actual = '094';
        $records = array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001',
            '627082001247546738417695     00001013185899324        BORATED GAMES         S 0122039360052108',
            '822500000100082001240000001013180000000000006323642DAE                         122039360000001',
            '5225VIOMYCIN RECOVER4041234567          6323642DAEWEBARC TRANSA130722130722   1122039360000002'
        );
        $exceptedValue = $records[0];
        $result = $this->fieldObject->isStaticString($actual, (substr($exceptedValue, 34, 3)));
        $this->assertEquals(true, $result);
    }

    /**
     * Validate as defined by NACHA. Example: Record 1, Field 8 has value of '092'.
     */
    public function testIsStaticStringBadCase() {
        $actual = '092';
        $records = array(
            '101 0214079124013137ZST13072221401094101CAPITAL ONE            VERICHECK INC          00000000',
            '5225BEVELLED COLLEGE4041234567          6323642DAECCDARC TRANSA130722130722   1122039360000001',
            '627082001247546738417695     00001013185899324        BORATED GAMES         S 0122039360052108',
            '822500000100082001240000001013180000000000006323642DAE                         122039360000001',
            '5225VIOMYCIN RECOVER4041234567          6323642DAEWEBARC TRANSA130722130722   1122039360000002'
        );
        $exceptedValue = $records[0];
        $result = $this->fieldObject->isStaticString($actual, (substr($exceptedValue, 34, 3)));
        $this->assertEquals(false, $result);
    }

    /**
     *
     */
    public function tearDown() {
        unset($this->FieldValidator);
        parent::tearDown();
    }

}
