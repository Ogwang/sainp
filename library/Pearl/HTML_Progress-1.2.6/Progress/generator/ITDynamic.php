<?php
/**
 * The ActionDisplay class provides a ITDynamic form rendering
 * with template engine IT[x] family.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   HTML
 * @package    HTML_Progress
 * @subpackage Progress_UI
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: ITDynamic.php,v 1.4 2005/07/25 13:00:05 farell Exp $
 * @link       http://pear.php.net/package/HTML_Progress
 */

require_once 'HTML/QuickForm/Renderer/ITDynamic.php';
// can use either HTML_Template_Sigma or HTML_Template_ITX
require_once 'HTML/Template/ITX.php';
//require_once 'HTML/Template/Sigma.php';

/**
 * The ActionDisplay class provides a ITDynamic form rendering
 * with template engine IT[x] family.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   HTML
 * @package    HTML_Progress
 * @subpackage Progress_UI
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @copyright  1997-2005 The PHP Group
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    Release: 1.2.6
 * @link       http://pear.php.net/package/HTML_Progress
 */

class ActionDisplay extends HTML_QuickForm_Action_Display
{
    function _renderForm(&$page)
    {
        $pageName = $page->getAttribute('name');
        $tabPreview = array_slice ($page->controller->_tabs, -2, 1);

        // can use either HTML_Template_Sigma or HTML_Template_ITX
        $tpl =& new HTML_Template_ITX('./templates');
        // $tpl =& new HTML_Template_Sigma('./templates');

        $tpl->loadTemplateFile('itdynamic.html');

        // on preview tab, add progress bar javascript and stylesheet
        if ($pageName == $tabPreview[0][0]) {
            $bar = $page->controller->createProgressBar();

            $tpl->setVariable(array(
                'qf_style'  => $bar->getStyle(),
                'qf_script' => $bar->getScript()
                )
            );

            $barElement =& $page->getElement('progressBar');
            $barElement->setText( $bar->toHtml() );
        }

        $renderer =& new HTML_QuickForm_Renderer_ITDynamic($tpl);
        $renderer->setElementBlock(array(
            'buttons'     => 'qf_buttons'
        ));

        $page->accept($renderer);

        $tpl->show();
    }
}
?>