<?php
include("../includes/labdashboardfunctions.php");
$currentyear = $_GET['mwaka'];
$labss = $_GET['lab'];
$startmonth = 1;
$endmonth = 12;
?>
<chart palette='3'  showvalues='0'   SYAxisName='Total Tests' formatNumberScale="0">
    <categories>
        <?php
        $startmonth = 1;
        for ($startmonth; $startmonth <= $endmonth; $startmonth++) {
            $monthname = GetMonthName($startmonth);
            ?>
            <category label="<?php echo $monthname; ?>" />
        <?php }
        ?>
    </categories>
    <dataset seriesName="Negative" showValues="0">
        <?php
        $startmonth = 1;
        for ($startmonth; $startmonth <= $endmonth; $startmonth++) {

            $rejectedsamples = GetRejectedSamplesPerlab($labss, $startmonth, $currentyear);
            $repeatts = GetTestedRepeatSamplesPerlab($labss, $startmonth, $currentyear);
            $failed = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 3, 2);
            $positives1 = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 2, 1); //historical data where no records of repeatss not shown
//$positives2=GetTestedSamplesPerlabByResult($labss,$startmonth,$currentyear,2,2); //final positive after retests
            $totalpositives = $positives1 + $positives2;

            $negatives1 = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 1, 1);
//$negatives2=GetTestedSamplesPerlabByResult($labss,$startmonth,$currentyear,1,2);
            $totalnegatives = $negatives1 + $negatives2;

            $totaltestsinlab = $totalpositives + $totalnegatives + $failed + $repeatts;
            $totaltestswithvalidresults = $totalpositives + $totalnegatives;
            $receivedsamples = $totalpositives + $totalnegatives + $rejectedsamples;
            ?>
            <set value="<?php echo $totalnegatives; ?>" /> <?php } ?>

    </dataset>
    <dataset seriesName="Positive" showValues="0" >
        <?php
        $startmonth = 1;
        for ($startmonth; $startmonth <= $endmonth; $startmonth++) {
            //tested samples overall result positive
            $rejectedsamples = GetRejectedSamplesPerlab($labss, $startmonth, $currentyear);
            $repeatts = GetTestedRepeatSamplesPerlab($labss, $startmonth, $currentyear);
            $failed = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 3, 2);
            $positives1 = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 2, 1); //historical data where no records of repeatss not shown
//$positives2=GetTestedSamplesPerlabByResult($labss,$startmonth,$currentyear,2,2); //final positive after retests
            $totalpositives = $positives1 + $positives2;

            $negatives1 = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 1, 1);
//$negatives2=GetTestedSamplesPerlabByResult($labss,$startmonth,$currentyear,1,2);
            $totalnegatives = $negatives1 + $negatives2;

            $totaltestsinlab = $totalpositives + $totalnegatives + $failed + $repeatts;
            $totaltestswithvalidresults = $totalpositives + $totalnegatives;
            $receivedsamples = $totalpositives + $totalnegatives + $rejectedsamples;
            ?>
            <set value="<?php echo $totalpositives; ?>" /> 
        <?php } ?>

    </dataset>

    <dataset seriesName="Rejected" showValues="0" >
        <?php
        $startmonth = 1;
        for ($startmonth; $startmonth <= $endmonth; $startmonth++) {
            //tested samples overall result positive
            //tested samples overall result positive
            $rejectedsamples = GetRejectedSamplesPerlab($labss, $startmonth, $currentyear);
            $repeatts = GetTestedRepeatSamplesPerlab($labss, $startmonth, $currentyear);
            $failed = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 3, 2);
            $positives1 = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 2, 1); //historical data where no records of repeatss not shown
//$positives2=GetTestedSamplesPerlabByResult($labss,$startmonth,$currentyear,2,2); //final positive after retests
            $totalpositives = $positives1 + $positives2;

            $negatives1 = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 1, 1);
//$negatives2=GetTestedSamplesPerlabByResult($labss,$startmonth,$currentyear,1,2);
            $totalnegatives = $negatives1 + $negatives2;

            $totaltestsinlab = $totalpositives + $totalnegatives + $failed + $repeatts;
            $totaltestswithvalidresults = $totalpositives + $totalnegatives;
            $receivedsamples = $totalpositives + $totalnegatives + $rejectedsamples;
            ?>
            <set value="<?php echo $rejectedsamples; ?>" /> 
        <?php } ?>

    </dataset>
    <dataset seriesName="Total Tests with Valid Results" parentYAxis="S" showValues="1" >
        <?php
        $startmonth = 1;
        for ($startmonth; $startmonth <= $endmonth; $startmonth++) {
            //tested samples overall result positive
            $rejectedsamples = GetRejectedSamplesPerlab($labss, $startmonth, $currentyear);
            $repeatts = GetTestedRepeatSamplesPerlab($labss, $startmonth, $currentyear);
            $failed = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 3, 2);
            $positives1 = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 2, 1); //historical data where no records of repeatss not shown
//$positives2=GetTestedSamplesPerlabByResult($labss,$startmonth,$currentyear,2,2); //final positive after retests
            $totalpositives = $positives1 + $positives2;

            $negatives1 = GetTestedSamplesPerlabByResult($labss, $startmonth, $currentyear, 1, 1);
//$negatives2=GetTestedSamplesPerlabByResult($labss,$startmonth,$currentyear,1,2);
            $totalnegatives = $negatives1 + $negatives2;

            $totaltestsinlab = $totalpositives + $totalnegatives + $failed + $repeatts;
            $totaltestswithvalidresults = $totalpositives + $totalnegatives;
            $receivedsamples = $totalpositives + $totalnegatives + $rejectedsamples;
            ?>
            <set value="<?php echo $totaltestswithvalidresults; ?>" />
        <?php } ?>
    </dataset>
</chart>
