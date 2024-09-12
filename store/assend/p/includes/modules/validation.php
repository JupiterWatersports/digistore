<div class="g-recaptcha" id="rcaptcha" data-sitekey="6LcfLg4TAAAAAJJwCtP3bHW3n2iXVFRtqPPRE0zU"></div>
<span id="captcha" style="color:red"></span>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
var v = grecaptcha.getResponse();
    if(v.length == 0)
    {
        document.getElementById('captcha').innerHTML="You can't leave Captcha Code empty";
      
    }
    // validation was successful

</script>