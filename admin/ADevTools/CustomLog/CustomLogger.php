<?php


require_once('ArcadierApi.php');

class CustomLogger
{

    protected $sendEmails;
    protected $emailsToBeSend;
    protected $emailfrom;
    protected $emailSubject;
    private $arcadier;

    function __construct($clientId, $clientSecret)
    {
        $this->arcadier = new ArcadierApi($clientId, $clientSecret);
    }

    function Log($payloadToLog, $emailBody= null)
    {
        //here send email 
    }

    function SendEmails($emailBody= null)
    {
        $this->GetEmailParams();

        //Execute only if flag is activated
        if (boolval($this->sendEmails) && $emailBody != null) {
            foreach ($this->GetEmails() as $email) {
                $this->arcadier->SendEmail($this->emailfrom, $email, $emailBody, $this->emailSubject);
            }
        }
    }

    function StoreLogs()
    {
    //store in ct the log
    }

    function GetEmails()
    {
        return explode(",", $this->emailsToBeSend);
    }

    function GetEmailParams()
    {
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

}

?>