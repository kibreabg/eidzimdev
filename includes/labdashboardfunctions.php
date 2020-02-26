<?php

require_once('../connection/config.php');
?>
<?php

//get totalnumber of samples received particular year and/or month
function GetReceivedSamplesPerlab($lab, $month, $year) {
    if ($month != "") {

        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND YEAR(samples.datereceived)='$year' AND MONTH(samples.datereceived)='$month' AND ((samples.parentid=0)||(samples.parentid IS NULL)) ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    } else {
        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND YEAR(samples.datereceived)='$year' AND ((samples.parentid=0)||(samples.parentid IS NULL))") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    }
}

//get totalnumber of samples tested particular year and/or month
function GetTestedSamplesPerlab($lab, $month, $year) {
    if ($month != "") {

        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.result>0 AND YEAR(samples.datetested)='$year' AND MONTH(samples.datetested)='$month' AND ((samples.parentid=0)||(samples.parentid IS NULL))") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    } else {
        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.result>0 AND YEAR(samples.datetested)='$year' AND ((samples.parentid=0)||(samples.parentid IS NULL))") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    }
}

function GetTestedpercentage($lab, $month, $year) {

    if (GetTestedSamplesPerlab($lab, $month, $year) == 0) {
        $per = 0;
    } else {
        if (GetReceivedSamplesPerlab($lab, $month, $year) == 0) {
            $per = 0;
        } else {
            $per = round(((GetTestedSamplesPerlab($lab, $month, $year) / GetReceivedSamplesPerlab($lab, $month, $year)) * 100), 2);
        }
    }
    return $per;
}

//get totalnumber of samples tested particular year and/or month based on result type
function GetTestedSamplesPerlabByResult($lab, $month, $year, $resulttype) {
    if ($month != "") {

        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.result='$resulttype' AND samples.repeatt=0 AND YEAR(samples.datetested)='$year' AND MONTH(samples.datetested)='$month' ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    } else {
        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.result='$resulttype' AND samples.repeatt=0 AND YEAR(samples.datetested)='$year'") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    }
}

function GetTestedpercentagePerResult($lab, $month, $year, $resulttype) {

    if (GetTestedSamplesPerlabByResult($lab, $month, $year, $resulttype) == 0) {
        $per = 0;
    } else {
        $per = round(((GetTestedSamplesPerlabByResult($lab, $month, $year, $resulttype) / GetTestedSamplesPerlab($lab, $month, $year)) * 100), 2);
    }
    return $per;
}

//get totalnumber of samples tested particular year and/or month only repeats
function GetTestedRepeatSamplesPerlab($lab, $month, $year) {
    if ($month != "") {

        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND ((samples.repeatt=1) AND ((samples.parentid=0)OR(samples.parentid IS NULL))) AND YEAR(samples.datetested)='$year' AND MONTH(samples.datetested)='$month' ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    } else {
        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.parentid >0 AND YEAR(samples.datetested)='$year'") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    }
}

//get totalnumber of REJECTED SAMPLES FOR particular year and/or month only repeats
function GetRejectedSamplesPerlab($lab, $month, $year) {
    if ($month != "") {

        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.receivedstatus =2 AND YEAR(samples.datereceived)='$year' AND MONTH(samples.datereceived)='$month' ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    } else {
        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.receivedstatus =2 AND YEAR(samples.datereceived)='$year' ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    }
}

//get rejected percentage
function GetRejectedpercentage($lab, $month, $year) {

    if (GetRejectedSamplesPerlab($lab, $month, $year) == 0) {
        $per = 0;
    } else {
        $per = round(((GetRejectedSamplesPerlab($lab, $month, $year) / GetReceivedSamplesPerlab($lab, $month, $year)) * 100), 2);
    }
    return $per;
}

//get turn around time rom collectioln to receipt at lab FOR particular year and/or month only repeats
function GetColletiontoReceivedatLabTAT($lab, $month, $year) {
    $incompletedate = "0000-00-00";
    if ($month != "") {

        $strQuery = mysql_query("select  samples.datecollected,samples.datereceived From samples WHERE samples.lab='$lab' AND  
((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected !='') AND (samples.datecollected !='0000-00-00') )   AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datecollected)='$year' AND MONTH(samples.datereceived)='$month' AND MONTH(samples.datecollected)='$month' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datecollected, $datereceived) = mysql_fetch_array($strQuery)) {

                $sdrec = date("d-m-Y", strtotime($datereceived));
                $sdoc = date("d-m-Y", strtotime($datecollected));
                $workingdays = getTotalWorkingDays($sdoc, $sdrec, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }
            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }


        return array($numsamples, $ave);
    } else {
        $strQuery = mysql_query("select samples.datecollected,samples.datereceived from samples WHERE samples.lab='$lab' AND  
((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected !='') AND (samples.datecollected !='0000-00-00') )   AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datecollected)='$year' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datecollected, $datereceived) = mysql_fetch_array($strQuery)) {

                $sdrec = date("d-m-Y", strtotime($datereceived));
                $sdoc = date("d-m-Y", strtotime($datecollected));
                $workingdays = getTotalWorkingDays($sdoc, $sdrec, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }
            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    }
}

//get turn around time from receipt at lab to dispatch FOR particular year and/or month only repeats
function GetReceivedatLabtoDispatchTAT($lab, $month, $year) {
    $incompletedate = "0000-00-00";
    if ($month != "") {

        $strQuery = mysql_query("select  samples.datereceived,samples.datedispatched from 

samples WHERE samples.lab='$lab' AND  
((samples.datereceived !='0000-00-00') AND (samples.datedispatched !='')  AND (samples.datedispatched !='1970-01-01') AND (samples.datereceived !='1970-01-01') AND (samples.datereceived !='') AND (samples.datedispatched !='0000-00-00') )   AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datedispatched)='$year' AND 
MONTH(samples.datereceived)='$month' AND 
MONTH(samples.datedispatched)='$month' AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datereceived, $datedispatched) = mysql_fetch_array($strQuery)) {

                $sdrec = date("d-m-Y", strtotime($datereceived));
                $sdis = date("d-m-Y", strtotime($datedispatched));
                $workingdays = getTotalWorkingDays($sdrec, $sdis, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }
            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    } else {
        $strQuery = mysql_query("select  samples.datereceived,samples.datedispatched from 

samples WHERE samples.lab='$lab' AND  
((samples.datereceived !='0000-00-00') AND (samples.datedispatched !='')  AND (samples.datedispatched !='1970-01-01') AND (samples.datereceived !='1970-01-01') AND (samples.datereceived !='') AND (samples.datedispatched !='0000-00-00') )   AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datereceived, $datedispatched) = mysql_fetch_array($strQuery)) {

                $sdrec = date("d-m-Y", strtotime($datereceived));
                $sdis = date("d-m-Y", strtotime($datedispatched));
                $workingdays = getTotalWorkingDays($sdrec, $sdis, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }

            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    }
}

//get turn around time from receipt at lab to processing FOR particular year and/or month only repeats
function GetReceivedatLabtoTestingTAT($lab, $month, $year) {
    $incompletedate = "0000-00-00";
    if ($month != "") {

        $strQuery = mysql_query("select  samples.datereceived,samples.datetested from 

samples WHERE samples.lab='$lab' AND  
((samples.datereceived !='0000-00-00') AND (samples.datetested !='')  AND (samples.datetested !='1970-01-01') AND (samples.datereceived !='1970-01-01') AND (samples.datereceived !='') AND (samples.datetested !='0000-00-00') )   AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datetested)='$year' AND 
MONTH(samples.datereceived)='$month' AND 
MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datereceived, $datetested) = mysql_fetch_array($strQuery)) {

                $sdrec = date("d-m-Y", strtotime($datereceived));
                $sdtest = date("d-m-Y", strtotime($datetested));
                $workingdays = getTotalWorkingDays($sdrec, $sdtest, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }
            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    } else {
        $strQuery = mysql_query("select  samples.datereceived,samples.datetested from 
samples WHERE samples.lab='$lab' AND  
((samples.datereceived !='0000-00-00') AND (samples.datetested !='')  AND (samples.datetested !='1970-01-01') AND (samples.datereceived !='1970-01-01') AND (samples.datereceived !='') AND (samples.datetested !='0000-00-00') )   AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datetested)='$year'  AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datereceived, $datetested) = mysql_fetch_array($strQuery)) {

                $sdrec = date("d-m-Y", strtotime($datereceived));
                $sdtest = date("d-m-Y", strtotime($datetested));
                $workingdays = getTotalWorkingDays($sdrec, $sdtest, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }

            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    }
}

//get turn around time from testing at lab to update results FOR particular year and/or month only repeats
function GetTestedatLabtoUpdateTAT($lab, $month, $year) {
    $incompletedate = "0000-00-00";
    if ($month != "") {

        $strQuery = mysql_query("select  samples.datetested,samples.datemodified from 

samples WHERE samples.lab='$lab' AND  
((samples.datemodified !='0000-00-00') AND (samples.datetested !='')  AND (samples.datetested !='1970-01-01') AND (samples.datemodified !='1970-01-01') AND (samples.datemodified !='') AND (samples.datetested !='0000-00-00') )   AND YEAR(samples.datemodified)='$year'  AND YEAR(samples.datetested)='$year' AND 
MONTH(samples.datemodified)='$month' AND 
MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datetested, $datemodified) = mysql_fetch_array($strQuery)) {


                $sdtest = date("d-m-Y", strtotime($datetested));
                $sdupdated = date("d-m-Y", strtotime($datemodified));
                $workingdays = getTotalWorkingDays($sdtest, $sdupdated, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }
            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    } else {
        $strQuery = mysql_query("select  samples.datetested,samples.datemodified from 
samples WHERE samples.lab='$lab' AND  
((samples.datemodified !='0000-00-00') AND (samples.datetested !='')  AND (samples.datetested !='1970-01-01') AND (samples.datemodified !='1970-01-01') AND (samples.datemodified !='') AND (samples.datetested !='0000-00-00') )   AND YEAR(samples.datemodified)='$year'  AND YEAR(samples.datetested)='$year'  AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datetested, $datemodified) = mysql_fetch_array($strQuery)) {

                $sdtest = date("d-m-Y", strtotime($datetested));
                $sdupdated = date("d-m-Y", strtotime($datemodified));
                $workingdays = getTotalWorkingDays($sdtest, $sdupdated, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }

            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    }
}

//get turn around time from  update results to release for printing  FOR particular year and/or month only repeats
function GetUpdatedResultsatLabtoReleaseforPrintingTAT($lab, $month, $year) {
    $incompletedate = "0000-00-00";
    if ($month != "") {

        $strQuery = mysql_query("select  samples.datemodified,samples.datereleased from 

samples WHERE samples.lab='$lab' AND  
((samples.datemodified !='0000-00-00') AND (samples.datereleased !='')  AND (samples.datereleased !='1970-01-01') AND (samples.datemodified !='1970-01-01') AND (samples.datemodified !='') AND (samples.datereleased !='0000-00-00') )   AND YEAR(samples.datemodified)='$year'  AND YEAR(samples.datereleased)='$year' AND 
MONTH(samples.datemodified)='$month' AND 
MONTH(samples.datereleased)='$month' AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datemodified, $datereleased) = mysql_fetch_array($strQuery)) {
                $sdupdated = date("d-m-Y", strtotime($datemodified));
                $sdrelease = date("d-m-Y", strtotime($datereleased));
                $workingdays = getTotalWorkingDays($sdupdated, $sdrelease, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }
            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    } else {
        $strQuery = mysql_query("select  samples.datemodified,samples.datereleased from 
samples WHERE samples.lab='$lab' AND  
((samples.datemodified !='0000-00-00') AND (samples.datereleased !='')  AND (samples.datereleased !='1970-01-01') AND (samples.datemodified !='1970-01-01') AND (samples.datemodified !='') AND (samples.datereleased !='0000-00-00') )   AND YEAR(samples.datemodified)='$year'  AND YEAR(samples.datereleased)='$year'  AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datemodified, $datereleased) = mysql_fetch_array($strQuery)) {

                $sdupdated = date("d-m-Y", strtotime($datemodified));
                $sdrelease = date("d-m-Y", strtotime($datereleased));
                $workingdays = getTotalWorkingDays($sdupdated, $sdrelease, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }

            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    }
}

//get turn around time from  release for printing to dispatch FOR particular year and/or month only repeats
function GetReleaseforPrintingatLabtoDispatchTAT($lab, $month, $year) {
    $incompletedate = "0000-00-00";
    if ($month != "") {

        $strQuery = mysql_query("select  samples.datereleased,samples.datedispatched from 

samples WHERE samples.lab='$lab' AND  
((samples.datedispatched !='0000-00-00') AND (samples.datereleased !='')  AND (samples.datereleased !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched !='') AND (samples.datereleased !='0000-00-00') )   AND YEAR(samples.datedispatched)='$year'  AND YEAR(samples.datereleased)='$year' AND 
MONTH(samples.datedispatched)='$month' AND 
MONTH(samples.datereleased)='$month' AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datereleased, $datedispatched) = mysql_fetch_array($strQuery)) {
                $sdrelease = date("d-m-Y", strtotime($datereleased));
                $sdis = date("d-m-Y", strtotime($datedispatched));
                $workingdays = getTotalWorkingDays($sdrelease, $sdis, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }
            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    } else {
        $strQuery = mysql_query("select  samples.datereleased,samples.datedispatched from 
samples WHERE samples.lab='$lab' AND  
((samples.datedispatched !='0000-00-00') AND (samples.datereleased !='')  AND (samples.datereleased !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched !='') AND (samples.datereleased !='0000-00-00') )   AND YEAR(samples.datedispatched)='$year'  AND YEAR(samples.datereleased)='$year'  AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datereleased, $datedispatched) = mysql_fetch_array($strQuery)) {

                $sdrelease = date("d-m-Y", strtotime($datereleased));
                $sdis = date("d-m-Y", strtotime($datedispatched));
                $workingdays = getTotalWorkingDays($sdrelease, $sdis, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }

            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    }
}

//get turn around time from colllection at facility to dispatch FOR particular year and/or month only repeats
function GetCollectedtoDispatchTAT($lab, $month, $year) {
    $incompletedate = "0000-00-00";
    if ($month != "") {

        $strQuery = mysql_query("select  samples.datecollected,samples.datedispatched from samples WHERE samples.lab='$lab' AND  
((samples.datecollected !='0000-00-00') AND (samples.datedispatched !='')  AND (samples.datedispatched !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected !='') AND (samples.datedispatched !='0000-00-00') )  AND YEAR(samples.datecollected)='$year' AND YEAR(samples.datedispatched)='$year' AND MONTH(samples.datecollected)='$month' AND MONTH(samples.datedispatched)='$month'   AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datecollected, $datedispatched) = mysql_fetch_array($strQuery)) {

                $sdoc = date("d-m-Y", strtotime($datecollected));
                $sdis = date("d-m-Y", strtotime($datedispatched));
                $workingdays = getTotalWorkingDays($sdoc, $sdis, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }

            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    } else {
        $strQuery = mysql_query("select  samples.datecollected,samples.datedispatched from samples WHERE samples.lab='$lab' AND  
((samples.datecollected !='0000-00-00') AND (samples.datedispatched !='')  AND (samples.datedispatched !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected !='') AND (samples.datedispatched !='0000-00-00') )  AND YEAR(samples.datecollected)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
        $numsamples = mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
        $sumdates = 0;
        if ($numsamples != 0) {
            while (list($datecollected, $datedispatched) = mysql_fetch_array($strQuery)) {

                $sdoc = date("d-m-Y", strtotime($datecollected));
                $sdis = date("d-m-Y", strtotime($datedispatched));
                $workingdays = getTotalWorkingDays($sdoc, $sdis, $holidays);
                $totaldays = $workingdays - $totalholidays;
                $sumdates = $sumdates + $totaldays;
            }

            $ave = round(($sumdates / $numsamples), 1);
        } else {
            $ave = 0;
        }

        return array($numsamples, $ave);
    }
}

//get totalnumber of samples received particular year and/or month per province
function GetReceivedSamplesPerlabPerProvince($province, $lab, $month, $year) {
    if ($month != "") {

        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND facilitys.district=districts.ID AND districts.province='$province'  AND YEAR(samples.datereceived)='$year' AND MONTH(samples.datereceived)='$month' ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    } else {
        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND facilitys.district=districts.ID AND districts.province='$province' AND YEAR(samples.datereceived)='$year'") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    }
}

//get totalnumber of samples tested particular year and/or month per province
function GetTestedSamplesPerlabPerProvince($province, $lab, $month, $year) {
    if ($month != "") {

        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab'  AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result>0 AND YEAR(samples.datetested)='$year' AND MONTH(samples.datetested)='$month' ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    } else {
        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab'  AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result>0 AND YEAR(samples.datetested)='$year'") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    }
}

//get tested percetage per lab per province for month and/or year
function GetTestedpercentagePerProvince($province, $lab, $month, $year) {

    if (GetTestedSamplesPerlabPerProvince($province, $lab, $month, $year) == 0) {
        $per = 0;
    } else {
        $per = round(((GetTestedSamplesPerlabPerProvince($province, $lab, $month, $year) / GetReceivedSamplesPerlabPerProvince($province, $lab, $month, $year)) * 100), 2);
    }
    return $per;
}

//get totalnumber of samples tested particular year and/or month based on result type per province
function GetTestedSamplesPerlabByResultPerProvince($province, $lab, $month, $year, $resulttype) {
    if ($month != "") {

        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab'  AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result='$resulttype' AND samples.parentid IS NULL AND YEAR(samples.datetested)='$year' AND MONTH(samples.datetested)='$month' ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    } else {
        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab'  AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result='$resulttype' AND samples.parentid IS NULL AND YEAR(samples.datetested)='$year'") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    }
}

//get tested percetange per result per province
function GetTestedpercentagePerResultPerProvince($province, $lab, $month, $year, $resulttype) {

    if (GetTestedSamplesPerlabByResultPerProvince($province, $lab, $month, $year, $resulttype) == 0) {
        $per = 0;
    } else {
        $per = round(((GetTestedSamplesPerlabByResultPerProvince($province, $lab, $month, $year, $resulttype) / GetTestedSamplesPerlabPerProvince($province, $lab, $month, $year)) * 100), 2);
    }
    return $per;
}

//get totalnumber of samples tested particular year and/or month only repeats per province
function GetTestedRepeatSamplesPerlabPerProvince($province, $lab, $month, $year) {
    if ($month != "") {

        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND facilitys.district=districts.ID AND districts.province='$province' AND samples.parentid >0 AND YEAR(samples.datetested)='$year' AND MONTH(samples.datetested)='$month' ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    } else {
        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND facilitys.district=districts.ID AND districts.province='$province' AND samples.parentid >0 AND YEAR(samples.datetested)='$year'") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    }
}

//get totalnumber of REJECTED SAMPLES FOR particular year and/or month only repeats per province
function GetRejectedSamplesPerlabPerProvince($province, $lab, $month, $year) {
    if ($month != "") {

        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND facilitys.district=districts.ID AND districts.province='$province' AND samples.receivedstatus =2 AND YEAR(samples.datereceived)='$year' AND MONTH(samples.datereceived)='$month' ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    } else {
        $strQuery = mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.lab='$lab' AND facilitys.district=districts.ID AND districts.province='$province'  AND samples.receivedstatus =2 AND YEAR(samples.datereceived)='$year' ") or die(mysql_error());
        $resultarray = mysql_fetch_array($strQuery);
        $numsamples = $resultarray['numsamples'];
        return $numsamples;
    }
}

//get rejected percentage
function GetRejectedpercentagePerProvince($lab, $month, $year) {

    if (GetRejectedSamplesPerlabPerProvince($province, $lab, $month, $year) == 0) {
        $per = 0;
    } else {
        $per = round(((GetRejectedSamplesPerlabPerProvince($province, $lab, $month, $year) / GetReceivedSamplesPerlabPerProvince($province, $lab, $mononth, $year)) * 100), 2);
    }
    return $per;
}

//get lab name
function GetLabName($lab) {

    $facilityquery = mysql_query("SELECT name FROM labs where ID='$lab' ") or die(mysql_error());
    $dd = mysql_fetch_array($facilityquery);
    $labname = $dd['name'];
    return $labname;
}

//get month names from ID
function GetMonthName($month) {
    if ($month == 1) {
        $monthname = " Jan ";
    } else if ($month == 2) {
        $monthname = " Feb ";
    } else if ($month == 3) {
        $monthname = " Mar ";
    } else if ($month == 4) {
        $monthname = " Apr ";
    } else if ($month == 5) {
        $monthname = " May ";
    } else if ($month == 6) {
        $monthname = " Jun ";
    } else if ($month == 7) {
        $monthname = " Jul ";
    } else if ($month == 8) {
        $monthname = " Aug ";
    } else if ($month == 9) {
        $monthname = " Sep ";
    } else if ($month == 10) {
        $monthname = " Oct ";
    } else if ($month == 11) {
        $monthname = " Nov ";
    } else if ($month == 12) {
        $monthname = " Dec ";
    }
    return $monthname;
}

function getTotalWorkingDays($startDate, $endDate, $holidays) {


    //The total number of days between the two dates. We compute the no. of seconds and divide it to 60*60*24
    //We add one to inlude both dates in the interval.
    $days = (strtotime($endDate) - strtotime($startDate)) / 86400 + 1;

    $no_full_weeks = floor($days / 7);

    $no_remaining_days = fmod($days, 7);

    //It will return 1 if it's Monday,.. ,7 for Sunday
    $the_first_day_of_week = date("N", strtotime($startDate));

    $the_last_day_of_week = date("N", strtotime($endDate));
    // echo              $the_last_day_of_week;
    //---->The two can be equal in leap years when february has 29 days, the equal sign is added here
    //In the first case the whole interval is within a week, in the second case the interval falls in two weeks.
    if ($the_first_day_of_week <= $the_last_day_of_week) {
        if ($the_first_day_of_week <= 6 && 6 <= $the_last_day_of_week)
            $no_remaining_days--;
        if ($the_first_day_of_week <= 7 && 7 <= $the_last_day_of_week)
            $no_remaining_days--;
    }

    else {
        if ($the_first_day_of_week <= 6) {
            //In the case when the interval falls in two weeks, there will be a Sunday for sure
            $no_remaining_days--;
        }
    }

    //The no. of business days is: (number of weeks between the two dates) * (5 working days) + the remainder
//---->february in none leap years gave a remainder of 0 but still calculated weekends between first and last day, this is one way to fix it
    $workingDays = $no_full_weeks * 5;
    if ($no_remaining_days > 0) {
        $workingDays += $no_remaining_days;
    }

    //We subtract the holidays
    /*    foreach($holidays as $holiday){
      $time_stamp=strtotime($holiday);
      //If the holiday doesn't fall in weekend
      if (strtotime($startDate) <= $time_stamp && $time_stamp <= strtotime($endDate) && date("N",$time_stamp) != 6 && date("N",$time_stamp) != 7)
      $workingDays--;
      } */

    return $workingDays;
}

?>