<?php

namespace Test\Icinga\Editor;

/**
 * Basic Configurator class test.
 */
class ConfiguratorTest extends \Test\Ease\BrickTest
{
    /**
     * Data Určená k testům.
     *
     * @var array
     */
    public $testRowA = [
        'class' => 'deleteme',
        'name' => 'MyName1',
        'string' => 'STRING',
        'bool' => '0',
        'date' => '20.5.2015',
        'datetime' => '5-20-2015',
        'text' => 'a"b',
        'int' => '11,4',
        'array2d' => 'a:2:{i:1;s:1:"A";i:2;s:1:"B";}',
        'array3d' => 'a:3:{i:1;s:1:"A";i:2;s:1:"B";i:3;a:2:{i:10;s:1:"X";i:11;s:1:"Y";}}',
    ];

    /**
     * Data Určená k testům.
     *
     * @var array
     */
    public $testRowB = [
        'test_id' => 23,
        'name' => 'MyName2',
        'string' => 'STRING',
        'bool' => '1',
        'date' => '20.5.2015',
        'datetime' => '5-20-2015',
        'text' => 'a"b',
        'int' => '11O',
        'array2d' => ['1' => 'A', '2' => 'B'],
        'array3d' => [1 => 'A', 2 => 'B', 3 => ['10' => 'X', 11 => 'Y']],
    ];

    /**
     * @var IEcfg
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new \Icinga\Editor\Engine\Configurator();

        $this->object->myKeyColumn          = 'test_id';
        $this->object->myTable              = 'test';
        $this->object->myCreateColumn       = 'DatCreate';
        $this->object->myLastModifiedColumn = 'DatSave';
        $this->object->nameColumn           = 'name';

        $this->object->useKeywords = [
            'name' => 'STRING',
            'bool' => 'BOOL',
            'date' => 'DATE',
            'datetime' => 'DATETIME',
            'text' => 'TEXT',
            'int' => 'INT',
            'array' => 'VIRTUAL',
        ];

        $this->object->keywordsInfo = [
            'name' => ['title' => _('Jméno')],
        ];
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {

    }

    /**
     * Převzetí dat.
     *
     * @covers Vitexus/icinga_configurator::takeData
     */
    public function testTakeData()
    {
        $this->object->takeData($this->testRowA);

        $results = $this->object->getData();

        $this->assertEquals('MyName1', $results['name']);
        $this->assertEquals('2015-05-20', $results['date']);
        $this->assertEquals('2015-05-20', $results['datetime']);
        $this->assertEquals('a\"b', $results['text']);
        $this->assertFalse($results['bool']);
        $this->assertEquals(11, $results['int']);
    }

    /**
     * @covers Vitexus/icinga_configurator::doThings
     *
     * @todo   Implement testDoThings().
     */
    public function testDoThings()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Přěvede data do HTML.
     *
     * @covers Vitexus/icinga_configurator::htmlizeData
     */
    public function testHtmlizeData()
    {
        $htmlized = $this->object->htmlizeData(
            [$this->testRowA, $this->testRowB]
        );

        $this->assertArrayHasKey('0', $htmlized);
        $this->assertArrayHasKey('1', $htmlized);
    }

    /**
     * Převede data do CSV.
     *
     * @covers Vitexus/icinga_configurator::csvizeData
     */
    public function testCsvizeData()
    {
        $csvized = $this->object->csvizeData(
            [$this->testRowA, $this->testRowB]
        );
//        $this->assertEquals('A|B', $csvized[0]['array2d']);
//        $this->assertEquals('A|B', $csvized[1]['array2d']);
        $this->assertEquals(2, count($csvized));
    }

    /**
     * Převede řádku dat do CSV.
     *
     * @covers Vitexus/icinga_configurator::csvizeRow
     */
    public function testCsvizeRow()
    {
        $csvized = $this->object->csvizeRow($this->testRowA);
        $this->assertEquals(count($csvized), count($this->testRowA));
    }

    /**
     * Převede řádku dat do HTML.
     *
     * @covers Vitexus/icinga_configurator::htmlizeRow
     */
    public function testHtmlizeRow()
    {
        $htmlizedA = $this->object->htmlizeRow($this->testRowA);

        $this->assertEquals(
            '
<span class="glyphicon glyphicon-unchecked" ></span>
', $htmlizedA['bool']
        );

        $htmlizedB = $this->object->htmlizeRow($this->testRowB);

        $this->assertEquals(
            '
<span class="glyphicon glyphicon-check" ></span>
', $htmlizedB['bool']
        );
    }

    /**
     * Vrací ID záznamu.
     *
     * @covers Vitexus/icinga_configurator::getId
     */
    public function testGetId()
    {
        $this->assertNull($this->object->getId());
        $this->object->setMyKey(23);
        $this->assertEquals(23, $this->object->getId());
    }

    /**
     * Vrací jméno záznamu.
     *
     * @covers Vitexus/icinga_configurator::getName
     */
    public function testGetName()
    {
        $this->object->takeData($this->testRowA);
        $this->assertEquals($this->testRowA['name'], $this->object->getName());
    }

    /**
     * @covers Vitexus/icinga_configurator::delete
     *
     * @todo   Implement testDelete().
     */
    public function testDelete()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Vrací SELECT pro složitější SQL dotazy.
     *
     * @covers Vitexus/icinga_configurator::getListingQuerySelect
     */
    public function testGetListingQuerySelect()
    {
        $lqsel = $this->object->getListingQuerySelect();
        $this->assertEquals('SELECT * FROM `test`', $lqsel);
    }

    /**
     * Vrací WHERE pro složitější SQL dotazy.
     *
     * @covers Vitexus/icinga_configurator::getListingQueryWhere
     */
    public function testGetListingQueryWhere()
    {
        $lqwhere = $this->object->getListingQueryWhere();
        $this->assertEmpty($lqwhere);
    }

    /**
     * Místní nabídka operací se záznamem.
     *
     * @covers Vitexus/icinga_configurator::operationsMenu
     */
    public function testOperationsMenu()
    {
        $this->assertInstanceOf(
            '\Ease\TWB\ButtonDropdown', $this->object->operationsMenu()
        );
    }

    /**
     * Prázdná funkce pro zpracování uploadu.
     *
     * @covers Vitexus/icinga_configurator::handleUpload
     */
    public function testHandleUpload()
    {
        $this->assertEmpty($this->object->handleUpload());
    }

    /**
     * @covers Vitexus/icinga_configurator::searchString
     *
     * @todo   Implement testSearchString().
     */
    public function testSearchString()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * Odstraní z pole "Neznámé" sloupce.
     *
     * @covers Vitexus/icinga_configurator::unsetUnknownColumns
     */
    public function testUnsetUnknownColumns()
    {
        $this->object->takeData($this->testRowA);
        $this->object->unsetUnknownColumns();
        $clean = $this->object->getData();
        $this->assertArrayNotHasKey('class', $clean);
        $this->assertArrayHasKey('name', $clean);
    }

    /**
     * @covers Vitexus/icinga_configurator::getDataFromSQL
     *
     * @todo   Implement testGetDataFromSQL().
     */
    public function testGetDataFromSQL()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Vitexus/icinga_configurator::sqlColumnsToSelect
     */
    public function testSqlColumnsToSelect()
    {
        $columns = $this->object->sqlColumnsToSelect();
        $this->assertEquals('`name`,`test_id`', $columns);
    }

    /**
     * Sql fragment dotazu specifický pro objekt.
     *
     * @covers Vitexus/icinga_configurator::getWhere
     */
    public function testGetWhere()
    {
        $this->assertEmpty($this->object->getWhere());
    }
}