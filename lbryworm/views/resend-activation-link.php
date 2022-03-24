<div class="lbryworm_box" style="text-align:center;">
    
    <h2>Resend activation link</h2>
    
    <?php

    if($sent){
    ?>
    Email with activaton link sent!
    <?
    }else{
    ?>

    <form method="post" class="resend-activation">
        <div>
            <input type="text" name="email" placeholder="Enter your email"><br/><br/>
            <button type="submit" name="resendActivationLink" value="resendActivationLink">Resend activation link</button>
        </div>
    </form>

    <?
    }

    ?>


</div>
