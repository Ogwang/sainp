<?php
/**
 * API setOrientation Unit tests for HTML_Progress_UI class.
 *
 * @version    $Id: HTML_Progress_TestCase_UI_setOrientation.php,v 1.5 2005/08/28 14:12:09 farell Exp $
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_Progress
 * @ignore
 */

class HTML_Progress_TestCase_UI_setOrientation extends PHPUnit_TestCase
{
    /**
     * HTML_Progress instance
     *
     * @var        object
     */
    var $progress;
    var $ui;

    function HTML_Progress_TestCase_UI_setOrientation($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        error_reporting(E_ALL & ~E_NOTICE);

        $logger['push_callback'] = array(&$this, '_pushCallback'); // don't die when an exception is thrown
        $this->progress = new HTML_Progress($logger);
        $this->ui =& $this->progress->getUI();
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
        if (in_array($n, get_class_methods($this->ui))) {
            return true;
        }
        $this->assertTrue(false, 'method '. $name . ' not implemented in ' . get_class($this->ui));
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
     * TestCases for method setOrientation.
     *
     */
    function test_setOrientation_fail_no_integer()
    {
        if (!$this->_methodExists('setOrientation')) {
            return;
        }
        $this->ui->setOrientation('');
        $this->_getResult();
    }

    function test_setOrientation_fail_invalid_value()
    {
        if (!$this->_methodExists('setOrientation')) {
            return;
        }
        $this->ui->setOrientation(0);
        $this->_getResult();
    }

    function test_setOrientation_vertical_valid_width()
    {
        if (!$this->_methodExists('setOrientation')) {
            return;
        }
        $this->ui->setOrientation(HTML_PROGRESS_BAR_VERTICAL);

        $this->assertEquals(24, $this->ui->_progress['progress']['width'],
            'default-size VERTICAL no-border : w=24 h=172.');
    }

    function test_setOrientation_vertical_valid_height()
    {
        if (!$this->_methodExists('setOrientation')) {
            return;
        }
        $this->ui->setOrientation(HTML_PROGRESS_BAR_VERTICAL);

        $this->assertEquals(172, $this->ui->_progress['progress']['height'],
            'default-size VERTICAL no-border : w=24 h=172.');
    }

    function test_setOrientation_vertical_valid_cell_width()
    {
        if (!$this->_methodExists('setOrientation')) {
            return;
        }
        $this->ui->setOrientation(HTML_PROGRESS_BAR_VERTICAL);

        $this->assertEquals(20, $this->ui->_progress['cell']['width'],
            'default-cell-size VERTICAL : w=20 h=15.');
    }

    function test_setOrientation_vertical_valid_cell_height()
    {
        if (!$this->_methodExists('setOrientation')) {
            return;
        }
        $this->ui->setOrientation(HTML_PROGRESS_BAR_VERTICAL);

        $this->assertEquals(15, $this->ui->_progress['cell']['height'],
            'default-cell-size VERTICAL : w=20 h=15.');
    }

    function test_setOrientation_vertical()
    {
        if (!$this->_methodExists('setOrientation')) {
            return;
        }
        $this->ui->setOrientation(HTML_PROGRESS_BAR_VERTICAL);
        $this->_getResult();
    }
}
?>