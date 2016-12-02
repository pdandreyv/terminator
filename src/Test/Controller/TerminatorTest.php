<?php

namespace Test\Controler;

use Controller\Terminator;

/**
 * class TerminatorTest
 */
class TerminatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     *  @dataProvider provider
     */
    public function testExec($start, array $actions, $result)
    {
        $trem = new Terminator();

        $this->assertEquals($result, $trem->exec($start, $actions));
    }

    public function testGetHRStatistic()
    {
        $trem = new Terminator();
        $trem->exec(4, [0,0,0,0,0]);

        $this->assertEquals(2, $trem->getHRStatistic());
    }

    public function testGetManagerStatistic()
    {
        $trem = new Terminator();
        $trem->exec(1, [1,1,1,1,1]);

        $this->assertEquals(2, $trem->getManagerStatistic());
    }

    /**
     * Data provider
     *
     * @return array
     */
    public function provider()
    {
        return [
            [4, [1,1,1,1], 4],
            [4, [1,0,0,1], 3],
            [4, [0,0,0,1], 2],
            [4, [0,0,0,0], 1],
            [1, [1,1,1,1], 4],
            [1, [0,0,0,0], 1],
        ];
    }
}
