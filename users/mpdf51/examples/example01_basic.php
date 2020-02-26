<?php
$d='tim';

$html = '
 <table>
   <TR>
   
   <TD COLSPAN="3">HIV DNA-PCR LABORATORY RESULT FORM</TD></TR>
		  
          <tr>
            <td width="171"> Referring Clinic / Hospital Name </td>
            <td colspan="3"><div>	echo $d </td>
				
          </tr>
		   <tr>
            <td width="171"> Province</td>
            <td colspan="3"><div>	<?php 
				?></td>
				
          </tr>
		   <tr>
            <td width="171">District</td>
            <td colspan="3"><div>	<?php 
				?></td>
				
          </tr>
         
          </table>
		  <table>
		  <tr>
            <td colspan="4" class="subsection-title">Mother Information</td>
          </tr>
		  <tr>
		  <td>Name of Mother</td>
		  <td><?php 
				?></td>
		  <td>Mothers ANC </td>
		  <td>
		  </td>
		  </tr>
          <tr>
		  	 <td> HIV Status </td>
            <td><div><?php
	  
	  ?>   <span id="mhivstatusInfo"></span></div>      </td>
            
	  <td> Entry Point</td>
            <td><?php
	   
	  ?></td>
          </tr>
		  <tr>	
		  <td>Which ARV did the mother receive?</td>
		<td>
		<?php
			
		?></td>	  	  
	  <td> Infant Feeding</td>
	      <td><?php
	  	  ?><span id="mbfeedingInfo"></span></td>
		  </tr>        
	
		  <tr>
            <td class="subsection-title" colspan="4">Infant Information</td>
          </tr>
          <tr>
		  	
            <td> Request No</td>
            <td>
			<strong>Year</strong>&nbsp;<?php 
				?>&nbsp;
			<strong>No</strong>&nbsp;<?php 
				?>		</td>
         	<td>Infants Name </td>
		  	<td><?php 
				?></td>
          </tr>
          <tr>
		  	<td> Date of Birth </td>
			<td><!--CALENDAR--><!--END CALENDAR-->	  <?php 
				?></td>
			 <td> Sex of baby </td>
             <td colspan=""><?php 
				?>
             </td>
          </tr>
		  <tr>
		  
			  <td> Infant Prophylaxis </td>
            <td colspan=""><div><?php
	   	  ?><span id="infantprophylaxisInfo"></span></div></td>
	  <td>Infant already on CTX prophylaxis? </td>
	  <td>
	 <?php 
				?>	  </td>
	  </tr>
	  <tr>
	   <td> Mode of Delivery </td>
            <td colspan=""><?php
		  ?><span id="infantprophylaxisInfo"></span></td>
	  </tr>
	  <tr>
            <td class="subsection-title" colspan="4">Infant Testing <em><small>(Check Child Health Card)</small></em></td>
          </tr>
	  <tr>
	  <td>Was Infant Tested for HIV before?</td>
	  <td>
	 <?php 
				?>  </td>
	  <td></td>
	  <td>
	  <div><?php
	  
	  ?> </div>	  </td>
		  </tr>
		  <tr>
		   <td>If yes, what type of test was it? </td>
	       <td><?php 
				?>
	        </td>
	  
	   <td>If DNA PCR, give original patient Lab Request No </td>
	   <td><strong>Year</strong>&nbsp;
	    <?php 
				?>
	     &nbsp; <strong>No</strong>&nbsp;
	   <?php 
				?></td>
		  </tr>
          <tr>
            <td class="subsection-title" colspan="4">Sample Information</td>
          </tr>
          <tr>
            <td> Date of taking DBS </td>
            <td><?php 
				?>	 </td>
			 <td> Date Received </td>
            <td><?php 
				?>
	  		<!--end calendar-->	  		</td>
          </tr>
		   <tr>
                    
            
			 <td> Reason for DNA/PCR Test </td>
             <td ><?php
	  		 	  	
	  ?></td>
          </tr>
		  
		    <tr>
            <td class="subsection-title" colspan="4">Laboratory Report</td>
          </tr>
          <tr>
            <td> Date Specimen Received </td>
            <td><?php 
				?>	 </td>
			 <td> DNA PCR Result  </td>
            <td><?php 
				?>
	  		<!--end calendar-->	  		</td>
          </tr>
		   <tr>
                    
            
			 <td> Date of Result </td>
             <td ><?php
	  		 	  	
	  ?></td>
	  <td> Lab Ref # </td>
             <td ><?php
	  		 	  	
	  ?></td>
          </tr>
		  <tr>
                    
            
			 <td colspan="2"> Comments </td>
             <td ><?php
	  		 	  	
	  ?></td>

          </tr>
          
	</table>

';


//==============================================================
//==============================================================
//==============================================================

include("../mpdf.php");

$mpdf=new mPDF(); 

$mpdf->WriteHTML($html);
$mpdf->Output();
exit;

//==============================================================
//==============================================================
//==============================================================


?>