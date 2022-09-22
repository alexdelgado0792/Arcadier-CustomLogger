<?php

require_once('ArcadierApi.php');

class CustomLogger
{
    private $sendEmails;
    private $emailsToBeSend;
    private $emailfrom;
    private $emailSubject;
    private $arcadier;

    function __construct($clientId, $clientSecret)
    {
        $this->arcadier = new ArcadierApi($clientId, $clientSecret);
    }

    function Log($payloadToLog, $message, $fileName = null, $emailBody = null)
    {
        $requestBody = $this->BuildLogRequest($payloadToLog, $message, $fileName);

        $this->StoreLog($requestBody);

        $this->SendEmails($emailBody);
    }

    private function SendEmails($emailBody = null)
    {
        $this->GetEmailParams();
        
        //Execute only if flag is activated and a email body is supply
        if (boolval($this->sendEmails) && $emailBody != null) {
            foreach ($this->GetEmails() as $email) {
                $this->arcadier->SendEmail($this->emailfrom, $email, $emailBody, $this->emailSubject);
            }
        }
    }

    private function StoreLog($requestBody)
    {
        $this->arcadier->CreateCtRow("Log", $requestBody);
    }

    private function GetEmails()
    {
        return explode(",", $this->emailsToBeSend);
    }

    private function GetEmailParams(): bool
    {
        $getParams = false;
        $configParams = $this->arcadier->GetAllCtContent('Configuration');

        if ($configParams["TotalRecords"] == 0) {
            $msg = "At least one configuration must exists. Current is " + $configParams["TotalRecords"];
            $file = "CustomerLogger.php->GetEmailParams()";
            $requestBody = $this->BuildLogRequest(null, $msg, $file);

            $this->StoreLog($requestBody);
        }
        else if ($configParams["TotalRecords"] > 1) {
            $msg = "Just one configuration must exists. Current is " + $configParams["TotalRecords"];
            $file = "CustomerLogger.php->GetEmailParams()";
            $requestBody = $this->BuildLogRequest(null, $msg, $file);

            $this->StoreLog($requestBody);
        }
        else if ($configParams["TotalRecords"] == 1) {
            $this->sendEmails = $configParams["Records"][0]["SendEmail"];
            $this->emailsToBeSend = $configParams["Records"][0]["Emails"];
            $this->emailfrom = $configParams["Records"][0]["EmailFrom"];
            $this->emailSubject = $configParams["Records"][0]["EmailSubject"];
            return true;
        }

        return $getParams;
    }

    private function BuildLogRequest($payloadToLog, $message, $fileName = null)
    {
        return [
            "Payload" => json_encode($payloadToLog), //this should be json format
            "Message" => $message,
            "File" => $fileName,
        ];
    }

}

?>