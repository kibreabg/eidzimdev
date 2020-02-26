<?php
include("../nationaldashboardfunctions.php");
$year=$_GET['mwaka'];
$province=$_GET['province'];
$twoless = $year-3;
//overall tested samples

?>
<chart palette='2' caption=''  showValues='0' decimals='0' formatNumberScale='0' useRoundEdges='1' divLineColor="FCB541" divLineAlpha="50" canvasBorderColor="666666" baseFontColor="666666" lineColor="FCB541" bgColor='#FFFFFF' showBorder='0' >
					  
<?php
	for ($year; $year>=$twoless; $year--)
  						{
						
						$samples=Getalltestedprovincesamplescount($province,$year,0) 

	?>
	 <set label="<?php echo $year; ?>" value="<?php echo $samples; ?>" />
	<?php
	}
	?>
	<styles>
		<definition>
			<style name='Anim1' type='animation' param='_xscale' start='0' duration='1' />
			<style name='Anim2' type='animation' param='_alpha' start='0' duration='0.6' />
			<style name='DataShadow' type='Shadow' alpha='40'/>
		</definition>
		<application>
			<apply toObject='DIVLINES' styles='Anim1' />
			<apply toObject='HGRID' styles='Anim2' />
			<apply toObject='DATALABELS' styles='DataShadow,Anim2' />
	</application>	
	</styles>

</chart> 
