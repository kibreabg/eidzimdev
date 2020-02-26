<?php
 $to = "smutheu@gmail.com";
 $subject = "Hi!";
 $from="tngugi@gmail.com";
 $body = "Hi,\n\nHow are you?";
 $headers = "From: $from";
 if (mail($to, $subject, $body, $headers)) {
   echo("<p>Message successfully sent!</p>");
  } else {
   echo("<p>Message delivery failed...</p>");
  }
 ?>