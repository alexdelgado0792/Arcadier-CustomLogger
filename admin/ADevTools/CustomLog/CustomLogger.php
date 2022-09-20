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

        $configParams = $this->arcadier->GetAllCtContent('Configuration');

        if ($configParams["TotalRecords"] == 0) {
            throw new Exception("At least one configuration must exists. Current is " . $configParams["TotalRecords"]);
        }

        if ($configParams["TotalRecords"] > 1) {
            throw new Exception("Just one configuration must exists. Current is " . $configParams["TotalRecords"]);
        }

        if ($configParams["TotalRecords"] == 1) {
            $this->sendEmails = $configParams["Records"]["SendEmail"];
            $this->emailsToBeSend = $configParams["Records"]["Emails"];
            $this->emailBody = $configParams["Records"]["EmailBody"];
            $this->emailfrom = $configParams["Records"]["EmailFrom"];
            $this->emailSubject = $configParams["Records"]["EmailSubject"];
        }
        else {
            throw new Exception("Some error occur but is an unknown error.");
        }

    }

    function Log()
    {
    //here send email 
    }

    function SendEmails()
    {
        //Execute only if flag is activated
        if (boolval($this->sendEmails)) {
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