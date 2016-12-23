<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\MenssajesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\MenssajesTable Test Case
 */
class MenssajesTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \App\Model\Table\MenssajesTable
     */
    public $Menssajes;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.menssajes'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Menssajes') ? [] : ['className' => 'App\Model\Table\MenssajesTable'];
        $this->Menssajes = TableRegistry::get('Menssajes', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Menssajes);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
