
DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getallprovincesamplescount`$$

CREATE PROCEDURE  `eid_zim`.`Getallprovincesamplescount`
(
IN province INT,
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0  THEN 
	SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province  AND  YEAR(samples.datereceived)=yea AND samples.repeatt=0 AND samples.Flag=1;                  
                 
  ELSE
    SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND  MONTH(samples.datereceived)=month AND  YEAR(samples.datereceived)=yea AND samples.repeatt=0 AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Gettestedsamplescountperprovince`$$

CREATE PROCEDURE  `eid_zim`.`Gettestedsamplescountperprovince`
(
IN province INT,
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
       SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts  WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province   AND YEAR(samples.datetested)=yea AND samples.result >0 AND samples.repeatt=0  AND samples.Flag=1;            
   ELSE
 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts  WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND MONTH(samples.datetested)=month  AND YEAR(samples.datetested)=yea AND samples.result >0 AND samples.repeatt=0 AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getprovinceresultcount`$$

CREATE PROCEDURE  `eid_zim`.`Getprovinceresultcount`
(
IN province INT,
IN yea INT,
IN month INT,
IN resulttype INT,
OUT numsamples INT
)
BEGIN
IF month =0 THEN                  
  SELECT COUNT(DISTINCT(samples.ID)) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result=resulttype  AND YEAR(samples.datetested)=yea AND samples.repeatt=0 AND samples.Flag=1;              
 ELSEIF month =13 THEN
    SELECT COUNT(DISTINCT(samples.ID)) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result=resulttype AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND YEAR(samples.datetested)=yea AND samples.repeatt=0 AND samples.Flag=1;   
  ELSE
   SELECT COUNT(DISTINCT(samples.ID)) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result=resulttype AND MONTH(samples.datetested)=month AND YEAR(samples.datetested)=yea AND samples.repeatt=0 AND samples.Flag=1 ; 
            
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getprovincerejectedsamples`$$

CREATE PROCEDURE  `eid_zim`.`Getprovincerejectedsamples`
(
IN province INT,
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month =0 THEN 
 SELECT COUNT(DISTINCT(samples.ID)) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.receivedstatus=2 AND YEAR(samples.datereceived)=yea AND  samples.repeatt=0 AND samples.Flag=1;                    
  ELSEIF month =13 THEN   
   SELECT COUNT(DISTINCT(samples.ID)) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.receivedstatus=2 AND MONTH(samples.datereceived)BETWEEN 1 AND 9 AND YEAR(samples.datereceived)=yea AND samples.repeatt=0 AND samples.Flag=1 ;               
  ELSE
 SELECT COUNT(DISTINCT(samples.ID)) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.receivedstatus=2 AND MONTH(samples.datereceived)=month AND YEAR(samples.datereceived)=yea AND samples.repeatt=0 AND samples.Flag=1 ;           
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getprovincialaverageage`$$

CREATE PROCEDURE  `eid_zim`.`Getprovincialaverageage`
(
IN province INT,
IN yea INT,
IN month INT,
OUT averageage FLOAT
)
BEGIN
IF month =0 THEN                  
      SELECT AVG(patients.age) INTO averageage FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result >0   AND YEAR(samples.datetested)=yea AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.repeatt=0 AND samples.Flag=1 ;     
   ELSEIF month =13 THEN 
      SELECT AVG(patients.age) INTO averageage FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result >0  AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND YEAR(samples.datetested)=yea AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.repeatt=0 AND samples.Flag=1 ;    
  ELSE
     SELECT AVG(patients.age) INTO averageage FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result >0  AND MONTH(samples.datetested)=month AND YEAR(samples.datetested)=yea AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.repeatt=0 AND samples.Flag=1 ;    
           
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getoverallaverageage`$$

CREATE PROCEDURE  `eid_zim`.`Getoverallaverageage`
(
IN yea INT,
IN month INT,
OUT averageage FLOAT
)
BEGIN
IF month =0 THEN                  
  SELECT AVG(patients.age) INTO averageage FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.result >0 AND YEAR(samples.datetested)=yea AND samples.Flag=1 and patients.age between 0 AND 18  AND samples.repeatt=0 ;       
  ELSE
 	 SELECT AVG(patients.age) INTO averageage FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.result >0  AND MONTH(samples.datetested)=month AND YEAR(samples.datetested)=yea AND samples.Flag=1 and patients.age between 0 AND 18  AND samples.repeatt=0;            
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Gettestedsamplescount`$$

CREATE PROCEDURE  `eid_zim`.`Gettestedsamplescount`
(
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
      SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1;            
       
  ELSE
 SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Gettestedsamplescountforconfirmatory`$$

CREATE PROCEDURE  `eid_zim`.`Gettestedsamplescountforconfirmatory`
(
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
      SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths'  AND samples.repeatt=0  AND samples.Flag=1;            
       
  ELSE
 SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND samples.repeatt=0  AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getoverallfirsttestedsamples`$$

CREATE PROCEDURE  `eid_zim`.`Getoverallfirsttestedsamples`
(
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
      SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0 AND samples.receivedstatus !=3    AND samples.Flag=1;            
         
  ELSE
 SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0 AND samples.receivedstatus !=3   AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;






DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getoverallconfirmatorytestedsamples`$$

CREATE PROCEDURE  `eid_zim`.`Getoverallconfirmatorytestedsamples`
(
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
      SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0 AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths'  AND samples.Flag=1;            
         
  ELSE
 SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0 AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Gettestedsamplescountlessthan2months`$$

CREATE PROCEDURE  `eid_zim`.`Gettestedsamplescountlessthan2months`
(
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
      SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2 AND samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0 AND samples.Flag=1;            
   ELSEIF month =13 THEN 
    SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2  AND samples.result > 0 AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND samples.repeatt=0 AND samples.Flag=1;          
  ELSE

 SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2 AND samples.result > 0 AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested)=month AND samples.repeatt=0 AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getnationaloutcome`$$

CREATE PROCEDURE  `eid_zim`.`Getnationaloutcome`
(
IN yea INT,
IN month INT,
IN resulttype INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
    SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility > 0;           
  ELSEIF month =13 THEN 
     
 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea   AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility > 0;          
  ELSE

SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea   AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility > 0;          
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getnationaloutcomeforconfirmatory`$$

CREATE PROCEDURE  `eid_zim`.`Getnationaloutcomeforconfirmatory`
(
IN yea INT,
IN month INT,
IN resulttype INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
    SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND samples.Flag=1;           
  ELSEIF month =13 THEN 
     
 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea   AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND samples.repeatt=0 AND samples.Flag=1;          
  ELSE

SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea   AND MONTH(samples.datetested)=month AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND samples.repeatt=0  AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getnationalrejectedsamples`$$

CREATE PROCEDURE  `eid_zim`.`Getnationalrejectedsamples`
(
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
   SELECT COUNT(ID) INTO numsamples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)=yea  AND samples.repeatt=0 AND samples.Flag=1;           
   
 ELSEIF month =13 THEN
   SELECT COUNT(ID) INTO numsamples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)=yea AND MONTH(samples.datereceived) BETWEEN 1 AND 9 AND samples.repeatt=0 AND samples.Flag=1;          
  ELSE
 SELECT COUNT(ID) INTO numsamples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)=yea AND MONTH(samples.datereceived)=month AND samples.repeatt=0 AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getinterventionspositivitycount`$$

CREATE PROCEDURE  `eid_zim`.`Getinterventionspositivitycount`
(
IN drug INT,
IN yea INT,
IN month INT,
IN resulttype INT,
OUT numtests INT
)
BEGIN
IF month =0 THEN    
SELECT COUNT(DISTINCT(samples.patientID)) INTO numtests from samples,patients,mothers where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND samples.Flag=1 AND mothers.art=drug;
ELSEIF month =13 THEN         
SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests from samples,patients,mothers where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND samples.Flag=1 AND mothers.art=drug;   
ELSE 
SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests from samples,patients,mothers where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.Flag=1 AND mothers.art=drug;
             
 END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getnooftestspermonth`$$

CREATE PROCEDURE  `eid_zim`.`Getnooftestspermonth`
(
IN province INT,
IN yea INT,
IN month INT,
OUT numtests INT
)
BEGIN

SELECT COUNT(DISTINCT(samples.ID)) INTO numtests
              FROM samples,districts,facilitys
			  WHERE   samples.result > 0  AND samples.repeatt=0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND  samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province  AND samples.Flag=1;          
  

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getnooftestsperyear`$$

CREATE PROCEDURE  `eid_zim`.`Getnooftestsperyear`
(
IN province INT,
IN yea INT,
OUT numtests INT
)
BEGIN

SELECT COUNT(DISTINCT(samples.ID)) INTO numtests
              FROM samples,districts,facilitys
			  WHERE   samples.result >0  AND samples.repeatt=0 AND samples.Flag=1 AND YEAR(samples.datetested)=yea  AND  samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province ;          
  

END $$

DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getprovincepositivitycount`$$

CREATE PROCEDURE  `eid_zim`.`Getprovincepositivitycount`
(
IN province INT,
IN resulttype INT,
IN yea INT,
OUT numtests INT
)
BEGIN

SELECT COUNT(DISTINCT(samples.ID)) INTO numtests
            FROM samples,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province  AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND samples.repeatt=0 AND samples.Flag=1 ;          
  

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Gettestedsamplescountpermonth`$$

CREATE PROCEDURE  `eid_zim`.`Gettestedsamplescountpermonth`
(
IN yea INT,
IN month INT,
OUT numtests INT
)
BEGIN

SELECT COUNT(ID) INTO numtests  FROM samples WHERE result > 0  AND facility > 0 AND  YEAR(datetested)=yea AND MONTH(datetested)=month AND samples.repeatt=0 AND samples.Flag=1;    

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getprovinceinterventionspositivitycount`$$

CREATE PROCEDURE  `eid_zim`.`Getprovinceinterventionspositivitycount`
(
IN province INT,
IN drug INT,
IN resulttype INT,
IN yea INT,
IN month INT,
IN dcode INT,
IN fcode INT,
OUT numsamples INT
)
BEGIN
IF (month = 0) THEN 
    
IF (dcode > 0) THEN
SELECT  COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys WHERE   samples.facility=facilitys.ID AND facilitys.district=dcode  AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.art=drug AND samples.repeatt=0 AND samples.Flag=1; 
ELSEIF (fcode >0) THEN
SELECT  COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers WHERE   samples.facility=fcode  AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.art=drug AND samples.repeatt=0 AND samples.Flag=1; 
ELSE
SELECT  COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.art=drug AND samples.repeatt=0 AND samples.Flag=1;  
END IF;            
 ELSE
IF (dcode > 0) THEN 
 SELECT  COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys WHERE   samples.facility=facilitys.ID AND facilitys.district=dcode  AND samples.result =resulttype AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested)=month AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.art=drug AND samples.repeatt=0 AND samples.Flag=1;          


ELSEIF (fcode >0) THEN
 SELECT  COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers WHERE   samples.facility=fcode  AND samples.result =resulttype AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested)=month AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.art=drug AND samples.repeatt=0 AND samples.Flag=1;          
   
ELSE
  
   SELECT  COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result =resulttype AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested)=month AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.art=drug AND samples.repeatt=0 AND samples.Flag=1;          
  END IF; 
END IF; 
END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getprovinceentrypositivitycount`$$

CREATE PROCEDURE  `eid_zim`.`Getprovinceentrypositivitycount`
(
IN province INT,
IN entrypoint INT,
IN resulttype INT,
IN yea INT,
IN month INT,
IN dcode INT,
IN fcode INT,
OUT numsamples INT
)
BEGIN
IF (month = 0) THEN  
 	IF (dcode > 0) THEN
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys WHERE   samples.facility=facilitys.ID AND facilitys.district=dcode  AND samples.result =resulttype AND YEAR(samples.datetested)=yea  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 ;  
 	ELSEIF (fcode >0) THEN 
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers WHERE   samples.facility=fcode  AND samples.result =resulttype AND YEAR(samples.datetested)=yea  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 ;  
 	ELSE             
     SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result =resulttype AND YEAR(samples.datetested)=yea  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 ;  
  	END IF;
 
       
ELSEIF (month > 0) THEN
IF (dcode > 0) THEN 
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys WHERE   samples.facility=facilitys.ID AND facilitys.district=dcode  AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 ; 
ELSEIF (fcode >0) THEN 
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers WHERE   samples.facility=fcode  AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 ; 
ELSE 
   SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 ;  
  END IF;

        
  END IF; 

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getinfantprophpositivitycount`$$

CREATE PROCEDURE  `eid_zim`.`Getinfantprophpositivitycount`
(
IN drug INT,
IN yea INT,
IN month INT,
IN resulttype INT,
OUT numtests INT
)
BEGIN
IF month =0 THEN                  
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests
            FROM samples,patients WHERE   samples.result =resulttype AND YEAR(samples.datetested)=yea  AND  samples.patientid=patients.AutoID   AND patients.prophylaxis=drug  AND samples.repeatt=0 AND samples.Flag=1  ;   
  ELSEIF  month = 13 THEN
  SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests
            FROM samples,patients WHERE   samples.result =resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested) BETWEEN 1 AND 9  AND  samples.patientid=patients.AutoID   AND patients.prophylaxis=drug  AND samples.repeatt=0 AND samples.Flag=1 ;     
  ELSE
      SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests
            FROM samples,patients WHERE   samples.result =resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month  AND  samples.patientid=patients.AutoID   AND patients.prophylaxis=drug  AND samples.repeatt=0 AND samples.Flag=1 ;            
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getinfantprophpositivitycountforconfirmatory`$$

CREATE PROCEDURE  `eid_zim`.`Getinfantprophpositivitycountforconfirmatory`
(
IN drug INT,
IN yea INT,
IN month INT,
IN resulttype INT,
OUT numtests INT
)
BEGIN
IF month =0 THEN                  
 SELECT count(DISTINCT(samples.ID)) INTO numtests
            FROM samples,patients WHERE   samples.result =resulttype AND YEAR(samples.datetested)=yea  AND  samples.patientid=patients.AutoID   AND patients.prophylaxis=drug  AND samples.repeatt=0 AND samples.receivedstatus=3 AND          samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND samples.Flag=1  ;   
   
  ELSE
      SELECT count(DISTINCT(samples.ID)) INTO numtests
            FROM samples,patients WHERE   samples.result =resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month  AND  samples.patientid=patients.AutoID   AND patients.prophylaxis=drug  AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND samples.repeatt=0 AND samples.Flag=1 ;            
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`GetNationalResultbyEntrypoint`$$

CREATE PROCEDURE  `eid_zim`.`GetNationalResultbyEntrypoint`
(
IN entrypoint INT,
IN resulttype INT,
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF (month = 0) THEN  
           
     SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers WHERE  samples.result =resulttype AND YEAR(samples.datetested)=yea  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 ;  
  	       
ELSE 

 SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers WHERE  samples.result =resulttype AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested)=month AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 ;  
   
        
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getprovincepmtctcount`$$

CREATE PROCEDURE  `eid_zim`.`Getprovincepmtctcount`
(
IN province INT,
IN yea INT,
IN month INT,
IN drugtype INT,
OUT numsamples INT
)
BEGIN
IF month =0 THEN                  
  SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples FROM samples,facilitys,districts,mothers,patients WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND mothers.art=drugtype  AND samples.repeatt=0 AND YEAR(samples.datetested)=yea;              
  ELSE
  SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples FROM samples,facilitys,districts,mothers,patients WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND mothers.art=drugtype  AND samples.repeatt=0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month;  

              
  END IF; 

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getpartneroutcome`$$
 
CREATE PROCEDURE  `eid_zim`.`Getpartneroutcome`
(
IN yea INT,
IN month INT,
IN resulttype INT,
IN region INT,
IN partner INT,
IN fcode INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN  

 IF region =0 AND fcode >0 THEN          
 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;   
		
 		ELSEIF region >0 AND fcode =0 THEN 
		SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region; 

		
		ELSEIF region >0 AND fcode >0 THEN 
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;   
		
		ELSE  
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;   
    
		END IF;   
	      
  	   
ELSEIF month > 0 THEN  

 IF region =0 AND fcode >0 THEN          
 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;   
		
 		ELSEIF region >0 AND fcode =0 THEN 
		SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region; 

		
		ELSEIF region >0 AND fcode >0 THEN 
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea   AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;   
		
		ELSE  
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;   
    
		END IF;  
END IF; 
END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getpartnertestedsamplescount`$$

CREATE PROCEDURE  `eid_zim`.`Getpartnertestedsamplescount`
(
IN yea INT,
IN month INT,
IN region INT,
IN partner INT,
IN fcode INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN 
         IF region =0 AND fcode >0 THEN          

		 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;
    
 		ELSEIF region >0 AND fcode =0 THEN 
		
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE   samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region; 

		ELSEIF region >0 AND fcode >0 THEN 
		
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;

		ELSE  
		SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys WHERE   samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;
    
		END IF;   
		           
       
ELSEIF month > 0 THEN 

   IF region =0 AND fcode >0 THEN          

		 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;
    
 		ELSEIF region >0 AND fcode =0 THEN 
		
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE   samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region; 

		ELSEIF region >0 AND fcode >0 THEN 
		
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;

		ELSE  
		SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys WHERE   samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;
    
		END IF;   
 
END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getpartneroverallfirsttestedsamples`$$

CREATE PROCEDURE  `eid_zim`.`Getpartneroverallfirsttestedsamples`
(
IN yea INT,
IN month INT,
IN region INT,
IN partner INT,
IN fcode INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN 
 IF region =0 AND fcode >0 THEN          
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0  AND samples.receivedstatus !=3 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;
		
 		ELSEIF region >0 AND fcode =0 THEN 
		
		SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE   samples.result > 0 AND samples.receivedstatus !=3  AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region; 
 		 
		ELSEIF region >0 AND fcode >0 THEN 
		
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0  AND samples.receivedstatus !=3 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;
	
		ELSE  
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys WHERE   samples.result > 0  AND samples.receivedstatus !=3 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;
    
		END IF;   

           
		   
		           
       
ELSEIF month > 0 THEN 

 IF region =0 AND fcode >0 THEN          
		  SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0  AND samples.receivedstatus !=3 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;

		
 		ELSEIF region >0 AND fcode =0 THEN 
		
		SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE   samples.result > 0 AND samples.receivedstatus !=3  AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region; 
 		 
		ELSEIF region >0 AND fcode >0 THEN 
		
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0  AND samples.receivedstatus !=3 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;
		
		ELSE  
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys WHERE   samples.result > 0  AND samples.receivedstatus !=3 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;
    
		END IF;   
 
 
END IF; 
END $$

DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getpartneroverallconfirmatorytestedsamples`$$

CREATE PROCEDURE  `eid_zim`.`Getpartneroverallconfirmatorytestedsamples`
(
IN yea INT,
IN month INT,
IN region INT,
IN partner INT,
IN fcode INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN 

  IF region =0 AND fcode >0 THEN          
SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0  
AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;

		
 		ELSEIF region >0 AND fcode =0 THEN 
		
		SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE   samples.result > 0 
AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region; 
		ELSEIF region >0 AND fcode >0 THEN 
		SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0  
AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;

		
		ELSE  
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys WHERE   samples.result > 0  
AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;

    
		END IF;   
        
		           
       
ELSEIF month > 0 THEN 

 IF region =0 AND fcode >0 THEN          
SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0  
AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;

		
 		ELSEIF region >0 AND fcode =0 THEN 
		
		SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE   samples.result > 0 
AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region; 
		ELSEIF region >0 AND fcode >0 THEN 
		SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result > 0  
AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;

		
		ELSE  
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys WHERE   samples.result > 0  
AND samples.receivedstatus=3 AND samples.reason_for_repeat='Confirmatory PCR at 9 Mths' AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;

    
		END IF;         
 
END IF; 
END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getpartneroverallaverageage`$$

CREATE PROCEDURE  `eid_zim`.`Getpartneroverallaverageage`
(
IN yea INT,
IN month INT,
IN region INT,
IN partner INT,
IN fcode INT,
OUT averageage FLOAT
)
BEGIN
IF month =0 THEN 
 IF region =0 AND fcode >0 THEN          
 SELECT AVG(patients.age) INTO averageage FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.result >0 AND YEAR(samples.datetested)=yea AND samples.Flag=1 AND samples.facility=fcode;
 	
 		ELSEIF region >0 AND fcode =0 THEN 
		
		SELECT AVG(patients.age) INTO averageage FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result >0 AND YEAR(samples.datetested)=yea AND samples.Flag=1 AND samples.facility=facilitys.ID  AND facilitys.district=districts.ID and districts.province=region AND facilitys.partner=partner;   
		ELSEIF region >0 AND fcode >0 THEN 
	 SELECT AVG(patients.age) INTO averageage FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.result >0 AND YEAR(samples.datetested)=yea AND samples.Flag=1 AND samples.facility=fcode;
    
		ELSE  
		 SELECT AVG(patients.age) INTO averageage FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.result >0 AND YEAR(samples.datetested)=yea AND samples.Flag=1 AND samples.facility=facilitys.ID AND facilitys.partner=partner;
    
		END IF;    

    
ELSEIF month > 0 THEN 
IF region =0 AND fcode >0 THEN          
 SELECT AVG(patients.age) INTO averageage FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.result >0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.Flag=1 AND samples.facility=fcode;
 	
 		ELSEIF region >0 AND fcode =0 THEN 
		
		SELECT AVG(patients.age) INTO averageage FROM samples,patients,facilitys,districts WHERE samples.patientid=patients.AutoID AND samples.result >0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.Flag=1 AND samples.facility=facilitys.ID  AND facilitys.district=districts.ID and districts.province=region AND facilitys.partner=partner;   
		ELSEIF region >0 AND fcode >0 THEN 
	 SELECT AVG(patients.age) INTO averageage FROM samples,patients WHERE samples.patientid=patients.AutoID AND samples.result >0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month  AND samples.Flag=1 AND samples.facility=fcode;
    
		ELSE  
		 SELECT AVG(patients.age) INTO averageage FROM samples,patients,facilitys WHERE samples.patientid=patients.AutoID AND samples.result >0 AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested)=month  AND samples.Flag=1 AND samples.facility=facilitys.ID AND facilitys.partner=partner;
    
		END IF;    
         
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getpartnertestedsamplescountlessthan2months`$$

CREATE PROCEDURE  `eid_zim`.`Getpartnertestedsamplescountlessthan2months`
(
IN yea INT,
IN month INT,
IN region INT,
IN partner INT,
IN fcode INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN     
 IF region =0 AND fcode >0 THEN          
  SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2 AND samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =fcode;  
		
 		ELSEIF region >0 AND fcode =0 THEN 
		
SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients,facilitys,districts WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2 AND samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region;  

		ELSEIF region >0 AND fcode >0 THEN 
	 SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2 AND samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =fcode;  

		ELSE  
		
    SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients,facilitys WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2 AND samples.result > 0 AND YEAR(samples.datetested)=yea  AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;  

		END IF;   



 
ELSEIF month > 0 THEN 
 IF region =0 AND fcode >0 THEN          
  SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2 AND samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =fcode;  
		
 		ELSEIF region >0 AND fcode =0 THEN 
		
SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients,facilitys,districts WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2 AND samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region;  

		ELSEIF region >0 AND fcode >0 THEN 
	 SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2 AND samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =fcode;  

		ELSE  
		
    SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients,facilitys WHERE samples.patient =patients.ID AND patients.age BETWEEN 0.1 AND  2 AND samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;  

		END IF;   
       
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getpartnerrejectedsamples`$$

CREATE PROCEDURE  `eid_zim`.`Getpartnerrejectedsamples`
(
IN yea INT,
IN month INT,
IN region INT,
IN partner INT,
IN fcode INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN   
IF region =0 AND fcode >0 THEN          
	
     SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.receivedstatus = 2 AND YEAR(samples.datereceived)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;
		
 		ELSEIF region >0 AND fcode =0 THEN 
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE   samples.receivedstatus = 2 AND YEAR(samples.datereceived)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region;
		
		ELSEIF region >0 AND fcode >0 THEN 
SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.receivedstatus = 2 AND YEAR(samples.datereceived)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;
			
		ELSE  
		
     SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys WHERE   samples.receivedstatus = 2 AND YEAR(samples.datereceived)=yea  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;
		END IF;    
 
ELSEIF month > 0 THEN 
IF region =0 AND fcode >0 THEN          
	
     SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.receivedstatus = 2 AND YEAR(samples.datereceived)=yea AND MONTH(samples.datereceived)=month  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;
		
 		ELSEIF region >0 AND fcode =0 THEN 
		 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE   samples.receivedstatus = 2 AND YEAR(samples.datereceived)=yea AND MONTH(samples.datereceived)=month  AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region;
		
		ELSEIF region >0 AND fcode >0 THEN 
SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.receivedstatus = 2 AND YEAR(samples.datereceived)=yea AND MONTH(samples.datereceived)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =fcode;
			
		ELSE  
		
     SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys WHERE   samples.receivedstatus = 2 AND YEAR(samples.datereceived)=yea AND MONTH(samples.datereceived)=month AND samples.repeatt=0  AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner;
		END IF;    
          
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`GetPartnerResultbyEntrypoint`$$

CREATE PROCEDURE  `eid_zim`.`GetPartnerResultbyEntrypoint`
(
IN entrypoint INT,
IN resulttype INT,
IN yea INT,
IN month INT,
IN region INT,
IN partner INT,
IN fcode INT,
OUT numsamples INT
)
BEGIN
IF (month = 0) THEN  
	IF region =0 AND fcode >0 THEN    
	 SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers WHERE  samples.result =resulttype AND YEAR(samples.datetested)=yea  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =fcode ;  

ELSEIF region >0 AND fcode =0 THEN 
SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE  samples.result =resulttype AND YEAR(samples.datetested)=yea  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region;   
ELSEIF region >0 AND fcode >0 THEN 
SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers WHERE  samples.result =resulttype AND YEAR(samples.datetested)=yea  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =fcode ;  
ELSE 
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys WHERE  samples.result =resulttype AND YEAR(samples.datetested)=yea  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner ;  


	  END IF;  
  	      
ELSEIF month > 0 THEN 
	IF region =0 AND fcode >0 THEN    
	 SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers WHERE  samples.result =resulttype AND YEAR(samples.datetested)=yea  AND  MONTH(samples.datetested)=month AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =fcode ;  

ELSEIF region >0 AND fcode =0 THEN 
SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE  samples.result =resulttype AND YEAR(samples.datetested)=yea  AND  MONTH(samples.datetested)=month  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region;   
ELSEIF region >0 AND fcode >0 THEN 
SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers WHERE  samples.result =resulttype AND YEAR(samples.datetested)=yea AND  MONTH(samples.datetested)=month  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =fcode ;  
ELSE 
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numsamples
            FROM samples,patients,mothers,facilitys WHERE  samples.result =resulttype AND YEAR(samples.datetested)=yea AND  MONTH(samples.datetested)=month  AND samples.patientid=patients.AutoID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND samples.repeatt=0 AND samples.Flag=1 AND samples.facility =facilitys.ID AND facilitys.partner=partner ;  


	  END IF;  
        
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_zim`.`Getpartnerinterventionspositivitycount`$$

CREATE PROCEDURE  `eid_zim`.`Getpartnerinterventionspositivitycount`
(
IN drug INT,
IN yea INT,
IN month INT,
IN resulttype INT,
IN region INT,
IN partner INT,
IN fcode INT,
OUT numtests INT
)
BEGIN
IF (month = 0)  THEN   
	
 IF region =0 AND fcode >0 THEN    
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests from samples,patients,mothers where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND samples.Flag=1 AND mothers.art=drug  AND samples.facility =fcode;
ELSEIF region >0 AND fcode =0 THEN 
SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests from samples,patients,mothers,facilitys,districts where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND samples.Flag=1 AND mothers.art=drug  AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region;   		

ELSEIF region >0 AND fcode >0 THEN 
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests from samples,patients,mothers where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND samples.Flag=1 AND mothers.art=drug  AND samples.facility =fcode;

ELSE  
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests from samples,patients,mothers,facilitys where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND samples.Flag=1 AND mothers.art=drug  AND samples.facility =facilitys.ID AND facilitys.partner=partner;

  END IF;  

ELSEIF month > 0 THEN 
IF region =0 AND fcode >0 THEN    
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests from samples,patients,mothers where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea  AND  MONTH(samples.datetested)=month AND samples.Flag=1 AND mothers.art=drug  AND samples.facility =fcode;
ELSEIF region >0 AND fcode =0 THEN 
SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests from samples,patients,mothers,facilitys,districts where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea  AND  MONTH(samples.datetested)=month  AND samples.Flag=1 AND mothers.art=drug  AND samples.facility =facilitys.ID AND facilitys.partner=partner AND facilitys.district=districts.ID and districts.province=region;   		

ELSEIF region >0 AND fcode >0 THEN 
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests from samples,patients,mothers where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND  MONTH(samples.datetested)=month  AND samples.Flag=1 AND mothers.art=drug  AND samples.facility =fcode;

ELSE  
 SELECT COUNT(DISTINCT(samples.patientid)) INTO numtests from samples,patients,mothers,facilitys where samples.patientid=patients.AutoID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea  AND  MONTH(samples.datetested)=month   AND samples.Flag=1 AND mothers.art=drug  AND samples.facility =facilitys.ID AND facilitys.partner=partner;

  END IF;  
             
 END IF; 

END $$

DELIMITER ;
