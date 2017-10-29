<?php
use PHPUnit\Framework\TestCase;
require_once '../src/File/CSV/Converter/Strategy.php';
require_once '../src/File/CSV/Converter.php';

final class Convertertest extends TestCase{
    public function testConstructorHasStrategyPassedToIt()
    {
        $strategy = new File_CSV_Converter_Strategy_ToHTML();
        $this->assertInstanceOf(
            File_CSV_Converter_Strategy::class,
            $strategy);
        $strategy = new File_CSV_Converter_Strategy_ToJSON();
        $this->assertInstanceOf(
            File_CSV_Converter_Strategy::class,
            $strategy);
        $map = ['name','stars','address','uri'];
        $this->assertType('array', $map);
        $convertor = new File_CSV_Converter($map,$strategy);
    }

}

