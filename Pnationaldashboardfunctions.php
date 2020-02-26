<?php
require_once('connection/config.php'); 
?>
<?php
//get sample details
 

//get miother id for patient

//get lab in wich facility belongs to

//get sample result based on ID
function Getsamplesresult($sample)
{
$strQuery=mysql_query("SELECT result FROM samples WHERE ID='$sample' ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$result=$resultarray['result'];
return $result;
}
//get a value based on ID
function Getvalue($tablename,$id)
{
$strQuery=mysql_query("SELECT ID FROM tablename WHERE ID='$id' ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$ID=$resultarray['ID'];
return $ID;
}
//get totalnumber of sites
function Gettotalsites($province,$district)
{
if (($province !=0) && ($district ==0)) //filter by province
{
$strQuery=mysql_query("SELECT COUNT(facilitys.ID) as 'totalsites' FROM facilitys,districts where facilitys.district=districts.ID AND districts.province='$province ' AND facilitys.Flag=1 ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$totalsites=$resultarray['totalsites'];
return $totalsites;
}
else if (($province !=0) && ($district !=0)) //filter by district
{
$strQuery=mysql_query("SELECT COUNT(facilitys.ID) as 'totalsites' FROM facilitys where district='$district' AND Flag=1 ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$totalsites=$resultarray['totalsites'];
return $totalsites;
}
else
{
$strQuery=mysql_query("SELECT COUNT(facilitys.ID) as 'totalsites' FROM facilitys where Flag=1 ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$totalsites=$resultarray['totalsites'];
return $totalsites;
}
}
//get totalnumber of sites
function Gettotalsitesperprovince($province)
{
$strQuery=mysql_query("SELECT COUNT(facilitys.ID) as 'totalsites' FROM facilitys,districts where facilitys.Flag=1 AND facilitys.district=districts.ID AND districts.province='$province' ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$totalsites=$resultarray['totalsites'];
return $totalsites;
}
//get totalnumber of sites
function GettotalEIDsites($province,$district)
{
if (($province !=0) && ($district ==0)) //filter by province
{
$strQuery=mysql_query("SELECT COUNT(DISTINCT facilitys.ID) as 'activesites' FROM facilitys,districts WHERE facilitys.district = districts.ID AND districts.province='$province' AND facilitys.iseid='Yes'  ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$activesites=$resultarray['activesites'];
return $activesites;
}

else if (($province !=0) && ($district !=0)) //filter by district
{
$strQuery=mysql_query("SELECT COUNT(DISTINCT facilitys.ID) as 'activesites' FROM facilitys WHERE facilitys.district = '$district'  AND facilitys.iseid='Yes'    ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$activesites=$resultarray['activesites'];
return $activesites;
}
else
{
$strQuery=mysql_query("SELECT COUNT(DISTINCT facilitys.ID) as 'activesites' FROM facilitys WHERE facilitys.iseid='Yes'    ") or die(mysql_error());//mysql_query("SELECT COUNT(DISTINCT facility) as 'activesites' FROM samples ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$activesites=$resultarray['activesites'];
return $activesites;
}

}
//get totalnumber of sites
function GettotalActivesites($year,$month)
{
if ($month !=0)
{
$strQuery=mysql_query("SELECT COUNT(DISTINCT samples.facility) as 'activesites' FROM samples WHERE MONTH(samples.datetested) =$month  AND YEAR(samples.datetested)='$year'  ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$activesites=$resultarray['activesites'];
return $activesites;
}
else
{
$strQuery=mysql_query("SELECT COUNT(DISTINCT samples.facility) as 'activesites' FROM samples WHERE YEAR(samples.datetested)='$year'  ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$activesites=$resultarray['activesites'];
return $activesites;
}
}
//get totalnumber of sites
function GettotalActivesitesperprovince($province,$year,$month)
{
if ($month !=0)
{
$strQuery=mysql_query("SELECT COUNT(DISTINCT samples.facility) as 'activesites'  FROM samples,districts,facilitys
			  WHERE    samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result >0 AND MONTH(samples.datetested) =$month  AND YEAR(samples.datetested)='$year' AND samples.parentid=0 AND samples.Flag=1   ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$activesites=$resultarray['activesites'];
return $activesites;
}
else
{
$strQuery=mysql_query("SELECT COUNT(DISTINCT samples.facility) as 'activesites'  FROM samples,districts,facilitys
			  WHERE    samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result >0   AND YEAR(samples.datetested)='$year' AND samples.parentid=0 AND samples.Flag=1   ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$activesites=$resultarray['activesites'];
return $activesites;
}
}
//get totalnumber of sites
function GettotalInctivesitesnotsentinlast3months()
{
$strQuery=mysql_query("select facility,max(MONTH(datereceived)) as maxmonth from samples  group by facility ")or die(mysql_error());
$colcount = 0;
$currentmonth=date("m");
$last3months=$currentmonth=date("m")-3;

 while(list($facility,$maxmonth) = mysql_fetch_array($strQuery))
		{
		
		if (($currentmonth - $maxmonth) > 3)
		{
		$colcount=$colcount+1;
		}
		
		}

return $colcount;
}
//get totalnumber of samples received particular year
function Getallsamplescount($yea)
{

$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE  YEAR(samples.datereceived)='$yea'")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$numsamples=$resultarray['numsamples'];
return $numsamples;


}
//get totalnumber of samples tested ioverall for particular year
function Gettestedsamplescount($yea,$mwezi)
{
if ($mwezi !="")
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'alltestedsamples' FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$mwezi' AND samples.repeatt=0  AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$alltestedsamples=$resultarray['alltestedsamples'];
return $alltestedsamples;
}
else
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'alltestedsamples' FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0  AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$alltestedsamples=$resultarray['alltestedsamples'];
return $alltestedsamples;
}

}


//get totalnumber of samples tested ioverall for particular year LESS THAN 2 MONTHS
function Gettestedsamplescountlessthan2months($yea,$mwezi)
{
if ($mwezi !="")
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'alltestedsamples' FROM samples,patients WHERE samples.patient =patients.ID AND patients.age < 2 AND samples.result > 0 AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$mwezi' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$alltestedsamples=$resultarray['alltestedsamples'];
return $alltestedsamples;
}
else
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'alltestedsamples' FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$alltestedsamples=$resultarray['alltestedsamples'];
return $alltestedsamples;
}

}
//get totalnumber of samples tested ioverall for particular year for particular month
function Gettestedsamplescountpermonth($yea,$month)
{
if ($month !="")
{
$strQuery=mysql_query("SELECT COUNT(ID) as 'monthlytestedsamples' FROM samples WHERE result > 0  AND  YEAR(datetested)='$yea' AND MONTH(datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1    ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$monthlytestedsamples=$resultarray['monthlytestedsamples'];
return $monthlytestedsamples;
}
else
{
$strQuery=mysql_query("SELECT COUNT(ID) as 'monthlytestedsamples' FROM samples WHERE result > 0  AND  YEAR(datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$monthlytestedsamples=$resultarray['monthlytestedsamples'];
return $monthlytestedsamples;
}

}
//get totalnumber of results nationally for prticular year
function Getnationaloutcome($yea,$resulttype,$mwezi)
{
if ($mwezi !="")
{
$strQuery2 = "SELECT COUNT(ID) as 'outcome'
FROM samples
WHERE   result = '$resulttype' AND YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$mwezi' AND ((parentid=0) OR (parentid IS NULL)) AND Flag=1";  
  $result2 = mysql_query($strQuery2) or die(mysql_error());
	    $ors = mysql_fetch_array( $result2);
		$outcome=  $ors['outcome']  ;
		return $outcome;
}
else
{
$strQuery2 = "SELECT COUNT(ID) as 'outcome'
FROM samples
WHERE   result = '$resulttype' AND YEAR(samples.datetested)='$yea' AND ((parentid=0) OR (parentid IS NULL)) AND Flag=1";  
  $result2 = mysql_query($strQuery2) or die(mysql_error());
	    $ors = mysql_fetch_array( $result2);
		$outcome=  $ors['outcome']  ;
		return $outcome;
}
}
//get totalnumber of rejected samples nationally for prticular year
function Getnationalrejectedsamples($yea,$mwezi)
{
if ($mwezi !="")
{
$rejquery = "SELECT COUNT(ID) as rej_samples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)='$yea' AND MONTH(samples.datereceived)='$month' AND samples.repeatt=0 AND samples.Flag=1
";
$rejresult = mysql_query($rejquery) or die('Error, query failed');
$rejrow = mysql_fetch_array($rejresult, MYSQL_ASSOC);
$rej_samples = $rejrow['rej_samples'];

return $rej_samples;
}
else
{
$rejquery = "SELECT COUNT(ID) as rej_samples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1 ";
$rejresult = mysql_query($rejquery) or die('Error, query failed');
$rejrow = mysql_fetch_array($rejresult, MYSQL_ASSOC);
$rej_samples = $rejrow['rej_samples'];

return $rej_samples;

}

}

//get pmctc interventions vs positivity
function Getinterventionspositivitycount($drug,$resulttype,$yea,$mwezi)
{
if ($mwezi !=0)
{
$pmtctQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.result ='$resulttype' AND samples.patientid=patients.AutoID AND MONTH(samples.datetested)='$mwezi'   AND YEAR(samples.datetested)='$yea'  AND patients.mother=mothers.ID AND mothers.prophylaxis='$drug' AND samples.repeatt=0 AND samples.Flag=1   ") or die(mysql_error());

   $ors = mysql_fetch_array($pmtctQuery);
		$pmtct=  $ors['TotOutput']  ;
return $pmtct;
}
else 
{
	$pmtctQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.result ='$resulttype' AND samples.patientid=patients.AutoID AND YEAR(samples.datetested)='$yea'  AND patients.mother=mothers.ID AND mothers.prophylaxis='$drug' AND samples.repeatt=0 AND samples.Flag=1    ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$pmtct=  $ors['TotOutput']  ;
return $pmtct;
}
}
//get pmctc interventions vs positivity FOR INFANT
function Getinfantprophpositivitycount($drug,$resulttype,$yea,$month)
{
if ($month !=0)
{
	$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE   samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month'  AND  samples.patientid=patients.AutoID   AND patients.prophylaxis='$drug'  AND samples.repeatt=0 AND samples.Flag=1   ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
else
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE   samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND  samples.patientid=patients.AutoID   AND patients.prophylaxis='$drug'  AND samples.repeatt=0 AND samples.Flag=1   ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
}
//get infant prophylaxis vs feeding FOR INFANT greater than 6 weeks and are not on prophylaxis
function Getinfantprophylaxisnonecount618($feeding,$yea,$month)
{
if ($month ==13)
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND patients.prophylaxis=13 AND patients.age > 1.5    AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND patients.prophylaxis=13 AND patients.age BETWEEN 1.5 AND 18    AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
else
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND patients.prophylaxis=13 AND patients.age > 1.5    AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
}
//get infant prophylaxis vs feeding FOR INFANT greater than 6 weeks that are on prophylaxis
function Getinfantprophylaxisyescount618($feeding,$yea,$month)
{
if ($month !=0)
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND patients.prophylaxis BETWEEN 8 AND 12 AND patients.age > 1.5  AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND patients.prophylaxis BETWEEN 8 AND 12 AND patients.age BETWEEN 1.5 AND 18  AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
else
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND patients.prophylaxis BETWEEN 8 AND 12 AND patients.age > 1.5  AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
}
//get infant prophylaxis vs feeding FOR INFANT less than 6 weeks and are not on prophylaxis
function Getinfantprophylaxisnonecount06($feeding,$yea,$month)
{
if ($month ==13)
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND patients.prophylaxis=13 AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND patients.prophylaxis=13 AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
else
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND patients.prophylaxis=13 AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}

}
//get infant prophylaxis vs feeding FOR INFANT less than 6 weeks that are on prophylaxis
function Getinfantprophylaxisyescount06($feeding,$yea,$month)
{
if ($month ==13)
{
$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)BETWEEN 1 AND 9  AND patients.prophylaxis BETWEEN 8 AND 12 AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND patients.prophylaxis BETWEEN 8 AND 12 AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
else
{
$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID  AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND patients.prophylaxis BETWEEN 8 AND 12 AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;
return $infantproph;
}
}
//get national feeding options vs positivity
function Getfeedingpositivitycount($feeding,$resulttype,$yea,$month)
{
if ($month !=0)
{
$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.feeding='$feeding'  AND samples.repeatt=0 and samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
else if ($month ==0)
{
	$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 and samples.Flag=1   ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
else
{
$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND samples.repeatt=0 and samples.Flag=1   ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
}


//get national delviery options vs positivity
function Getdeliverypositivitycount($deliverymode,$resulttype,$yea,$month)
{
 if ($month ==0)
{
	$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.delivery='$deliverymode' AND samples.repeatt=0 and samples.Flag=1   ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
else
{
$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.delivery='$deliverymode' AND samples.repeatt=0 and samples.Flag=1    ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
}

//get mother proph usage count
function Getmotherprophusagecount($proph,$yea,$month)
{
 if ($month ==0)
{
	$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE  YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.prophylaxis='$proph'  AND samples.repeatt=0 and samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
else
{
$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE    YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.prophylaxis='$proph' AND samples.repeatt=0 and samples.Flag=1   ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
}

//get infant proph usage count
function Getinfantprophusagecount($proph,$yea,$month)
{
 if ($month ==0)
{
	$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE  YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID    AND patients.prophylaxis='$proph' AND samples.repeatt=0 and samples.Flag=1   ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
else
{
$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE    YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID   AND patients.prophylaxis='$proph' AND samples.repeatt=0 and samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
}
//get pmctc interventions vs positivity PER PROVINCE
function Getprovinceinterventionspositivitycount($province,$drug,$resulttype,$yea,$month)
{
if (month !=0)
{
	$drugQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.prophylaxis='$drug'  AND samples.repeatt=0 and samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($drugQuery);
		$drug=  $ors['TotOutput']  ;
return $drug;
}
else
{
$drugQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.prophylaxis='$drug'   AND samples.repeatt=0 and samples.Flag=1 ") or die(mysql_error());


    $ors = mysql_fetch_array($drugQuery);
		$drug=  $ors['TotOutput']  ;
return $drug;
}
}

//get entry points vs positivity PER PROVINCE
function Getprovinceentrypositivitycount($province,$entrypoint,$resulttype,$yea,$month)
{
if (month !=0)
{
	$entryQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point='$entrypoint' AND samples.repeatt=0 AND samples.Flag=1   ") or die(mysql_error());


    $ors = mysql_fetch_array($entryQuery);
		$entry=  $ors['TotOutput']  ;
		return $entry;
}
else
{
$entryQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point='$entrypoint' AND samples.repeatt=0 AND samples.Flag=1    ") or die(mysql_error());


    $ors = mysql_fetch_array($entryQuery);
		$entry=  $ors['TotOutput']  ;
		return $entry;
}
		
  

}


//get province vs positivity
function Getprovincepositivitycount($province,$resulttype,$yea,$month,$dcode,$fcode)
{
 
 if ($month ==0)
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys WHERE   samples.facility=facilitys.ID AND facilitys.district='$dcode'   AND samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea'   AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples WHERE   samples.facility='$fcode'  AND samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea'   AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else //only filter by default: province
		{
						$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'  AND samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
return $outcome;

		}
	


}
else
{
				if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys WHERE   samples.facility=facilitys.ID AND facilitys.district='$dcode'   AND samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples WHERE   samples.facility='$fcode'  AND samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else //only filter by default: province
		{	
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'  AND samples.result ='$resulttype' AND YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
}
}
//get totalnumber of samples tested per province
function Gettestedsamplescountperprovince($province,$yea,$month)
{
if ($month !="")
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'provincialtestedsamples' FROM samples,facilitys,districts  WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea' AND samples.result > 0")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$provincialtestedsamples=$resultarray['provincialtestedsamples'];
return $provincialtestedsamples;
}
else
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'provincialtestedsamples' FROM samples,facilitys,districts  WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'  AND YEAR(samples.datetested)='$yea' AND samples.result > 0 AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$provincialtestedsamples=$resultarray['provincialtestedsamples'];
return $provincialtestedsamples;
}
}
//get total number of particular result type overall
function Getoverallresultcount($resulttype,$yea)
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'allCount' FROM samples WHERE samples.result='$resulttype' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$allCount=$resultarray['allCount'];
return $allCount;
}
//get number of rejected samples
function Getrejectedsamplescount($yea)
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'rejectedCount' FROM samples WHERE samples.receivedstatus=2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$rejectedCount=$resultarray['rejectedCount'];
return $rejectedCount;
}


//get total number of particular result type for particular district
function Getdistrictresultcount($district,$resulttype,$yea)
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'DistrictCount' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district='$district' AND sample.result='$resultype' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$DistrictCount=$resultarray['DistrictCount'];
return $DistrictCount;
}
//get total number of particular result type for particular province
function Getprovinceresultcount($province,$resulttype,$yea,$month)
{
if ($month !="")
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'ProvinceCount' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result='$resulttype' AND MONTH(samples.datetested)='$month' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$ProvinceCount=$resultarray['ProvinceCount'];
return $ProvinceCount;
}
else
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'ProvinceCount' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result='$resulttype' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$ProvinceCount=$resultarray['ProvinceCount'];
return $ProvinceCount;
}
}
function Getprovincepositive($province,$resulttype,$yea,$month)
{
if ((Gettestedsamplescountperprovince($province,$yea,$month))!=0)
{
 $positive=round(((Getprovinceresultcount($province,$resulttype,$yea,$month))/(Gettestedsamplescountperprovince($province,$yea,$month))*100),2);
 }
 else
 {
  $positive=0;
 }
  return $positive;
}

//get totalnumber of samples received particular year per province
function Getallprovincesamplescount($province,$yea,$month)
{
if ($month ==13)
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  MONTH(samples.datereceived)BETWEEN 1 AND 9 AND  YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$numsamples=$resultarray['numsamples'];
return $numsamples;
}
else if ($month ==0)
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$numsamples=$resultarray['numsamples'];
return $numsamples;
}
else
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  MONTH(samples.datereceived)='$month' AND  YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$numsamples=$resultarray['numsamples'];
return $numsamples;
}

}
//get totalnumber of samples tested particular year per province
function Getalltestedprovincesamplescount($province,$yea,$month,$dcode,$fcode)
{
 if ($month ==0)
{
	if (($dcode !=0) && ($fcode ==0)) //filter by district
	{
			$strQuery = "SELECT COUNT(samples.ID) AS 'no_tested_samples'
              FROM samples,facilitys
			  WHERE  samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  samples.result >0  AND 	YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1
			  ";
    				$result = mysql_query($strQuery) or die(mysql_error());
				  $D2=mysql_fetch_array($result);
				$no_tested_samples=$D2['no_tested_samples'];

				return $no_tested_samples;
	}
	else if (($dcode ==0) && ($fcode !=0)) //filter by facility
	{
			$strQuery = "SELECT COUNT(samples.ID) AS 'no_tested_samples'
              FROM samples
			  WHERE  samples.facility='$fcode'  AND  samples.result >0  AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1
			  ";
    	$result = mysql_query($strQuery) or die(mysql_error());
				  $D2=mysql_fetch_array($result);
		$no_tested_samples=$D2['no_tested_samples'];
		return $no_tested_samples;
	}
	else //only filter by default: province
	{
			$strQuery = "SELECT COUNT(samples.ID) AS 'no_tested_samples'
              FROM samples,districts,facilitys
			  WHERE   samples.result >0  AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' 
			  ";
  		  $result = mysql_query($strQuery) or die(mysql_error());
				  $D2=mysql_fetch_array($result);
		$no_tested_samples=$D2['no_tested_samples'];

		return $no_tested_samples;
	}
 
}
else
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
				$strQuery = "SELECT COUNT(samples.ID) AS 'no_tested_samples'
              FROM samples,facilitys
			  WHERE  samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  samples.result >0  AND 	YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$month'  AND samples.repeatt=0 AND samples.Flag=1
			  ";
    				$result = mysql_query($strQuery) or die(mysql_error());
				  $D2=mysql_fetch_array($result);
				$no_tested_samples=$D2['no_tested_samples'];

				return $no_tested_samples;
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
					
					$strQuery = "SELECT COUNT(samples.ID) AS 'no_tested_samples'
              FROM samples
			  WHERE  samples.facility='$fcode'  AND  samples.result >0  AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1
			  ";
    	$result = mysql_query($strQuery) or die(mysql_error());
				  $D2=mysql_fetch_array($result);
		$no_tested_samples=$D2['no_tested_samples'];
		return $no_tested_samples;
		}
		else //only filter by default: province
		{
			
			$strQuery = "SELECT COUNT(samples.ID) AS 'no_tested_samples'
              FROM samples,districts,facilitys
			  WHERE    samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result >0 AND MONTH(samples.datetested)='$month' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1
			  ";
    		$result = mysql_query($strQuery) or die(mysql_error());
				  $D2=mysql_fetch_array($result);
			$no_tested_samples=$D2['no_tested_samples'];

			return $no_tested_samples;
		}


}
}

//get distrcit name
function GetFacilityName($fcode)
{
$districtnamequery=mysql_query("SELECT name 
            FROM facilitys
            WHERE  ID='$fcode'"); 
			$districtname = mysql_fetch_array($districtnamequery);  
			$facilityname=$districtname['name'];
		return $facilityname;
}



//get total number of rejected samples for particular province per year
function Getprovincerejectedsamples($province,$yea,$month,$dcode,$fcode)
{
if ($month ==13)
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'ProvinceRejected' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.receivedstatus=2 AND MONTH(samples.datereceived) BETWEEN 1 AND 9 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$ProvinceRejected=$resultarray['ProvinceRejected'];
return $ProvinceRejected;
}
else if ($month ==0)
{
				if (($dcode !=0) && ($fcode ==0)) //filter by district
				{
						$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'ProvinceRejected' FROM samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode' AND samples.receivedstatus=2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
						$ProvinceRejected=$resultarray['ProvinceRejected'];
						return $ProvinceRejected;
				}
				else if (($dcode ==0) && ($fcode !=0)) //filter by facility
				{
						$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'ProvinceRejected' FROM samples WHERE samples.facility='$fcode' AND samples.receivedstatus=2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
						$ProvinceRejected=$resultarray['ProvinceRejected'];
						return $ProvinceRejected;
				}
				else //only filter by default: province
				{
						$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'ProvinceRejected' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.receivedstatus=2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
						$ProvinceRejected=$resultarray['ProvinceRejected'];
						return $ProvinceRejected;
				}




}
else
{
				if (($dcode !=0) && ($fcode ==0)) //filter by district
				{
				$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'ProvinceRejected' FROM samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode' AND samples.receivedstatus=2 AND MONTH(samples.datereceived)='$month' AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
				$resultarray=mysql_fetch_array($strQuery);
				$ProvinceRejected=$resultarray['ProvinceRejected'];
				return $ProvinceRejected;
				}
				else if (($dcode ==0) && ($fcode !=0)) //filter by facility
				{
				$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'ProvinceRejected' FROM samples WHERE samples.facility='$fcode'  AND samples.receivedstatus=2 AND MONTH(samples.datereceived)='$month' AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
				$resultarray=mysql_fetch_array($strQuery);
				$ProvinceRejected=$resultarray['ProvinceRejected'];
				return $ProvinceRejected;
				}
				else //only filter by default: province
				{

				$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'ProvinceRejected' FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.receivedstatus=2 AND MONTH(samples.datereceived)='$month' AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
				$resultarray=mysql_fetch_array($strQuery);
				$ProvinceRejected=$resultarray['ProvinceRejected'];
				return $ProvinceRejected;
				}


}
}
//get percentage number of rejected samples for particular province per year
function Getprovincerejectedpercentage($province,$yea,$month)
{
if ((Getprovincerejectedsamples($province,$yea,$month))!=0)
{
 $rej=((Getprovincerejectedsamples($province,$yea,$month))/(Getallprovincesamplescount($province,$yea,$month))*100);
 }
 else
 {
  $rej=0;
 }
  return $rej;
}
//get average age of testing of all tested samples
function Getoverallaverageage($yea,$month)
{
if ($month !="")
{
$strQuery=mysql_query("SELECT AVG(patients.age) as 'averageage' FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.result >0  AND MONTH(samples.datetested)='$month' AND YEAR(samples.datetested)='$yea' ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$averageage=round($resultarray['averageage'],2);
return $averageage;
}
else
{
$strQuery=mysql_query("SELECT AVG(patients.age) as 'averageage' FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.result >0 AND YEAR(samples.datetested)='$yea' ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$averageage=round($resultarray['averageage'],2);
return $averageage;
}
}
//get average age of testing of all tested samples per province
function Getprovincialaverageage($province,$yea,$month,$dcode,$fcode)
{
 if ($month ==0)
{
		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$strQuery=mysql_query("SELECT AVG(patients.age) as 'provincialaverageage' FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND samples.result >0  AND YEAR(samples.datetested)='$yea' and patients.age between 0 AND 18 AND samples.repeatt=0  ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$provincialaverageage=round($resultarray['provincialaverageage'],1);
return $provincialaverageage;
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$strQuery=mysql_query("SELECT AVG(patients.age) as 'provincialaverageage' FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND samples.result >0   AND YEAR(samples.datetested)='$yea' and patients.age between 0 AND 18  AND samples.repeatt=0 ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$provincialaverageage=round($resultarray['provincialaverageage'],1);
return $provincialaverageage;
		
		}
		else //only filter by default: province
		{
		$strQuery=mysql_query("SELECT AVG(patients.age) as 'provincialaverageage' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result >0 AND YEAR(samples.datetested)='$yea' and patients.age between 0 AND 18 AND samples.repeatt=0  ")or die(mysql_error());
		$resultarray=mysql_fetch_array($strQuery);
		$provincialaverageage=round($resultarray['provincialaverageage'],1);
		return $provincialaverageage;
		}


}
else
{
			if (($dcode !=0) && ($fcode ==0)) //filter by district
			{
				$strQuery=mysql_query("SELECT AVG(patients.age) as 'provincialaverageage' FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND samples.result >0  AND MONTH(samples.datetested)='$month' AND YEAR(samples.datetested)='$yea' and patients.age between 0 AND 18  AND samples.repeatt=0")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$provincialaverageage=round($resultarray['provincialaverageage'],1);
return $provincialaverageage;
			}
			else if (($dcode ==0) && ($fcode !=0)) //filter by facility
			{
			$strQuery=mysql_query("SELECT AVG(patients.age) as 'provincialaverageage' FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND samples.result >0  AND MONTH(samples.datetested)='$month' AND YEAR(samples.datetested)='$yea' and patients.age between 0 AND 18 AND samples.repeatt=0 ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$provincialaverageage=round($resultarray['provincialaverageage'],1);
return $provincialaverageage;
			}
			else //only filter by default: province
			{
					$strQuery=mysql_query("SELECT AVG(patients.age) as 'provincialaverageage' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.result >0  AND MONTH(samples.datetested)='$month' AND YEAR(samples.datetested)='$yea' and patients.age between 0 AND 18 AND samples.repeatt=0 ")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$provincialaverageage=round($resultarray['provincialaverageage'],1);
return $provincialaverageage;
			}



}
}
//get  less than 6 weeks positivity
function Getageless2monthspositivitycount($resultype,$yea,$month)
{
if ($month ==13)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE    samples.result='$resultype' AND MONTH(samples.datetested) BETWEEN 1 AND 9  AND YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID    AND patients.age BETWEEN 0.1 AND 2 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE    samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID    AND patients.age BETWEEN 0.1 AND 2  AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
}
else
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID    AND patients.age BETWEEN 0.1 AND 2 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
}
}
//get   more than 2-9 months  positivity
function Getagemore2monthsto9monthspositivitycount($resultype,$yea,$month)
{
if ($month ==13)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND ((patients.age > 2) AND (patients.age <=9))  AND samples.result='$resultype' AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND YEAR(samples.datetested)='$yea'  AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND ((patients.age > 2) AND (patients.age <=9))  AND samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else
{
$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND ((patients.age > 2) AND (patients.age <=9))  AND samples.result='$resultype' AND MONTH(samples.datetested)='$month' AND YEAR(samples.datetested)='$yea'  AND samples.repeatt=0 AND samples.Flag=1") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
}
//get   more than 3 months and 9 months  positivity
function Getagemore3to9positivitycount($resultype,$yea,$month)
{
if ($month ==13)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND ((patients.age > 3) AND (patients.age <=9))  AND samples.result='$resultype'  AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND YEAR(samples.datetested)='$yea'  AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND ((patients.age > 3) AND (patients.age <=9))  AND samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND ((patients.age > 3) AND (patients.age <=9))  AND samples.result='$resultype'  AND MONTH(samples.datetested)='$month' AND YEAR(samples.datetested)='$yea'  AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
}
//get   more than 9 months and 18 months  positivity
function Getagemore9to18positivitycount($resultype,$yea,$month)
{
if ($month ==13)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND ((patients.age > 9) AND (patients.age <=18))  AND samples.result='$resultype'  AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND ((patients.age > 9) AND (patients.age <=18))  AND samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else
{
$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND ((patients.age > 9) AND (patients.age <=18))  AND samples.result='$resultype'  AND MONTH(samples.datetested)='$month' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
}
//get   more  18 months  positivity
function Getagemore18positivitycount($resultype,$yea,$month)
{
if ($month ==13)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND patients.age > 18  AND samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND patients.age > 18  AND samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND patients.age > 18  AND samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
}

// no of infant wtibetwen 0-6 weeks n feeding option
function Getinfantcountonfeedingtype06weeks($feeding,$yea,$month)
{
if ($month ==13)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID   AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID   AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND YEAR(samples.datetested)='$yea'  AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else
{
$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID   AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
}

// no of infant wtibetwen 6-18months n feeding option
function Getinfantcountonfeedingtype618($feeding,$yea,$month)
{
if ($month ==13)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID   AND patients.age BETWEEN 1.5  AND 18 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID   AND patients.age BETWEEN 1.5  AND 18 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND YEAR(samples.datetested)='$yea'  AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else
{
$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID   AND patients.age BETWEEN 1.5  AND 18 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
}



// no of infant wtibetwen 0-6 weeks n prophylaxis option
function Getinfantcountonprophylaxistype06weeks($prophylaxis,$yea,$month)
{
if ($month ==13)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID   AND patients.age <= 1.5 AND patients.prophylaxis='$prophylaxis' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID))as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID   AND patients.age <= 1.5 AND patients.prophylaxis='$prophylaxis' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else
{
$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID   AND patients.age <= 1.5 AND patients.prophylaxis='$prophylaxis' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
}

// no of infant wtibetwen 6-18months n prophylaxis option
function Getinfantcountonprophylaxistype618($prophylaxis,$yea,$month)
{
if ($month ==13)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID   AND patients.age BETWEEN 1.5  AND 18  AND patients.prophylaxis='$prophylaxis' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else if ($month ==0)
{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID   AND patients.age BETWEEN 1.5  AND 18  AND patients.prophylaxis='$prophylaxis' AND YEAR(samples.datetested)='$yea'  AND samples.repeatt=0 AND samples.Flag=1 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else
{
$prophQuery =mysql_query("SELECT count(DISTINCT(samples.ID))) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID   AND patients.age BETWEEN 1.5  AND 18  AND patients.prophylaxis='$prophylaxis' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
}




//get   missing ages  positivity
function Getagenullpositivitycount($resultype,$yea,$month)
{
 if ($month ==0)
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND patients.age <= 0  AND samples.result='$resultype' AND YEAR(samples.datetested)='$yea' ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND patients.age <= 0  AND samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
}
	
//get   missing ages  positivity
function Getageabove18positivitycount($resultype,$yea,$month)
{
 if ($month ==0)
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND patients.age >18  AND samples.result='$resultype' AND YEAR(samples.datetested)='$yea' ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}
else
{
$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE   samples.patientid=patients.AutoID    AND patients.age >18  AND samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$num=  $ors['TotOutput']  ;
return $num;
}

}
//get infant prophylaxis vs feeding FOR INFANT less than 6 weeks and are not on prophylaxis
function Getagevsfeedingless6($feeding,$yea,$month)
{
	$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID   AND patients.prophylaxis=11 AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND YEAR(samples.datetested)='$yea'  ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;

		
  
return $infantproph;
}

//get  number of patients per feedinging options  FOR INFANT less than 6 weeks 
function Getnoofinfantless6perfeeding($feeding,$yea,$month)
{
	$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID    AND patients.age <= 1.5 AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND YEAR(samples.datetested)='$yea' ") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;

		
  
return $infantproph;
}

//get  number of patients per feedinging options  FOR INFANT more than 6 weeks 
function Getnoofinfantmore6perfeeding($feeding,$yea,$month)
{
	$prophQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE   samples.patientid=patients.AutoID   AND patients.age > 1.5  AND patients.mother=mothers.ID AND mothers.feeding='$feeding' AND YEAR(samples.datetested)='$yea'") or die(mysql_error());


    $ors = mysql_fetch_array($prophQuery);
		$infantproph=  $ors['TotOutput']  ;

		
  
return $infantproph;
}
//get number of tests done per province in particular year for month of january
function Getnooftestspermonth($province,$year,$month)
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) AS 'noofprovincialtests'
              FROM samples,districts,facilitys
			  WHERE   samples.result >0 AND YEAR(samples.datetested)='$year' AND MONTH(samples.datetested)='$month' AND  samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			  
$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year 
function Getnooftestsperyear($province,$year)
{
$strQuery=mysql_query("SELECT COUNT(samples.ID) AS 'noofprovincialtests'
              FROM samples,districts,facilitys
			  WHERE   samples.result >0 AND YEAR(samples.datetested)='$year'  AND  samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
			  
$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of january
function Getnooftestsinjanuary($province,$year)
{
$startdate=$year."-01-01";
$enddate=$year."-01-31";
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND  (samples.datetested >='$startdate' AND samples.datetested <='$enddate') AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of february
function Getnooftestsinfebruary($province,$year)
{

$startdate=$year."-02-01";
$enddate=$year."-02-29";
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND (samples.datetested >='$startdate' AND samples.datetested <='$enddate') AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of march
function Getnooftestsinmarch($province,$year)
{

$startdate=$year."-03-01";
$enddate=$year."-03-31";
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND (samples.datetested >='$startdate' AND samples.datetested <='$enddate') AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());

$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of april
function Getnooftestsinapril($province,$year)
{

$startdate=$year."-04-01";
$enddate=$year."-04-30";
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND (samples.datetested >='$startdate' AND samples.datetested <='$enddate') AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());

$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of may
function Getnooftestsinmay($province,$year)
{

$startdate=$year."-05-01";
$enddate=$year."-05-31";
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND (samples.datetested >='$startdate' AND samples.datetested <='$enddate') AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());

$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of june
function Getnooftestsinjune($province,$year)
{

$startdate=$year."-06-01";
$enddate=$year."-06-30";
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND (samples.datetested >='$startdate' AND samples.datetested <='$enddate') AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());

$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of july
function Getnooftestsinjuly($province,$year)
{
$startdate=$year."-07-01";
$enddate=$year."-07-31";
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND (samples.datetested >='$startdate' AND samples.datetested <='$enddate') AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());

$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of august
function Getnooftestsinaugust($province,$year)
{
$startdate=$year."-08-01";
$enddate=$year."-08-31";
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND (samples.datetested >='$startdate' AND samples.datetested <='$enddate') AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());

$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of september
function Getnooftestsinseptember($province,$year)
{
$startdate=$year."-09-01";
$enddate=$year."-09-30";
 $strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND samples.datetested  BETWEEN '$startdate' AND '$enddate'  AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());

$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of october
function Getnooftestsinoctober($province,$year)
{
$startdate=$year."-10-01";
$enddate=$year."-10-31";
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND (samples.datetested >='$startdate' AND samples.datetested <='$enddate') AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());

$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of november
function Getnooftestsinnov($province,$year)
{
$startdate=$year."-11-01";
$enddate=$year."-11-30";
$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND (samples.datetested >='$startdate' AND samples.datetested <='$enddate') AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());

$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get number of tests done per province in particular year for month of december
function Getnooftestsindec($province,$year)
{
$startdate=$year."-12-1";
$enddate=$year."-12-31";

$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'noofprovincialtests' FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result IS NOT NULL AND YEAR(samples.datetested)='$year' AND ((samples.datetested >=$startdate) AND (samples.datetested <=$enddate)) AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'")or die(mysql_error());
$resultarray=mysql_fetch_array($strQuery);
$provincialtests=$resultarray['noofprovincialtests'];
return $provincialtests;
}
//get month names from ID
function GetMonthName($month)
{
 if ($month==1)
 {
     $monthname=" Jan ";
 }
else if ($month==2)
 {
     $monthname=" Feb ";
 }else if ($month==3)
 {
     $monthname=" Mar ";
 }else if ($month==4)
 {
     $monthname=" Apr ";
 }else if ($month==5)
 {
     $monthname=" May ";
 }else if ($month==6)
 {
     $monthname=" Jun ";
 }else if ($month==7)
 {
     $monthname=" Jul ";
 }else if ($month==8)
 {
     $monthname=" Aug ";
 }else if ($month==9)
 {
     $monthname=" Sep ";
 }else if ($month==10)
 {
     $monthname=" Oct ";
 }else if ($month==11)
 {
     $monthname=" Nov ";
 }
  else if ($month==12)
 {
     $monthname=" Dec ";
 }
  else if ($month==13)
 {
     $monthname=" Jan - Sep  ";
 }
return $monthname;
}




function getTotalHolidaysinMonth($month)
{
if ($month==0)
{
$totalholidays=10;
}
if ($month==1)
{
$totalholidays=1;
}
else if ($month==4)
{
$totalholidays=2;
}
else if ($month==5)
{
$totalholidays=1;
}
else if ($month==6)
{
$totalholidays=1;
}
else if ($month==8)
{
$totalholidays=1;
}
else if ($month==10)
{
$totalholidays=1;
}
else if ($month==12)
{
$totalholidays=3;
}

else
{
$totalholidays=0;
}

return $totalholidays;

}

//get turn around time rom collectioln to receipt at lab FOR particular year and/or month only repeats
function GetColletiontoReceivedatLabTAT($province,$month,$year,$dcode,$fcode)
{

 if ($month ==0) //only year
{
	
	if (($dcode !=0) && ($fcode ==0)) //filter by district
	{
	$strQuery=mysql_query("select samples.datecollected,samples.datereceived From samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  ((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected !='') AND (samples.datecollected !='0000-00-00') ) AND samples.datecollected <= samples.datereceived  AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datecollected)='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datereceived) = mysql_fetch_array($strQuery))
	{
	 	
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdoc=date("d-m-Y",strtotime($datecollected));
		
		$workingdays=getWorkingDays($sdoc,$sdrec,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else if (($dcode ==0) && ($fcode !=0)) //filter by facility
	{
	$strQuery=mysql_query("select samples.datecollected,samples.datereceived From samples WHERE samples.facility='$fcode'  AND  ((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected !='') AND (samples.datecollected !='0000-00-00') ) AND samples.datecollected <= samples.datereceived  AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datecollected)='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datereceived) = mysql_fetch_array($strQuery))
	{
	 	
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$workingdays=getWorkingDays($sdoc,$sdrec,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else //only filter by default: province
	{
$strQuery=mysql_query("select samples.datecollected,samples.datereceived From samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  ((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected !='') AND (samples.datecollected !='0000-00-00') ) AND samples.datecollected <= samples.datereceived  AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datecollected)='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datereceived) = mysql_fetch_array($strQuery))
	{
	 	
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$workingdays=getWorkingDays($sdoc,$sdrec,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	
		
}
else 
{
	if (($dcode !=0) && ($fcode ==0)) //filter by district
	{
	$strQuery=mysql_query("select  samples.datecollected,samples.datereceived From samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  
((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected !='') AND (samples.datecollected !='0000-00-00') )  AND samples.datecollected <= samples.datereceived AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datecollected)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datereceived) = mysql_fetch_array($strQuery))
	{
	  
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$workingdays=getWorkingDays($sdoc,$sdrec,$holidays) ;
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	
	}
	else if (($dcode ==0) && ($fcode !=0)) //filter by facility
	{
	$strQuery=mysql_query("select  samples.datecollected,samples.datereceived From samples WHERE samples.facility='$fcode'  AND  
((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected !='') AND (samples.datecollected !='0000-00-00') ) AND samples.datecollected <= samples.datereceived   AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datereceived) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$workingdays=getWorkingDays($sdoc,$sdrec,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays = $workingdays-$totalholidays;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else //only filter by default: province
	{
$strQuery=mysql_query("select  samples.datecollected,samples.datereceived From samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  ((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected !='') AND (samples.datecollected !='0000-00-00') ) AND samples.datecollected <= samples.datereceived    AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datecollected)='$year' AND MONTH(samples.datereceived)='$month' AND MONTH(samples.datecollected)='$month' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datereceived) = mysql_fetch_array($strQuery))
	{
	 	
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$workingdays=getWorkingDays($sdoc,$sdrec,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}


	return array($numsamples, $ave); 

	}

	}
}





//get turn around time from receipt at lab to dispatch FOR particular year and/or month only repeats

function GetReceivedatLabtoDispatchTAT($province,$month,$year,$dcode,$fcode)
{

 if ($month ==0)
{
	
	if (($dcode !=0) && ($fcode ==0)) //filter by district
	{
	$strQuery=mysql_query("select samples.datereceived,samples.datedispatched From samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  ((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') )  AND samples.datereceived <= samples.datedispatched   AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datedispatched )='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datedispatched) = mysql_fetch_array($strQuery))
	{
	 	
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdispatch=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdrec,$sdispatch,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else if (($dcode ==0) && ($fcode !=0)) //filter by facility
	{
	$strQuery=mysql_query("select samples.datereceived,samples.datedispatched From samples WHERE samples.facility='$fcode'  AND  ((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') )  AND samples.datereceived <= samples.datedispatched  AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datedispatched )='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datedispatched) = mysql_fetch_array($strQuery))
	{
	 	
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdispatch=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdrec,$sdispatch,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else //only filter by default: province
	{
$strQuery=mysql_query("select samples.datedispatched,samples.datereceived From samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  ((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') )  AND samples.datereceived <= samples.datedispatched   AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datedispatched )='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datedispatched,$datereceived) = mysql_fetch_array($strQuery))
	{
	 	
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdispatch=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdrec,$sdispatch,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	
		
}
else 
{
	if (($dcode !=0) && ($fcode ==0)) //filter by district
	{
	$strQuery=mysql_query("select  samples.datereceived,samples.datedispatched From samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  ((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') )   AND samples.datereceived <= samples.datedispatched AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdispatch=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdrec,$sdispatch,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays = $workingdays - $totalholidays;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	
	}
	else if (($dcode ==0) && ($fcode !=0)) //filter by facility
	{
	$strQuery=mysql_query("select  samples.datereceived,samples.datedispatched From samples WHERE samples.facility='$fcode'  AND  
((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') )   AND samples.datereceived <= samples.datedispatched AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdispatch=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdrec,$sdispatch,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays = $workingdays-$totalholidays;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else //only filter by default: province
	{
$strQuery=mysql_query("select samples.datedispatched,samples.datereceived From samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  ((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') )  AND samples.datereceived <= samples.datedispatched   AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datedispatched )='$year' AND MONTH(samples.datereceived)='$month' AND MONTH(samples.datedispatched )='$month' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datedispatched,$datereceived) = mysql_fetch_array($strQuery))
	{
	 	
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdispatch=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdrec,$sdispatch,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}


	return array($numsamples, $ave); 

	}

	}
}

//get turn around time from colllection at facility to dispatch FOR particular year and/or month only repeats
function GetCollectedtoDispatchTAT($province,$month,$year,$dcode,$fcode)
{
 if ($month ==0)
{
		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$strQuery=mysql_query("select  samples.datecollected,samples.datedispatched From samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  ((samples.datecollected !='0000-00-00') AND (samples.datecollected !='')  AND (samples.datecollected !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') )   AND samples.datecollected <= samples.datedispatched  AND YEAR(samples.datecollected)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
	while(list($datecollected,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$strQuery=mysql_query("select  samples.datecollected,samples.datedispatched From samples WHERE samples.facility='$fcode'  AND  ((samples.datecollected !='0000-00-00') AND (samples.datecollected !='')  AND (samples.datecollected !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') ) AND samples.datecollected <= samples.datedispatched  AND YEAR(samples.datecollected)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
	while(list($datecollected,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

		}
		else //only filter by default: province
		{
$strQuery=mysql_query("select  samples.datecollected,samples.datedispatched From samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  ((samples.datecollected !='0000-00-00') AND (samples.datecollected !='')  AND (samples.datecollected !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') ) AND samples.datecollected <= samples.datedispatched AND YEAR(samples.datecollected)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
	while(list($datecollected,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

		}
		
}
else
{
	if (($dcode !=0) && ($fcode ==0)) //filter by district
	{
	$strQuery=mysql_query("select  samples.datecollected,samples.datedispatched From samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  ((samples.datecollected !='0000-00-00') AND (samples.datecollected !='')  AND (samples.datecollected !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') )  AND samples.datecollected <= samples.datedispatched AND YEAR(samples.datecollected)='$year' AND YEAR(samples.datedispatched)='$year' AND MONTH(samples.datecollected)='$month' AND MONTH(samples.datedispatched)='$month'   AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
	
	}
	else
	{
		$ave=0;
	}
	
		return array($numsamples, $ave); 
	}
	else if (($dcode ==0) && ($fcode !=0)) //filter by facility
	{
	$strQuery=mysql_query("select  samples.datecollected,samples.datedispatched From samples WHERE samples.facility='$fcode' AND  ((samples.datecollected !='0000-00-00') AND (samples.datecollected !='')  AND (samples.datecollected !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') ) AND samples.datecollected <= samples.datedispatched  AND YEAR(samples.datecollected)='$year' AND YEAR(samples.datedispatched)='$year' AND MONTH(samples.datecollected)='$month' AND MONTH(samples.datedispatched)='$month'   AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
	
	}
	else
	{
		$ave=0;
	}
	
	return array($numsamples, $ave); 
	}

	else //only filter by default: province
	{
$strQuery=mysql_query("select  samples.datecollected,samples.datedispatched From samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  ((samples.datecollected !='0000-00-00') AND (samples.datecollected !='')  AND (samples.datecollected !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') )  AND samples.datecollected <= samples.datedispatched AND YEAR(samples.datecollected)='$year' AND YEAR(samples.datedispatched)='$year' AND MONTH(samples.datecollected)='$month' AND MONTH(samples.datedispatched)='$month'   AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
	
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 
}

	}
		}
//****************reports functions***************8///
//get any date


//get maximum year



//get totalnumber of samples tested particular year and/or month
function GetTestedSamplesPerlab($lab,$month,$year,$startdate,$enddate)
{
if ($month > 0)//month and year
{
	
		$strQuery=mysql_query("select count(DISTINCT(ID))  as numsamples from samples where lab='$lab' AND MONTH(datetested)='$month' and YEAR(datetested)='$year' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
}
else if ($month == 0) //only year
{
	$strQuery=mysql_query("select count(DISTINCT(ID))  as numsamples from samples where lab='$lab' AND YEAR(datetested)='$year' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
}
else
{
$strQuery=mysql_query("select count(DISTINCT(ID))  as numsamples from samples where lab='$lab' AND datetested BETWEEN '$startdate' AND '$enddate' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
}

}

//get totalnumber of REJECTED SAMPLES FOR particular year and/or month only repeats
function GetRejectedSamplesPerlab($lab,$month,$year,$startdate,$enddate)
{
if ($month > 0)
{ 		if ($lab ==2)
		{
		$strQuery=mysql_query("SELECT count(DISTINCT(samples.ID)) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.receivedstatus =2 AND YEAR(samples.datetested)='$year' AND MONTH(samples.datetested)='$month'  AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
		}
		else
		{
		$strQuery=mysql_query("SELECT count(DISTINCT(samples.ID)) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.receivedstatus =2 AND YEAR(samples.datereceived)='$year' AND MONTH(samples.datereceived)='$month'  AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
		}
	
		
}
else if ($month == 0)
{
	if ($lab ==2)
		{
		$strQuery=mysql_query("SELECT count(DISTINCT(samples.ID)) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.receivedstatus =2 AND YEAR(samples.datetested)='$year' AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
		}
		else
		{
	
	$strQuery=mysql_query("SELECT count(DISTINCT(samples.ID)) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.receivedstatus =2 AND YEAR(samples.datereceived)='$year' AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
	}
}
else
{

		if ($lab ==2)
		{
		$strQuery=mysql_query("SELECT count(DISTINCT(samples.ID)) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.receivedstatus =2  AND samples.datetested BETWEEN '$startdate' AND '$enddate' AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
		}
		else
		{
$strQuery=mysql_query("SELECT count(DISTINCT(samples.ID)) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.receivedstatus =2  AND samples.datereceived BETWEEN '$startdate' AND '$enddate' AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
		}
}

}

//get totalnumber of samples tested particular year and/or month based on result type
//function GetTestedSamplesPerlabByResult($lab,$month,$year,$resulttype)
function GetTestedSamplesPerlabByResult($lab,$month,$year,$resulttype,$startdate,$enddate)
{
//if ($month !=0)
//{
//	
//		$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.result='$resulttype' AND samples.repeatt=0 AND YEAR(samples.datetested)='$year' AND MONTH(samples.datetested)='$month' ")or die(mysql_error());
//	$resultarray=mysql_fetch_array($strQuery);
//	$numsamples=$resultarray['numsamples'];
//	return $numsamples;
//}
//else
//{
//	$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'numsamples' FROM samples WHERE samples.lab='$lab' AND samples.result='$resulttype' AND samples.repeatt=0 AND YEAR(samples.datetested)='$year'")or die(mysql_error());
//	$resultarray=mysql_fetch_array($strQuery);
//	$numsamples=$resultarray['numsamples'];
//	return $numsamples;
//}
////
if ($month > 0)//month and year
{
	
		$strQuery=mysql_query("select count(DISTINCT(ID))  as numsamples from samples where lab='$lab' AND MONTH(datetested)='$month' and YEAR(datetested)='$year' AND samples.repeatt=0 and samples.Flag=1 AND samples.result='$resulttype' ")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
}
else if ($month == 0) //only year
{
	$strQuery=mysql_query("select count(DISTINCT(ID))  as numsamples from samples where lab='$lab' AND YEAR(datetested)='$year' AND samples.repeatt=0 and samples.Flag=1 AND samples.result='$resulttype' ")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
}
else
{
$strQuery=mysql_query("select count(DISTINCT(ID))  as numsamples from samples where lab='$lab' AND datetested BETWEEN '$startdate' AND '$enddate' AND samples.repeatt=0 and samples.Flag=1 AND samples.result='$resulttype' ")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
}
////

}


//get quota name
function GetQuarterName($quarterly)
{
if ($quarterly ==1)
{
$quota="JAN-MAR";
}
else if ($quarterly ==2)
{
$quota="APR-JUN";
}
else if ($quarterly ==3)
{
$quota="JUL-SEP";
}
else if ($quarterly ==4)
{
$quota="OCT-DEC";
}


return $quota;
}


//get distrcit name

//get province id

//get province name

//get lab name


//get total number of particular result type for particular facility
function Getfacilityresultcount($facility,$resultype,$month,$yea)
{
	if ($month !=0)
	{
		$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$resultarray=mysql_fetch_array($strQuery);
		$TotalCount=$resultarray['TotalCount'];
		return $TotalCount;
	}
	else
	{
		$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
		$resultarray=mysql_fetch_array($strQuery);
		$TotalCount=$resultarray['TotalCount'];
		return $TotalCount;
	}
}

//get total number of tests for particular facility
function Getfacilitytestscount($facility,$month,$yea)
{
	if ($month !=0)
	{
		$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$resultarray=mysql_fetch_array($strQuery);
		$TotalCount=$resultarray['TotalCount'];
		return $TotalCount;
	}
	else
	{
		$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
		$resultarray=mysql_fetch_array($strQuery);
		$TotalCount=$resultarray['TotalCount'];
		return $TotalCount;
	}
}
//get total number of tests for particular facility
function Getfacilityrejectedcount($facility,$month,$yea)
{
	if ($month !=0)
	{
		$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.receivedstatus =2 AND YEAR(samples.datereceived)='$yea'  AND MONTH(samples.datereceived)='$month' AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
		$resultarray=mysql_fetch_array($strQuery);
		$TotalCount=$resultarray['TotalCount'];
		return $TotalCount;
	}
	else
	{
	
		$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.receivedstatus =2 AND YEAR(samples.datereceived)='$yea' AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
		$resultarray=mysql_fetch_array($strQuery);
		$TotalCount=$resultarray['TotalCount'];
		return $TotalCount;
	}
}

//periodic reports

//get total number of particular result type for particular facility
function Getfacilityresultcountpequarter($facility,$resultype,$quarterly,$quarteryear)
{
	if ($quarterly == 1 ) //january - March
		{
		$startmonth=1;
		$midmonth=2;
		$endmonth=3;
		}
		else if ($quarterly == 2 )
		{
		$startmonth=4;
		$midmonth=5;
		$endmonth=6;
		}
		else if ($quarterly == 3 )
		{
		$startmonth=7;
		$midmonth=8;
		$endmonth=9;
		}
		else if ($quarterly == 4 )
		{
		$startmonth=10;
		$midmonth=11;
		$endmonth=12;
		}
		//month 1
		$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$startmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$resultarray=mysql_fetch_array($strQuery);
		$TotalCount=$resultarray['TotalCount'];
		
		//month 2
		$strQuery2=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$midmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$resultarray2=mysql_fetch_array($strQuery2);
		$TotalCount2=$resultarray2['TotalCount'];
		
		//month 3
		$strQuery3=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$endmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$resultarray3=mysql_fetch_array($strQuery3);
		$TotalCount3=$resultarray3['TotalCount'];
		
		//TOTAL 
		$strQuery4=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result='$resultype' AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) BETWEEN  '$startmonth'  AND '$endmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$resultarray4=mysql_fetch_array($strQuery4);
		$TotalCount4=$resultarray4['TotalCount'];


		
		return array($TotalCount, $TotalCount2 ,$TotalCount3,$TotalCount4); 
	
}

//get total number of tests for particular facility
function Getfacilitytestscountperquarter($facility,$quarterly,$quarteryear)
{
	if ($quarterly == 1 ) //january - March
		{
		$startmonth=1;
		$midmonth=2;
		$endmonth=3;
		}
		else if ($quarterly == 2 )
		{
		$startmonth=4;
		$midmonth=5;
		$endmonth=6;
		}
		else if ($quarterly == 3 )
		{
		$startmonth=7;
		$midmonth=8;
		$endmonth=9;
		}
		else if ($quarterly == 4 )
		{
		$startmonth=10;
		$midmonth=11;
		$endmonth=12;
		}
		//month 1
		$strQuery=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$startmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$resultarray=mysql_fetch_array($strQuery);
		$TotalCount=$resultarray['TotalCount'];
		
		//month 2
		$strQuery2=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$midmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$resultarray2=mysql_fetch_array($strQuery2);
		$TotalCount2=$resultarray2['TotalCount'];
		
		//month 3
		$strQuery3=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) = '$endmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$resultarray3=mysql_fetch_array($strQuery3);
		$TotalCount3=$resultarray3['TotalCount'];
		
		//TOTAL 
		$strQuery4=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.result > 0 AND YEAR(samples.datetested)='$quarteryear' AND MONTH(samples.datetested) BETWEEN  '$startmonth' AND '$endmonth'  AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$resultarray4=mysql_fetch_array($strQuery4);
		$TotalCount4=$resultarray4['TotalCount'];
		
		return array($TotalCount, $TotalCount2 ,$TotalCount3,$TotalCount4); 
		
	
	
}
//get total number of tests for particular facility
function Getfacilityrejectedcountperquarter($facility,$quarterly,$quarteryear)
{
	if ($quarterly == 1 ) //january - March
		{
		$startmonth=1;
		
		$endmonth=3;
		}
		else if ($quarterly == 2 )
		{
		$startmonth=4;
		
		$endmonth=6;
		}
		else if ($quarterly == 3 )
		{
		$startmonth=7;
		
		$endmonth=9;
		}
		else if ($quarterly == 4 )
		{
		$startmonth=10;
				$endmonth=12;
		}

		//total rejected
		$strQuery4=mysql_query("SELECT COUNT(samples.ID) as 'TotalCount' FROM samples WHERE samples.facility='$facility' AND 	samples.receivedstatus =2 AND YEAR(samples.datereceived)='$quarteryear' AND MONTH(samples.datereceived) BETWEEN '$startmonth' AND '$endmonth' AND samples.repeatt=0 AND 	samples.Flag=1 ")or die(mysql_error());
		$resultarray4=mysql_fetch_array($strQuery4);
		$TotalCount4=$resultarray4['TotalCount'];
		
		
		return $TotalCount4; 
	
	
	
	
}


function GetMinDatetestedYear()
{
	$getanydate = "SELECT MIN(YEAR(datereceived)) AS lowdate FROM samples WHERE flag=1 AND result !=0 AND datereceived !='1970-01-01' AND datereceived !='0000-00-00' AND datereceived !=''";
	$anydate = mysql_query($getanydate) or die(mysql_error());
	$dateresult = mysql_fetch_array($anydate);
	$showdate = $dateresult['lowdate'];
	
return $showdate;
}

//get maximum year
function GetMaxDateTestedYear()
{
	$getmaxyear = "SELECT MAX( YEAR( datetested ) ) AS maximumyear FROM samples WHERE flag =1 AND result !=0 AND datetested !='1970-01-01' AND datetested !='0000-00-00' AND datetested !=''";
	$maxyear = mysql_query($getmaxyear) or die(mysql_error());
	$year = mysql_fetch_array($maxyear);
	$showyear = $year['maximumyear'];
	
return $showyear;
}


//get  less than 6 weeks positivity fpr regional view
function Getageless2monthspositivitycountbyprovince($resultype,$yea,$month,$province,$dcode,$fcode)
{
if ($month ==0)
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype'   AND YEAR(samples.datetested)='$yea'   AND patients.age  BETWEEN 0.1 AND 2 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype'  AND YEAR(samples.datetested)='$yea'   AND patients.age BETWEEN 0.1 AND 2 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
		else //only filter by default: province
		{

	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE  samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND   samples.result='$resultype' AND YEAR(samples.datetested)='$yea'    AND samples.repeatt=0 AND samples.Flag=1 AND samples.patientid=patients.AutoID  AND patients.age BETWEEN 0.1 AND 2 ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
	}
}
else
{
	
		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'   AND patients.age BETWEEN 0.1 AND 2 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'   AND patients.age BETWEEN 0.1 AND 2 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else //only filter by default: province
		{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'   AND patients.age BETWEEN 0.1 AND 2 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
}
}

//get  6 - 3 months  positivity fpr regional view
function Getagemore2monthsto9monthspositivitycountbyprovince($resultype,$yea,$month,$province,$dcode,$fcode)
{
if ($month ==0)
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype'   AND YEAR(samples.datetested)='$yea'    AND ((patients.age > 2) AND (patients.age <=9)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype'  AND YEAR(samples.datetested)='$yea'    AND ((patients.age > 2) AND (patients.age <=9)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
		else //only filter by default: province
		{

	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND   samples.result='$resultype' AND YEAR(samples.datetested)='$yea'   AND ((patients.age > 2) AND (patients.age <=9))   AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
	}
}
else
{
	
		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND ((patients.age > 2) AND (patients.age <=9)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'   AND ((patients.age > 2) AND (patients.age <=2)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else //only filter by default: province
		{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND ((patients.age > 2) AND (patients.age <=9)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
}
}

//get  3 - 9 months  positivity fpr regional view
function Getagemore3to9positivitycountbyprovince($resultype,$yea,$month,$province,$dcode,$fcode)
{
if ($month ==0)
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype'   AND YEAR(samples.datetested)='$yea'    AND ((patients.age >3) AND (patients.age <=9)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype'  AND YEAR(samples.datetested)='$yea'    AND ((patients.age >3) AND (patients.age <=9))  AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
		else //only filter by default: province
		{

	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND   samples.result='$resultype' AND YEAR(samples.datetested)='$yea'   AND ((patients.age >3) AND (patients.age <=9))   AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
	}
}
else
{
	
		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND ((patients.age >3) AND (patients.age <=9)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'   AND ((patients.age >3) AND (patients.age <=9)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else //only filter by default: province
		{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND ((patients.age >3) AND (patients.age <=9)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
}
}

//get  9 - 18 months  positivity fpr regional view
function Getagemore9to18positivitycountbyprovince($resultype,$yea,$month,$province,$dcode,$fcode)
{
if ($month ==0)
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype'   AND YEAR(samples.datetested)='$yea'    AND ((patients.age >9) AND (patients.age <=18)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype'  AND YEAR(samples.datetested)='$yea'    AND ((patients.age >9) AND (patients.age <=18)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
		else //only filter by default: province
		{

	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND   samples.result='$resultype' AND YEAR(samples.datetested)='$yea'   AND ((patients.age >9) AND (patients.age <=18))   AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
	}
}
else
{
	
		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND ((patients.age >9) AND (patients.age <=18)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'   AND ((patients.age >9) AND (patients.age <=18)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else //only filter by default: province
		{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND ((patients.age >9) AND (patients.age <=18)) AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
}
}

//get  > 18 months  positivity fpr regional view
function Getagemore18positivitycountbyprovince($resultype,$yea,$month,$province,$dcode,$fcode)
{
if ($month ==0)
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype'   AND YEAR(samples.datetested)='$yea'    AND patients.age >18 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype'  AND YEAR(samples.datetested)='$yea'    AND patients.age >18 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
		else //only filter by default: province
		{

	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND   samples.result='$resultype' AND YEAR(samples.datetested)='$yea'   AND patients.age > 18  AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
	}
}
else
{
	
		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND patients.age >18 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'   AND patients.age >18 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else //only filter by default: province
		{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND patients.age >18 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
}
}

//get  no data  positivity fpr regional view
function Getagenullpositivitycountbyprovince($resultype,$yea,$month,$province,$dcode,$fcode)
{
if ($month ==0)
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype'   AND YEAR(samples.datetested)='$yea'    AND patients.age  <=0 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype'  AND YEAR(samples.datetested)='$yea'    AND patients.age  <=0 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
		else //only filter by default: province
		{

	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND   samples.result='$resultype' AND YEAR(samples.datetested)='$yea'   AND patients.age  <=0    AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
	}
}
else
{
	
		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND patients.age  <=0 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'   AND patients.age  <=0 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else //only filter by default: province
		{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND patients.age  <=0 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
}
}

//get  no data  positivity fpr regional view
function Getagegreater18monhtspositivitycountbyprovince($resultype,$yea,$month,$province,$dcode,$fcode)
{
if ($month ==0)
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype'   AND YEAR(samples.datetested)='$yea'    AND patients.age  >18 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype'  AND YEAR(samples.datetested)='$yea'    AND patients.age  >18 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
		else //only filter by default: province
		{

	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND   samples.result='$resultype' AND YEAR(samples.datetested)='$yea'   AND patients.age  >18    AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
	}
}
else
{
	
		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND patients.age  >18 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		
		$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.facility='$fcode'  AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'   AND patients.age >18 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		
		}
		else //only filter by default: province
		{
	$prophQuery =mysql_query("SELECT count(DISTINCT(samples.patient)) as 'TotOutput'
            FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND    samples.result='$resultype' AND MONTH(samples.datetested)='$month'  AND YEAR(samples.datetested)='$yea'    AND patients.age  >18 AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());
$ors = mysql_fetch_array($prophQuery);
$numer=  $ors['TotOutput']  ;
return $numer;
		}
}
}

//get turn around time from receipt at lab to processing FOR particular year and/or month only repeats
function GetReceivedatLabtoProcessingTAT($province,$month,$year,$dcode,$fcode)
{
$incompletedate="0000-00-00";

if ($month ==0)
{

	if (($dcode !=0) && ($fcode ==0)) //filter by district
	{
	$strQuery=mysql_query("select  samples.datereceived,samples.datetested From samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  
samples.datereceived !='0000-00-00' AND samples.datetested !='0000-00-00'   AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datetested)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datetested) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datetested));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	
	}
	else if (($dcode ==0) && ($fcode !=0)) //filter by facility
	{
	$strQuery=mysql_query("select  samples.datereceived,samples.datetested From samples WHERE samples.facility='$fcode'  AND  
samples.datereceived !='0000-00-00' AND samples.datetested !='0000-00-00'   AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datetested)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datetested) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datetested));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else //only filter by default: province
	{
$strQuery=mysql_query("select  samples.datereceived,samples.datetested From samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  
samples.datereceived !='0000-00-00' AND samples.datetested !='0000-00-00'    AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datetested)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datetested) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datetested));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
		
}
else
{
	if (($dcode !=0) && ($fcode ==0)) //filter by district
	{
	$strQuery=mysql_query("select  samples.datereceived,samples.datetested From samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  
((samples.datereceived !='0000-00-00') AND (samples.datetested !='0000-00-00'))   AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datetested)='$year' AND 
MONTH(samples.datereceived)='$month' AND 
MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datetested) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datetested));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else if (($dcode ==0) && ($fcode !=0)) //filter by facility
	{
	$strQuery=mysql_query("select  samples.datereceived,samples.datetested From samples WHERE samples.facility='$fcode'  AND  
((samples.datereceived !='0000-00-00') AND (samples.datetested !='0000-00-00'))   AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datetested)='$year' AND 
MONTH(samples.datereceived)='$month' AND 
MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datetested) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datetested));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else //only filter by default: province
	{
$strQuery=mysql_query("select  samples.datereceived,samples.datetested From samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  
((samples.datereceived !='0000-00-00') AND (samples.datetested !='0000-00-00'))   AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datetested)='$year' AND 
MONTH(samples.datereceived)='$month' AND 
MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datetested) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datetested));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
		}
}


//get turn around time from receipt at lab to dispatch FOR particular year and/or month only repeats
function GetProcessedatLabtoDispatchTAT($province,$month,$year,$dcode,$fcode)
{
$incompletedate="0000-00-00";
if ($month ==0)
{

	if (($dcode !=0) && ($fcode ==0)) //filter by district
	{
	$strQuery=mysql_query("select  samples.datetested,samples.datedispatched From samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  
samples.datetested !='0000-00-00' AND samples.datedispatched !=''   AND YEAR(samples.datetested)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datetested,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datetested));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	
	}
	else if (($dcode ==0) && ($fcode !=0)) //filter by facility
	{
	$strQuery=mysql_query("select  samples.datetested,samples.datedispatched From samples WHERE samples.facility='$fcode'  AND  
samples.datetested !='0000-00-00' AND samples.datedispatched !=''   AND YEAR(samples.datetested)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datetested,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datetested));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else //only filter by default: province
	{
$strQuery=mysql_query("select  samples.datetested,samples.datedispatched From samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  
samples.datetested !='0000-00-00' AND samples.datedispatched !=''   AND YEAR(samples.datetested)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datetested,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datetested));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
		
}
else
{
	if (($dcode !=0) && ($fcode ==0)) //filter by district
	{
	$strQuery=mysql_query("select  samples.datetested,samples.datedispatched From samples,facilitys WHERE samples.facility=facilitys.ID AND facilitys.district='$dcode'  AND  
((samples.datetested !='0000-00-00') AND (samples.datedispatched !=''))   AND YEAR(samples.datetested)='$year'  AND YEAR(samples.datedispatched)='$year' AND 
MONTH(samples.datetested)='$month' AND 
MONTH(samples.datedispatched)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datetested,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datetested));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else if (($dcode ==0) && ($fcode !=0)) //filter by facility
	{
	$strQuery=mysql_query("select  samples.datetested,samples.datedispatched From samples WHERE samples.facility='$fcode'  AND  
((samples.datetested !='0000-00-00') AND (samples.datedispatched !=''))   AND YEAR(samples.datetested)='$year'  AND YEAR(samples.datedispatched)='$year' AND 
MONTH(samples.datetested)='$month' AND 
MONTH(samples.datedispatched)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datetested,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datetested));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
	else //only filter by default: province
	{
$strQuery=mysql_query("select  samples.datetested,samples.datedispatched From samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province' AND  
((samples.datetested !='0000-00-00') AND (samples.datedispatched !=''))   AND YEAR(samples.datetested)='$year'  AND YEAR(samples.datedispatched)='$year' AND 
MONTH(samples.datetested)='$month' AND 
MONTH(samples.datedispatched)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datetested,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datetested));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	}
		}
}


//get province vs first tsted samples
function Getregionalfirsttestedsamples($province,$yea,$month,$dcode,$fcode)
{
 
 if ($month ==0)
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys WHERE   samples.facility=facilitys.ID AND facilitys.district='$dcode'   AND samples.receivedstatus !=3 AND YEAR(samples.datetested)='$yea'   AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples WHERE   samples.facility='$fcode'  AND samples.receivedstatus !=3 AND YEAR(samples.datetested)='$yea'   AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else //only filter by default: province
		{
						$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'  AND samples.receivedstatus !=3 AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
return $outcome;

		}
	


}
else
{
				if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys WHERE   samples.facility=facilitys.ID AND facilitys.district='$dcode'   AND samples.receivedstatus !=3 AND YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples WHERE   samples.facility='$fcode'  AND samples.receivedstatus !=3 AND YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else //only filter by default: province
		{	
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'  AND samples.receivedstatus !=3 AND YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
}
}


//get province vs second tsted samples
function Getregionalsecondtestedsamples($province,$yea,$month,$dcode,$fcode)
{
 
 if ($month ==0)
{

		if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys WHERE   samples.facility=facilitys.ID AND facilitys.district='$dcode'   AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)='$yea'   AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples WHERE   samples.facility='$fcode'  AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)='$yea'   AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else //only filter by default: province
		{
						$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'  AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)='$yea' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
return $outcome;

		}
	


}
else
{
				if (($dcode !=0) && ($fcode ==0)) //filter by district
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys WHERE   samples.facility=facilitys.ID AND facilitys.district='$dcode'   AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else if (($dcode ==0) && ($fcode !=0)) //filter by facility
		{
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples WHERE   samples.facility='$fcode'  AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
		else //only filter by default: province
		{	
		$pmtctQuery =mysql_query("SELECT COUNT(DISTINCT(samples.ID)) as 'TotOutput'
            FROM samples,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province='$province'  AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)='$yea'  AND MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($pmtctQuery);
		$outcome=  $ors['TotOutput']  ;
		return $outcome;
		}
}
}

//get totalnumber of facilitys sending to lab particular year and/or month
function GetSupportedfacilitysPerlab($lab,$month,$year,$startdate,$enddate)
{
if ($month > 0)//month and year
{
	
		$strQuery=mysql_query("select count(DISTINCT(facility))  as numsamples from samples where lab='$lab' AND MONTH(datetested)='$month' and YEAR(datetested)='$year' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
}
else if ($month == 0) //only year
{
	$strQuery=mysql_query("select count(DISTINCT(facility))  as numsamples from samples where lab='$lab' AND YEAR(datetested)='$year' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
}
else
{
$strQuery=mysql_query("select count(DISTINCT(facility))  as numsamples from samples where lab='$lab' AND datetested BETWEEN '$startdate' AND '$enddate' AND samples.repeatt=0 and samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=$resultarray['numsamples'];
	return $numsamples;
}

}

//get mother on art count
function GetmotheronARTs($state,$yea,$month)
{
 if ($month ==0)
{
	$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE  YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.onart='$state'  AND samples.repeatt=0 and samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
else
{
$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE    YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.onart='$state'  AND samples.repeatt=0 and samples.Flag=1 ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
}

//get mother on arv count
function GetmotheronARVs($state,$yea,$month)
{
 if ($month ==0)
{
	$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE  YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.onart=2 AND mothers.receivearv='$state' AND samples.repeatt=0 and samples.Flag=1    ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
else
{
$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE    YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.onart=2 AND mothers.receivearv='$state' AND samples.repeatt=0 and samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
}

//get infants on arv count
function GetinfantsonARVs($state,$yea,$month)
{
 if ($month ==0)
{
	$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE  YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID  AND patients.infantarv='$state' AND samples.repeatt=0 and samples.Flag=1   ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
else
{
$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE    YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID  AND patients.infantarv='$state' AND samples.repeatt=0 and samples.Flag=1 ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
}

//get infants on arv count
function GetinfantsonCTX($state,$yea,$month)
{
 if ($month ==0)
{
	$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE  YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID  AND patients.onctx='$state' AND samples.repeatt=0 and samples.Flag= 1  ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
else
{
$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients WHERE    YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID  AND patients.onctx='$state' AND samples.repeatt=0 and samples.Flag=1") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
}


function Getinfantsonfeeding($state,$yea,$month)
{

 if ($month ==0)
{
	$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE  YEAR(samples.datetested)='$yea' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID  AND mothers.breastfeeding='$state' AND samples.repeatt=0 and samples.Flag=1    ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
else
{
$feedingQuery =mysql_query("SELECT COUNT(samples.ID) as 'TotOutput'
            FROM samples,patients,mothers WHERE    YEAR(samples.datetested)='$yea' AND MONTH(samples.datetested)='$month' AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.breastfeeding='$state' AND samples.repeatt=0 and samples.Flag=1  ") or die(mysql_error());


    $ors = mysql_fetch_array($feedingQuery);
		$feed=  $ors['TotOutput']  ;
return $feed;
}
}


/**

LAB TURN AROUN  TIMES FUCNTIONS


**/
//get turn around time from receipt at lab to processing FOR particular year and/or month only repeats
function GetCollectiontoReceivedatLabTAT($lab,$month,$year,$startdate,$enddate)
{
$incompletedate="0000-00-00";

if ($month ==0) // year ONLY
{
	$strQuery=mysql_query("select  samples.datecollected,samples.datereceived From samples WHERE  samples.lab='$lab'  AND  
((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected!='') AND (samples.datecollected !='0000-00-00') ) AND samples.datecollected <= samples.datereceived AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datecollected)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datereceived) = mysql_fetch_array($strQuery))
		{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$workingdays=getWorkingDays($sdoc,$sdrec,$holidays) ;
		$totalholidays=0;
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
		}
	
		$ave=round(($sumdates/$numsamples),1);
   }
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

}
else if ($month > 0) // MONTH AND  year ONLY
{
	
	$strQuery=mysql_query("select  samples.datereceived,samples.datecollected From samples WHERE  samples.lab='$lab'  AND  
((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datecollected !='1970-01-01') AND (samples.datecollected!='') AND (samples.datecollected !='0000-00-00') ) AND samples.datecollected <= samples.datereceived   AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datecollected)='$year' AND 
MONTH(samples.datereceived)='$month' AND 
MONTH(samples.datecollected)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datecollected) = mysql_fetch_array($strQuery))
		{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$workingdays=getWorkingDays($sdoc,$sdrec,$holidays) ;
		$totalholidays=0;
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
		}
	$ave=round(($sumdates/$numsamples),1);
  }
	else
  {
	$ave=0;
   }
	
	return array($numsamples, $ave); 

	
}
else //START N END DATE
{

$strQuery=mysql_query("select  samples.datereceived,samples.datecollected From samples WHERE  samples.lab='$lab'  AND samples.datereceived BETWEEN '$startdate' AND '$enddate' AND samples.datecollected BETWEEN '$startdate' AND '$enddate' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datecollected) = mysql_fetch_array($strQuery))
		{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$workingdays=getWorkingDays($sdoc,$sdrec,$holidays) ;
		$totalholidays=0;
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
		}
	
		$ave=round(($sumdates/$numsamples),1);
   }
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 


}
}



function GetReceivedatLabtoProcessingLABTAT($lab,$month,$year,$startdate,$enddate)
{
$incompletedate="0000-00-00";

if ($month ==0) // year ONLY
{
	$strQuery=mysql_query("select  samples.datereceived,samples.datetested From samples WHERE  samples.lab='$lab'  AND  
((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datetested !='1970-01-01') AND (samples.datetested!='') AND (samples.datetested !='0000-00-00') ) AND samples.datereceived <= samples.datetested AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datetested)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datetested) = mysql_fetch_array($strQuery))
		{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datetested));
		$workingdays=getWorkingDays($sdrec,$sdis,$holidays) ;
		//$totalholidays=getTotalHolidaysinMonth($month);


		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
		}
	
		$ave=round(($sumdates/$numsamples),1);
   }
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

}
else if ($month > 0) // MONTH AND  year ONLY
{
	
	$strQuery=mysql_query("select  samples.datereceived,samples.datetested From samples WHERE  samples.lab='$lab'  AND  
((samples.datereceived !='0000-00-00') AND (samples.datereceived !='')  AND (samples.datereceived !='1970-01-01') AND (samples.datetested !='1970-01-01') AND (samples.datetested!='') AND (samples.datetested !='0000-00-00') ) AND samples.datereceived <= samples.datetested   AND YEAR(samples.datereceived)='$year'  AND YEAR(samples.datetested)='$year' AND 
MONTH(samples.datereceived)='$month' AND 
MONTH(samples.datetested)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datetested) = mysql_fetch_array($strQuery))
		{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datetested));
		$workingdays=getWorkingDays($sdrec,$sdis,$holidays) ;
		//$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

		}
	$ave=round(($sumdates/$numsamples),1);
  }
	else
  {
	$ave=0;
   }
	
	return array($numsamples, $ave); 

	
}
else //START N END DATE
{

$strQuery=mysql_query("select  samples.datereceived,samples.datetested From samples WHERE  samples.lab='$lab'  AND samples.datereceived BETWEEN '$startdate' AND '$enddate' AND samples.datetested BETWEEN '$startdate' AND '$enddate' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datetested) = mysql_fetch_array($strQuery))
		{
	  
		$sdrec=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datetested));
		$workingdays=getWorkingDays($sdrec,$sdis,$holidays) ;
		$totalholidays=0;
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
		}
	
		$ave=round(($sumdates/$numsamples),1);
   }
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 


}
}

//get turn around time from receipt at lab to dispatch FOR particular year and/or month only repeats
function GetProcessedatLabtoDispatchLABTAT($lab,$month,$year,$startdate,$enddate)
{
$incompletedate="0000-00-00";
if ($month ==0)
{

	
	$strQuery=mysql_query("select  samples.datetested,samples.datedispatched From samples WHERE  samples.lab='$lab'  AND  
samples.datetested !='0000-00-00' AND samples.datedispatched !=''  AND samples.datetested <= samples.datedispatched  AND YEAR(samples.datetested)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datetested,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datetested));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdrec,$sdis,$holidays) ;
		//$totalholidays=getTotalHolidaysinMonth($month);


		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	
	
}
else if ($month > 0)
{
	
	$strQuery=mysql_query("select  samples.datetested,samples.datedispatched From samples WHERE  samples.lab='$lab'  AND  
((samples.datetested !='0000-00-00') AND (samples.datedispatched !=''))  AND samples.datetested <= samples.datedispatched  AND YEAR(samples.datetested)='$year'  AND YEAR(samples.datedispatched)='$year' AND 
MONTH(samples.datetested)='$month' AND 
MONTH(samples.datedispatched)='$month' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datetested,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datetested));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdrec,$sdis,$holidays) ;
		//$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

	
}
else 
{


	$strQuery=mysql_query("select  samples.datetested,samples.datedispatched From samples WHERE  samples.lab='$lab'  AND samples.datetested BETWEEN '$startdate' AND '$enddate' AND samples.datedispatched BETWEEN '$startdate' AND '$enddate' AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
		$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datetested,$datedispatched) = mysql_fetch_array($strQuery))
	{
	  
		$sdrec=date("d-m-Y",strtotime($datetested));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdrec,$sdis,$holidays) ;
		$totalholidays=0;
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;

		

	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

}
}

//get turn around time from receipt at lab to dispatch FOR particular year and/or month only repeats
function GetReceivedtoDispatchLABTAT($lab,$month,$year,$startdate,$enddate)
{
$incompletedate="0000-00-00";

 if ($month ==0)
{
		
		$strQuery=mysql_query("select  samples.datereceived,samples.datedispatched From samples WHERE  samples.lab='$lab'  AND  samples.datereceived !='0000-00-00' AND samples.datedispatched  !='' AND samples.datereceived <= samples.datedispatched  AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
	while(list($datereceived,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		//$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

}
else if ($month > 0)
{
	
	$strQuery=mysql_query("select  samples.datereceived,samples.datedispatched From samples WHERE  samples.lab='$lab'  AND  (samples.datereceived !='0000-00-00' AND  samples.datedispatched  !='') AND samples.datereceived <= samples.datedispatched  AND YEAR(samples.datereceived)='$year' AND YEAR(samples.datedispatched)='$year' AND MONTH(samples.datereceived)='$month' AND MONTH(samples.datedispatched)='$month'   AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datereceived,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		//$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
	
	}
	else
	{
		$ave=0;
	}
	
		return array($numsamples, $ave); 
	
}
else 
{
$strQuery=mysql_query("select  samples.datereceived,samples.datedispatched From samples WHERE  samples.lab='$lab' AND samples.datereceived BETWEEN '$startdate' AND '$enddate' AND samples.datedispatched BETWEEN '$startdate' AND '$enddate' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
	while(list($datereceived,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datereceived));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		$totalholidays=0;
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

}

}


//get turn around time from receipt at lab to dispatch FOR particular year and/or month only repeats
function GetCollectiontoDispatchLABTAT($lab,$month,$year,$startdate,$enddate)
{
$incompletedate="0000-00-00";

 if ($month ==0)
{
		
		$strQuery=mysql_query("select  samples.datecollected,samples.datedispatched From samples WHERE  samples.lab='$lab'  AND  ((samples.datecollected !='0000-00-00') AND (samples.datecollected  !='')  AND (samples.datecollected  !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') ) AND samples.datecollected <= samples.datedispatched  AND YEAR(samples.datecollected)='$year' AND YEAR(samples.datedispatched)='$year' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
	while(list($datecollected,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		//$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

}
else if ($month > 0)
{
	
	$strQuery=mysql_query("select  samples.datecollected,samples.datedispatched From samples WHERE  samples.lab='$lab'  AND  ((samples.datecollected !='0000-00-00') AND (samples.datecollected  !='')  AND (samples.datecollected  !='1970-01-01') AND (samples.datedispatched !='1970-01-01') AND (samples.datedispatched!='') AND (samples.datedispatched !='0000-00-00') ) AND samples.datecollected <= samples.datedispatched  AND YEAR(samples.datecollected)='$year' AND YEAR(samples.datedispatched)='$year' AND MONTH(samples.datecollected)='$month' AND MONTH(samples.datedispatched)='$month'   AND samples.repeatt=0 AND samples.Flag=1")or die(mysql_error());
	$resultarray=mysql_fetch_array($strQuery);
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
		while(list($datecollected,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		//$totalholidays=getTotalHolidaysinMonth($month);
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
	
	}
	else
	{
		$ave=0;
	}
	
		return array($numsamples, $ave); 
	
}
else 
{
$strQuery=mysql_query("select  samples.datecollected,samples.datedispatched From samples WHERE  samples.lab='$lab' AND samples.datecollected BETWEEN '$startdate' AND '$enddate' AND samples.datedispatched BETWEEN '$startdate' AND '$enddate' AND samples.repeatt=0 AND samples.Flag=1 ")or die(mysql_error());
	$numsamples=mysql_num_rows($strQuery);  //no of samples with complete dae collcted and date received 
	$sumdates=0;
	if ($numsamples !=0)
	{
	while(list($datecollected,$datedispatched) = mysql_fetch_array($strQuery))
	{
		
		$sdoc=date("d-m-Y",strtotime($datecollected));
		$sdis=date("d-m-Y",strtotime($datedispatched));
		$workingdays=getWorkingDays($sdoc,$sdis,$holidays) ;
		$totalholidays=0;
		$totaldays =$workingdays -$totalholidays ;
		$sumdates=$sumdates+$totaldays;
	}
	
	$ave=round(($sumdates/$numsamples),1);
}
else
{
	$ave=0;
}
	
	return array($numsamples, $ave); 

}

}

//get quota name
function  GetBiAnnualName($biannual)
{
if ($biannual ==1)
{
$quota="JAN-JUN";
}
else if ($biannual ==2)
{
$quota="JUL-DEC";
}
return $quota;
}

//get received status based on ID


//get mothers entry point


//get mothers feeding types

//get mothers pmtct intervention


//get sample result

//get mothers hivstatus

?>