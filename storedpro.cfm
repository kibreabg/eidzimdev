
DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getallprovincesamplescount`$$

CREATE PROCEDURE  `eid_kemri2`.`Getallprovincesamplescount`
(
IN province INT,
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0  THEN 
	SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province  AND  YEAR(samples.datereceived)=yea AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;                  
  ELSEIF month =13 THEN  
	  SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND  MONTH(samples.datereceived)BETWEEN 1 AND 9  AND  YEAR(samples.datereceived)=yea AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;                      
  ELSE
    SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND  MONTH(samples.datereceived)=month AND  YEAR(samples.datereceived)=yea AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Gettestedsamplescountperprovince`$$

CREATE PROCEDURE  `eid_kemri2`.`Gettestedsamplescountperprovince`
(
IN province INT,
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
       SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts  WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province   AND YEAR(samples.datetested)=yea AND samples.result >0 AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;            
 ELSEIF month =13 THEN  
  SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts  WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND MONTH(samples.datetested)BETWEEN 1 AND 9  AND YEAR(samples.datetested)=yea AND samples.result >0 AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  ELSE

 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts  WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND MONTH(samples.datetested)=month  AND YEAR(samples.datetested)=yea AND samples.result >0 AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getprovinceresultcount`$$

CREATE PROCEDURE  `eid_kemri2`.`Getprovinceresultcount`
(
IN province INT,
IN yea INT,
IN month INT,
IN resulttype INT,
OUT numsamples INT
)
BEGIN
IF month =0 THEN                  
  SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result=resulttype AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND YEAR(samples.datetested)=yea;              
 ELSEIF month =13 THEN
    SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result=resulttype AND ((samples.parentid=0) OR (samples.parentid IS NULL))AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND YEAR(samples.datetested)=yea ;   
  ELSE
   SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result=resulttype AND ((samples.parentid=0) OR (samples.parentid IS NULL))AND MONTH(samples.datetested)=month AND YEAR(samples.datetested)=yea ; 
            
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getprovincerejectedsamples`$$

CREATE PROCEDURE  `eid_kemri2`.`Getprovincerejectedsamples`
(
IN province INT,
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month =0 THEN 
 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.receivedstatus=2 AND YEAR(samples.datereceived)=yea AND samples.Flag=1;                    
  ELSEIF month =13 THEN   
   SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.receivedstatus=2 AND MONTH(samples.datereceived)BETWEEN 1 AND 9 AND YEAR(samples.datereceived)=yea AND samples.Flag=1 ;               
  ELSE
 SELECT COUNT(samples.ID) INTO numsamples FROM samples,facilitys,districts WHERE samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.receivedstatus=2 AND MONTH(samples.datereceived)=month AND YEAR(samples.datereceived)=yea AND samples.Flag=1 ;           
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getprovincialaverageage`$$

CREATE PROCEDURE  `eid_kemri2`.`Getprovincialaverageage`
(
IN province INT,
IN yea INT,
IN month INT,
OUT averageage FLOAT
)
BEGIN
IF month =0 THEN                  
      SELECT AVG(patients.age) INTO averageage FROM samples,patients,facilitys,districts WHERE samples.patient=patients.ID AND samples.result >0   AND YEAR(samples.datetested)=yea AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.Flag=1;     
   ELSEIF month =13 THEN 
      SELECT AVG(patients.age) INTO averageage FROM samples,patients,facilitys,districts WHERE samples.patient=patients.ID AND samples.result >0  AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND YEAR(samples.datetested)=yea AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.Flag=1 ;    
  ELSE
     SELECT AVG(patients.age) INTO averageage FROM samples,patients,facilitys,districts WHERE samples.patient=patients.ID AND samples.result >0  AND MONTH(samples.datetested)=month AND YEAR(samples.datetested)=yea AND samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.Flag=1 ;    
           
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getoverallaverageage`$$

CREATE PROCEDURE  `eid_kemri2`.`Getoverallaverageage`
(
IN yea INT,
IN month INT,
OUT averageage FLOAT
)
BEGIN
IF month =0 THEN                  
  SELECT AVG(patients.age) INTO averageage FROM samples,patients WHERE samples.patient=patients.ID AND samples.result >0 AND YEAR(samples.datetested)=yea AND samples.Flag=1;       
 ELSEIF month =13 THEN 
      SELECT AVG(patients.age) INTO averageage FROM samples,patients WHERE samples.patient=patients.ID AND samples.result >0  AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND YEAR(samples.datetested)=yea AND samples.Flag=1;            
 ELSE
 	 SELECT AVG(patients.age) INTO averageage FROM samples,patients WHERE samples.patient=patients.ID AND samples.result >0  AND MONTH(samples.datetested)=month AND YEAR(samples.datetested)=yea AND samples.Flag=1;            
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Gettestedsamplescount`$$

CREATE PROCEDURE  `eid_kemri2`.`Gettestedsamplescount`
(
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
      SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea  AND ((samples.parentid=0) OR (samples.parentid IS NULL))  AND samples.Flag=1;            
  ELSEIF month =13 THEN 
  SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND ((samples.parentid=0) OR (samples.parentid IS NULL))  AND samples.Flag=1;          
  ELSE
 SELECT COUNT(samples.ID) INTO numsamples  FROM samples WHERE samples.result > 0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND ((samples.parentid=0) OR (samples.parentid IS NULL))  AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Gettestedsamplescountlessthan2months`$$

CREATE PROCEDURE  `eid_kemri2`.`Gettestedsamplescountlessthan2months`
(
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
      SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients WHERE samples.patient =patients.ID AND patients.age < 2 AND samples.result > 0 AND YEAR(samples.datetested)=yea  AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;            
   ELSEIF month =13 THEN 
    SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients WHERE samples.patient =patients.ID AND patients.age < 2 AND samples.result > 0 AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  ELSE

 SELECT  count(DISTINCT(samples.ID)) INTO numsamples FROM samples,patients WHERE samples.patient =patients.ID AND patients.age < 2 AND samples.result > 0 AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested)=month AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;


DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getnationaloutcome`$$

CREATE PROCEDURE  `eid_kemri2`.`Getnationaloutcome`
(
IN yea INT,
IN month INT,
IN resulttype INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
    SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea  AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;           
  ELSEIF month =13 THEN 
     
 SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea   AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  ELSE

SELECT COUNT(samples.ID) INTO numsamples FROM samples WHERE   samples.result = resulttype AND YEAR(samples.datetested)=yea   AND MONTH(samples.datetested)=month AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getnationalrejectedsamples`$$

CREATE PROCEDURE  `eid_kemri2`.`Getnationalrejectedsamples`
(
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
   SELECT COUNT(ID) INTO numsamples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)=yea  AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;           
   
 ELSEIF month =13 THEN
   SELECT COUNT(ID) INTO numsamples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)=yea AND MONTH(samples.datereceived) BETWEEN 1 AND 9 AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  ELSE
 SELECT COUNT(ID) INTO numsamples FROM samples WHERE receivedstatus = 2 AND YEAR(samples.datereceived)=yea AND MONTH(samples.datereceived)=month AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getinterventionspositivitycount`$$

CREATE PROCEDURE  `eid_kemri2`.`Getinterventionspositivitycount`
(
IN drug INT,
IN yea INT,
IN month INT,
IN resulttype INT,
OUT numtests INT
)
BEGIN
IF month =0 THEN    
SELECT COUNT(DISTINCT(samples.ID)) INTO numtests from samples,patients,mothers where samples.patient=patients.ID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND samples.Flag=1 AND mothers.prophylaxis=drug;
ELSEIF month =13 THEN         
SELECT COUNT(DISTINCT(samples.ID)) INTO numtests from samples,patients,mothers where samples.patient=patients.ID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND samples.Flag=1 AND mothers.prophylaxis=drug;   
ELSE 
SELECT COUNT(DISTINCT(samples.ID)) INTO numtests from samples,patients,mothers where samples.patient=patients.ID AND patients.mother=mothers.ID AND samples.result=resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.Flag=1 AND mothers.prophylaxis=drug;
             
 END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getnooftestspermonth`$$

CREATE PROCEDURE  `eid_kemri2`.`Getnooftestspermonth`
(
IN province INT,
IN yea INT,
IN month INT,
OUT numtests INT
)
BEGIN

SELECT COUNT(samples.ID) INTO numtests
              FROM samples,districts,facilitys
			  WHERE   samples.result >0 AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND  samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getnooftestsperyear`$$

CREATE PROCEDURE  `eid_kemri2`.`Getnooftestsperyear`
(
IN province INT,
IN yea INT,
OUT numtests INT
)
BEGIN

SELECT COUNT(samples.ID) INTO numtests
              FROM samples,districts,facilitys
			  WHERE   samples.result >0 AND YEAR(samples.datetested)=yea  AND  samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  

END $$

DELIMITER ;

DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getprovincepositivitycount`$$

CREATE PROCEDURE  `eid_kemri2`.`Getprovincepositivitycount`
(
IN province INT,
IN resulttype INT,
IN yea INT,
OUT numtests INT
)
BEGIN

SELECT COUNT(samples.ID) INTO numtests
            FROM samples,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province  AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1 ;          
  

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Gettestedsamplescountpermonth`$$

CREATE PROCEDURE  `eid_kemri2`.`Gettestedsamplescountpermonth`
(
IN yea INT,
IN month INT,
OUT numtests INT
)
BEGIN

SELECT COUNT(ID) INTO numtests  FROM samples WHERE result > 0  AND  YEAR(datetested)=yea AND MONTH(datetested)=month AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;    

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getprovinceinterventionspositivitycount`$$

CREATE PROCEDURE  `eid_kemri2`.`Getprovinceinterventionspositivitycount`
(
IN province INT,
IN drug INT,
IN resulttype INT,
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
      SELECT  count(DISTINCT(samples.ID)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND samples.patient=patients.ID  AND patients.mother=mothers.ID AND mothers.prophylaxis=drug AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;            
  ELSEIF  month = 13 THEN 
     SELECT  count(DISTINCT(samples.ID)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result =resulttype AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested)BETWEEN 1 AND 9 AND samples.patient=patients.ID  AND patients.mother=mothers.ID AND mothers.prophylaxis=drug AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          

  ELSE
   SELECT  count(DISTINCT(samples.ID)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result =resulttype AND YEAR(samples.datetested)=yea  AND MONTH(samples.datetested)=month AND samples.patient=patients.ID  AND patients.mother=mothers.ID AND mothers.prophylaxis=drug AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1;          
  END IF; 

END $$

DELIMITER ;



DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getprovinceentrypositivitycount`$$

CREATE PROCEDURE  `eid_kemri2`.`Getprovinceentrypositivitycount`
(
IN province INT,
IN entrypoint INT,
IN resulttype INT,
IN yea INT,
IN month INT,
OUT numsamples INT
)
BEGIN
IF month = 0 THEN                  
     SELECT count(DISTINCT(samples.ID)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result =resulttype AND YEAR(samples.datetested)=yea  AND samples.patient=patients.ID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1 ;            
    ELSEIF  month = 13 THEN
   SELECT count(DISTINCT(samples.ID)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested) BETWEEN 1 AND 9 AND samples.patient=patients.ID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1 ;          

  ELSE
   SELECT count(DISTINCT(samples.ID)) INTO numsamples
            FROM samples,patients,mothers,facilitys,districts WHERE   samples.facility=facilitys.ID AND facilitys.district=districts.ID AND districts.province=province AND samples.result =resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month AND samples.patient=patients.ID  AND patients.mother=mothers.ID AND mothers.entry_point=entrypoint AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1 ;          
  END IF; 

END $$

DELIMITER ;




DELIMITER $$
DROP PROCEDURE IF EXISTS `eid_kemri2`.`Getinfantprophpositivitycount`$$

CREATE PROCEDURE  `eid_kemri2`.`Getinfantprophpositivitycount`
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
            FROM samples,patients WHERE   samples.result =resulttype AND YEAR(samples.datetested)=yea  AND  samples.patient=patients.ID   AND patients.prophylaxis=drug  AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1  ;   
  ELSEIF  month = 13 THEN
  SELECT count(DISTINCT(samples.ID)) INTO numtests
            FROM samples,patients WHERE   samples.result =resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested) BETWEEN 1 AND 9  AND  samples.patient=patients.ID   AND patients.prophylaxis=drug  AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1 ;     
  ELSE
      SELECT count(DISTINCT(samples.ID)) INTO numtests
            FROM samples,patients WHERE   samples.result =resulttype AND YEAR(samples.datetested)=yea AND MONTH(samples.datetested)=month  AND  samples.patient=patients.ID   AND patients.prophylaxis=drug  AND ((samples.parentid=0) OR (samples.parentid IS NULL)) AND samples.Flag=1 ;            
  END IF; 

END $$

DELIMITER ;
