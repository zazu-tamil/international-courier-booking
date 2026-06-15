<?php
 if(isset($_POST['source']) && isset($_POST['destination']))
 { 	
    
		/* recipients */
 	$to = "Courier Syndicate Gmail <couriersyndicate@gmail.com>"." , " ;
 	//$to = "selvanramesh <selvanramesh@gmail.com>"."" ;
	$to .= "Courier Syndicate <support@couriersyndicate.co.in>"." , " ; 
	
	/* subject */
	$subject = "Courier Pickup Request - Courier Syndicate";
	
	/* message */
	$message = ' 
	<table border="0" cellpadding="2" style="border:1px solid #FF9900;background-color:#FFF7DD;" cellspacing="0" >	   
	  <tr>
		<th align="center" colspan="2" style="border-bottom:1px solid #FF9900"><h4>Courier Pickup Request </h4></th>
	  </tr>
	  <tr>
		<td width="50%" style="text-align:right;padding-right:5px;">Source Pincode :</td>
		<td>'.  $_POST['source']  .'</td>
	  </tr>
	  <tr>
		<td width="50%" style="text-align:right;padding-right:5px;">Destination Pincode :</td>
		<td>'.  $_POST['destination']  .'</td>
	  </tr>
	  <tr>
		<td style="text-align:right;padding-right:5px;">Package Type :</td>
		<td>'.  $_POST['package_type'].'</td>
	  </tr>
	  <tr>
		<td style="text-align:right;padding-right:5px;">Weight :</td>
		<td>'. $_POST['weight'].'</td>
	  </tr>
	  <tr>
		<td style="text-align:right;padding-right:5px;">Contact Name :</td>
		<td>'. $_POST['contact_name'].'</td>
	  </tr> 
	   <tr>
		<td style="text-align:right;padding-right:5px;">Mobile :</td>
		<td>'. $_POST['mobile'].'</td>
	  </tr>
      <tr>
		<td style="text-align:right;padding-right:5px;">Description :</td>
		<td>'. $_POST['description'].'</td>
	  </tr>
	</table>
	</body>
	</html>
	';
	
	//echo $message;
	//exit;
	
	/* To send HTML mail, you can set the Content-type header. */
	$headers  = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	
	/* additional headers */
	$headers .= "To: ". $to ."\r\n"; 
    
	$headers .= "From: Info-Courier Syndicate<info@couriersyndicate.co.in>\r\n";
	 
	//$headers .= "cc: crinfotechcbe@gmail.com\r\n";
	$headers .= "Bcc: crinfotechcbe@gmail.com\r\n";
	
	/* and now mail it */
	$flg = mail($to, $subject, $message, $headers);	
	if($flg)
	{
	   echo "PickUp Request Send";
	}
	else
	{
	   echo "PickUp Request Not Send. Try Again Later";
	} 
     
 }
 

?>