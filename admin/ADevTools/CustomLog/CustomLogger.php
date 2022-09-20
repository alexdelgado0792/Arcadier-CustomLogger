<?php


require_once('ArcadierApi.php');

class CustomLogger
{

    protected $sendEmails;
    protected $emailsToBeSend;
    protected $emailfrom;
    protected $emailBody;
    protected $emailSubject;
    private $arcadier;

    function __construct($clientId, $clientSecret)
    {
        $this->arcadier = new ArcadierApi($clientId, $clientSecret);

        $configParams = $this->arcadier->SearchCt('Configuration', 'request body');

        $this->sendEmails = $configParams["SendEmail"];
        $this->emailsToBeSend = $configParams["Emails"];
        $this->emailBody = $configParams["EmailBody"];
        $this->emailfrom = $configParams["EmailFrom"];
        $this->emailSubject = $configParams["EmailSubject"];
    }

    function Log()
    {
        //here send email 
    }

    function SendEmails()
    {
        //Execute only if flag is activated
        if(boolval($this->sendEmails))
        {
            foreach ($this->GetEmails() as $email) {
                $this->arcadier->SendEmail($this->emailfrom, $email, $this->emailBody, $this->emailSubject);
            }
        }
    }

    function GetEmails()
    {
        return explode(",", $this->emailsToBeSend);
    }


}

?>