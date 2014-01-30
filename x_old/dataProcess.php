
<?php	    							

// Published at: scripts.tropicalpcsolutions.com
// ENTER YOUR INFORMATION BELOW


$maxSize="500";                            // Maximum size of the letters message
$subject="visitor comments";               // The subject line of the letters that you receive
$to="linseyburritt@gmail.com";                     // The email address you want letters sent to
$HTMLmailFormat="1";                       // Do you want to use HTML mail (1 for yes and 0 for no)
$verify_referrer="1";	                   // Do you want to do domain checking (1 for yes and 0 for no)
$domain="http://burrittconsulting.com";               // Enter your domain here if you want to verify it
$domainAlias="http://burrittconsulting.com";      // Enter your domains alias here if you want to verify it
$ipLogging="1";                            // Do you want to do IP logging (1 for yes and 0 for no)
$notify="1";                               // Do you want to be notified when an IP is logged  (1 or 0)
$notifyFrom="Abuse@yoursite.com";          // What do you want the notifications 'From' field to say
$notifySubject="Form abuse notification";  // What do you want the notifications 'Subject' line to say



//////////////////////    NO EDITING BEYOND THIS POINT
////////////////////// unless you know what you are doing!


// Below code may or may not be necessary for you
$name = $_POST['name']; $from = $_POST['from']; $message = $_POST['message'];

// Set IP variable based on registar globals status
$register_globals = (bool) ini_get('register_gobals');
if ($register_globals) { $ip=getenv(REMOTE_ADDR); } 
else $ip=$_SERVER['REMOTE_ADDR'];

if ($register_globals) { $ref=getenv(HTTP_REFERER); } 
else $ref=$_SERVER['HTTP_REFERER'];


// If webmaster wants to do domain checks
if($verify_referrer=="1")
{
        // If the domain referrer DOESN'T match either the set domain or domainAlias variable
	if(!eregi("$domain", $ref) && !eregi("$domainAlias", $ref)) 
        {
                $error=1;

                // If webmaster wants to log 3rd party domain attempts
                if($ipLogging=="1"){ 
		 	$date=date ("l dS of F Y h:i:s A");
			$ipLog="ipLog.htm";
			$fp=fopen("$ipLog", "a+");
			fputs($fp, "<font face=arial size=3>  >>> Logged IP address: $ip - Date: $date<br>");
			fclose($fp);

			$errorMesB="ERROR: Invalid domain.<br><br><b>NOTICE:</b> Your IP has been logged as: $ip."; $error=1;
		}
		else{ $errorMesA="ERROR: Invalid domain."; $error=1; }

                // If webmaster wants to be notified via email of 3rd party domain attempts
		if($notify=="1"){
       			$subject=$notifySubject;

                        // If webmaster wants mail sent in HTML format
        		if($HTMLmailFormat=="1"){
				$body=" <font face=arial size=3><br>
        				--------<font color=red>WARNING!</font><font face=arial size=3> Form abuse notification ------
        				<br><br><br><font face=arial size=2>A person has attempted to abuse the contact form.
        				<br><font face=arial size=2>Their IP address was logged as: $ip <br></font><br>";
                        }
                        // If no HTML then send as plain text
                        else{
                              	$body=" \n--------WARNING! Form abuse notification ------\n\n\n
        				A person has attempted to abuse the contact form.\n
        				Their IP address was logged as: $ip \n";
                        }
			$from=$notifyFrom;

                        // Set headers based on content type (plain / HTML)
			if($HTMLmailFormat=="1") $headers="Content-Type: text/html; charset=windows-1252 \n";
                        else $headers="Content-Type: text/plain \n";
			$headers.="From: $from \n";
			$headers.="X-mailer: \"contact\" published at tropicalpcsolutions.com \n";

                        // Mail notice to webmaster
			mail($to,$subject,$body,$headers);

                        $errorMesC="An email with this information has been sent to the webmaster."; $error=1;
		}
   	} 
}

// Trim whitespace from user input and replace potentially harmfull charchters
$name=trim($name); $name = preg_replace("/>/","]",$name); $name = preg_replace("/</","[",$name);

// If user enters NO name
if($name==""){ $errorMes1="ERROR: You didn't write your name. "; $error=1; }

// Trim whitespace from user input 
$message=trim($message); if($message==""){ $errorMes2="ERROR: You didn't write a message. "; $error=1; }

// Determine the length of the message
//elseif (strlen($message) >= $maxSize) { $errorMes3="ERROR: Your message is too long. The maximum characters allowed is $maxSize. "; $error=1; }

// If all is well so far there are no errors
else
	$error=0;

// If there IS data in the email field then check it
if ($from!==""){

        // Check email address for certain charcters
	if (!eregi("^.+@.+\\..+$", $from)) 
	{ 
		$errorMes4="ERROR: Your email address contains errors. "; $error=1; 
	}

        // If email address pass check then trim whitespace
        else
		$from=trim($from);
}
else { $errorMes5="ERROR: You need to enter an email address. "; $error=1; }

// If there has been an error then display the error
if ($error=="1"){
	echo ("<title>SendMail Error</title>
        <body><br>
	<p style=\"font:11pt arial\">SendMail <font color=red> Error</font>
	<br><br>The following errors have occured:<br><br>
	$errorMes1<br>$errorMes2<br>$errorMes3<br>$errorMes4<br>$errorMes5<br>$errorMesA<br>$errorMesB<br>$errorMesC<br>
	<br><a href=\"contact.html\" style=\"color:black\">Click here</a> to try again. </body></html>"); exit(0);
}

// If there has been no error then send mail
else if ($error=="0"){

        // If webmaster wants mail sent in HTML format
        if($HTMLmailFormat=="1") {
		$message = preg_replace("/>/","&gt;",$message); $message = preg_replace("/</","&lt;",$message);

		$body="<font face=arial size=2>$message</font <br><br><br>
                       <font face=\"ms sans serif\" size=2>
                       --------------- SENDER INFORMATION ------------
               	       <br>This message was sent to you by $name.<br>
               	       $name's email address is: $from<br>
               	       $name's IP address is: $ip </font><br>";
        }
       
        // If webmaster wants mail sent in plain text format
        else{
		$body="$message\n\n\n
                       --------------- SENDER INFORMATION ------------
		       \nThis message was sent to you by $name.\n
               	       $name's email address is: $from\n
               	       $name's IP address is: $ip \n";
        }
	$from="\"$name\" <$from>";

        // Set headers based on content type (plain / HTML)
        if($HTMLmailFormat=="1") $headers="Content-Type: text/html; charset=windows-1252\n";
        else $headers="Content-Type: text/plain \n";
	$headers.="From: $from \n";
        $headers.="X-mailer: \"contact\" published at tropicalpcsolutions.com \n";

        // Send mail
	if(!mail($to,$subject,$body,$headers)){
		echo "mail error";
	}
        // display mail sent message
	else {
        echo (" <title>SendMail Notice: mail was successfully sent</title><body><br><br><br><br>
		<p style=\"font:11pt arial\" align=center>Your mail has been successfully sent...<i>Thank you</i></p>
		</body></html>"); exit(0);
	}

// exit script
} exit(0);
?>
