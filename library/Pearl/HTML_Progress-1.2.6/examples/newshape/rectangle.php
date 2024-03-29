<?php
/**
 * Basic Rectangle Progress example.
 *
 * @version    $Id: rectangle.php,v 1.2 2005/07/25 11:19:41 farell Exp $
 * @author     Laurent Laville <pear@laurent-laville.org>
 * @package    HTML_Progress
 * @subpackage Examples
 */

require_once 'HTML/Progress.php';

$bar = new HTML_Progress();
$bar->setAnimSpeed(100);
$bar->setIncrement(10);

$ui =& $bar->getUI();
$ui->setTab('    ');
$ui->setStringAttributes('valign=bottom align=center width=90 height=30');
$ui->setOrientation(HTML_PROGRESS_POLYGONAL);
$ui->setCellAttributes(array(
    'width'  => 15,
    'height' => 15,
    'active-color'   => 'red',
    'inactive-color' => 'orange',
));
$ui->setCellCoordinates(6,4);          // Rectangle 6x4
?>
<html>
<head>
<title>Basic Rectangle ProgressBar example</title>
<style type="text/css">
<!--
<?php echo $bar->getStyle(); ?>

body {
    background-color: #FFFFFF;
    color: #000000;
    font-family: Verdana, Arial;
}

a:visited, a:active, a:link {
    color: navy;
}
// -->
</style>
<script type="text/javascript">
<!--
<?php echo $ui->getScript(); ?>
//-->
</script>
</head>
<body>

<?php
echo $bar->toHtml();
$bar->run();
?>

</body>
</html>