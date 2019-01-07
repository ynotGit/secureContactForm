<?php
    //Message Variables
    $popupMsg = '';
    $popupMsgClass = '';

    //Submit Check
  if(filter_has_var(INPUT_POST, 'submit')) {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $number = htmlspecialchars($_POST['number']);
    $company = htmlspecialchars($_POST['company']);
    $message = htmlspecialchars($_POST['message']);

    //Captcha
    $secretKey = 'YOUR-SECRET-KEY';
    $responseKey = $_POST['g-recaptcha-response'];
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$responseKey';
    $response = file_get_contents($url);
    $response = json_decode($response);

    //Required Fields Check
    if(!empty($name) && !empty($email) && !empty($message) && !empty($responseKey)) {
      if(filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
          $popupMsg = 'Please use a valid eMail*';
          $popupMsgClass = 'form-error';
      } else {
        $toEmail = 'RECIPIENT-EMAIL';
        $subject = 'Form submitted by ' .$name;
        $body = '<h2>Form Submission</h2>
                <h4>Name</h4><p>' .$name. '</p>
                <h4>eMail</h4><p>' .$email. '</p>
                <h4>Number</h4><p>' .$number. '</p>
                <h4>Company</h4><p>' .$company. '</p>
                <h4>Message</h4><p>' .$message. '</p>';
        $headers = 'MIME-Version: 1:0' . "\r\n";
        $headers .= 'Content-Type:text/html; charset=UTF-8' ."\r\n";
        $headers .= 'From: ' .$name. '<'.$email.'>'. "\r\n";

        if(mail($toEmail, $subject, $body, $headers)) {
          $popupMsg = 'Message Sent';
          $popupMsgClass = 'form-success';
          $_POST = array(); 
        } else {
          $popupMsg = 'Message NOT Sent';
          $popupMsgClass = 'form-error';
        }
      }
    } else {
        $popupMsg = 'Please fill in all *Required fields & reCaptcha';
        $popupMsgClass = 'form-error';
    }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
<title>Contact Form - PHP, Materialize, SASS & reCaptcha</title>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <!-- Transpile to CSS or manually convert SCSS file to CSS-->
  <link href="main.scss" rel="stylesheet" type="text/css">
  <!--Materialize-->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.5/css/materialize.min.css">
  <!--Materialize Icons-->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <!--Font Awesome Icons / Only use is Envelope Icon at the top of the contact form-->
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU"
    crossorigin="anonymous">
</head>

<body>

<section class="contact container">
    <div class="row">
      <form method="post" action="#contact" class="col s12">
        <hr class="header-line col s4">
        <i class="fa fa-envelope col s4"></i>
        <hr class="header-line col s4">
        </h4>
        <div class="row">
          <div class="input-field col s6">
            <i class="material-icons prefix">account_circle</i>
            <input name="name" id="name" type="text" class="validate" 
              value="<?php echo isset($_POST['name']) ? $name : '';?>">
            <label for="name" data-error="*Required">Name*</label>
          </div>
          <div class="input-field col s6">
            <i class="material-icons prefix">mail_outline</i>
            <input name="email" id="email" type="email" class="validate" 
              value="<?php echo isset($_POST['email']) ? $email : '';?>">
            <label for="email" data-error="*Required">eMail*</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s6">
            <i class="material-icons prefix">phone_iphone</i>
            <input name="number" id="number" type="number" class="validate"
              value="<?php echo isset($_POST['number']) ? $number : '';?>">
            <label for="number">Number</label>
          </div>
          <div class="input-field col s6">
            <i class="material-icons prefix">business</i>
            <input name="company" id="company" type="text" class="validate"
            value="<?php echo isset($_POST['company']) ? $company : '';?>">
            <label for="company">Company</label>
          </div>
        </div>
        <div class="input-field">
          <i class="material-icons prefix">chat_bubble</i>
          <textarea name="message" id="textarea" class="materialize-textarea"
            value="<?php echo isset($_POST['message']) ? $message : '';?>"></textarea>
          <label for="textarea" data-error="*Required">Message*</label>
        </div>
        <?php if($popupMsg != ''): ?>
          <div class="<?php echo $popupMsgClass?>"><?php echo $popupMsg?></div>
        <?php endif; ?>
        <p>* Required</p>
        <div class="g-recaptcha" data-sitekey="YOUR-DATA-SITE-KEY"></div>
        <button type="submit" name="submit" class="waves-effect waves-light btn">Send</button>
      </form>
    </div>
  </section>

  <!-- Materialize jS-->
  <script defer src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  <script defer src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  </body>