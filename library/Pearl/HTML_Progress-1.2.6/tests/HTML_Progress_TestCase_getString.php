<?php
/**
 * API getString Unit tests for HTML_Progress class.
 *
 * @version    $Id: HTML_Progress_TestCase_getString.php,v 1.5 2005/08/28 14:12:09 farell Exp $
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_Progress
 * @ignore
 */

class HTML_Progress_TestCase_getString extends PHPUnit_TestCase
{
    /**
     * HTML_Progress instance
     *
     * @var        object
     */
    var $progress;

    function HTML_Progress_TestCase_getString($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        error_reporting(E_ALL);

        $logger['push_callback'] = array(&$this, '_pushCallback'); // don't die when an exception is thrown
        $this->progress = new HTML_Progress($logger);
    }

    function tearDown()
    {
        unset($this->progress);
    }

    function _stripWhitespace($str)
    {
        return preg_replace('/\\s+/', '', $str);
    }

    function _methodExists($name)
    {
        if (substr(PHP_VERSION,0,1) < '5') {
            $n = strtolower($name);
        } else {
            $n = $name;
        }
        if (in_array($n, get_class_methods($this->progress))) {
            return true;
        }
        $this->assertTrue(false, 'method '. $name . ' not implemented in ' . get_class($this->progress));
        return false;
    }

    function _pushCallback($err)
    {
        // don't die if the error is an exception (as default callback)
        return HTML_PROGRESS_ERRORSTACK_PUSH;
    }

    function _getResult()
    {
        if ($this->progress->hasErrors()) {
            $err = $this->progress->getError();
            $this->assertTrue(false, $err['message']);
        } else {
            $this->assertTrue(true);
        }
    }

    /**
     * TestCases for method getString.
     *
     */
    function test_getString_fail()
    {
        if (!$this->_methodExists('getString')) {
            return;
        }
        $this->progress->setStringPainted(true);
        $this->progress->setString('"Receiving 1 out of 5"');
        $percent = $this->progress->getString();

        $this->assertEquals('"0 %"', $percent);
    }

    function test_getString()
    {
        if (!$this->_methodExists('getString')) {
            return;
        }
        $percent = $this->progress->getString();

        $this->assertEquals("0 %", $percent);
    }
}
?>