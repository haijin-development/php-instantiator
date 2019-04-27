<?php

use Haijin\Debugger;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\Report\Html\Facade;

$specs->beforeAll(function () {
    $this->coverage = initializeCoverageReport();
});

$specs->afterAll(function () {
    generateCoverageReport($this->coverage);
});

function initializeCoverageReport()
{
    $coverage = new CodeCoverage;
    $coverage->filter()->addDirectoryToWhitelist('src/');
    $coverage->start('specsCoverage');

    return $coverage;
}

;

function generateCoverageReport($coverage)
{
    $coverage->stop();
    $writer = new Facade;
    $writer->process($coverage, 'coverage-report/');
}

;

function inspect($object)
{
    Debugger::inspect($object);
}

class Sample
{
}

class DifferentSample
{
}

class DifferentSample2
{
}

class SampleWithParams
{
    public $p1;
    public $p2;
    public $p3;

    public function __construct($p1, $p2, $p3)
    {
        $this->p1 = $p1;
        $this->p2 = $p2;
        $this->p3 = $p3;
    }
}
