<?php

namespace Controller;

/**
 * Interface TerminatorInterface
 */
interface TerminatorInterface
{
    /**
     * @param int   $state
     * @param array $juniorBehavior
     *
     * @return mixed
     */
    public function exec($state, array $juniorBehavior);

    /**
     * Returns the worst feedback amount
     *
     * @return int
     */
    public function getHRStatistic();

    /**
     * Returns best feedback amount
     *
     * @return int
     */
    public function getManagerStatistic();
}
