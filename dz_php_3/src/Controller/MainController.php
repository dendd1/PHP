<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;

class MainController extends BaseController
{
    public function __construct()
    {
        $this->DbController = new DbController();
        session_start();
    }

    public function show_main_page(): Response
    {
        return $this->renderTemplate('main_page.php', []);
    }

    public function sendApp()
    {
        $fname = htmlspecialchars($_POST["first_name"]);
        $sname = htmlspecialchars($_POST["second_name"]);
        $lname = htmlspecialchars($_POST["last_name"]);
        $email = htmlspecialchars($_POST["email"]);
        $phone = htmlspecialchars($_POST["phone"]);
        $comm = htmlspecialchars($_POST["comm"]);
        $PDO = $this->DbController->connectToDataBase();
        if ($this->DbController->getUserCountByEmail($PDO, $email) > 0) {
            $dateFromDB = DateTime::createFromFormat('Y-m-d H:i:s', $this->
            DbController->
            getUserWithActiveReplyByEmail($PDO, $email)['datetime']);
            $now = new \DateTime('now');
            $interval = $now->diff($dateFromDB);
            $minutes = $interval->days * 24 * 60;
            $minutes += $interval->h * 60;
            $minutes += $interval->i;
            if ($minutes < 60 and $dateFromDB < $now) {
                $date_plus_hour = $dateFromDB;
                $date_plus_hour->modify('+ 1 hour');
                $time_pause = $date_plus_hour->diff($now);
                return new JsonResponse(['status' => false, 'h' => $time_pause->h, 'm' => $time_pause->i, 's' => $time_pause->s]);
            } else {
                $this->DbController->deleteUserById($PDO, $this->DbController->getUserIdByEmail($PDO, $email));
                $this->DbController->addAppToDB($PDO, $fname, $sname, $lname, $email, $phone, $comm);
                $this->DbController->sendToEmail($fname . ' ' . $sname . ' ' . $lname, $email, $phone, $comm);
                return new JsonResponse(['status' => true]);
            }
        } else {
            $this->DbController->addAppToDB($PDO, $fname, $sname, $lname, $email, $phone, $comm);
            $this->DbController->sendToEmail($fname . ' ' . $sname . ' ' . $lname, $email, $phone, $comm);
            return new JsonResponse(['status' => true]);
        }
    }
}
