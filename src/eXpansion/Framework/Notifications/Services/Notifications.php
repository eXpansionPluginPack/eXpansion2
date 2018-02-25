<?php
/**
 * Created by PhpStorm.
 * User: php_r
 * Date: 25.2.2018
 * Time: 21.50
 */

namespace eXpansion\Framework\Notifications\Services;


use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Notifications\Plugins\Gui\NotificationUpdater;
use Psr\Log\LoggerInterface;

class Notifications
{
    /**
     * @var NotificationUpdater
     */
    private $notificationsUpdater;
    /** @var Group */
    private $group;
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Notifications constructor.
     * @param Group               $group
     * @param NotificationUpdater $notificationUpdater
     * @param LoggerInterface     $logger
     */
    public function __construct(
        $group,
        NotificationUpdater $notificationUpdater,
        LoggerInterface $logger
    ) {
        $this->notificationsUpdater = $notificationUpdater;
        $this->group = $group;
        $this->logger = $logger;
    }

    /**
     * Sends notification as info level
     * Notifications are automatically logged
     *
     * @param string     $title Title of the notification
     * @param string     $message Message of notification
     * @param int        $duration use 0 for permanent
     * @param Group|null $group
     */
    public function info($message, $title = "Info", $duration = 5500, $group = null)
    {
        $this->logger->info("[Notification] ".$message, ["title" => $title]);
        $this->sendNotification('$3af '.$title, $message, $duration, $group);
    }

    /**
     * Sends notification as info level
     * Notifications are automatically logged
     *
     * @param string     $title Title of the notification
     * @param string     $message Message of notification
     * @param int        $duration use 0 for permanent
     * @param Group|null $group
     */

    public function notice($message, $title = "Notice", $duration = 5500, $group = null)
    {
        $this->logger->notice("[Notification] ".$message, ["title" => $title]);
        $this->sendNotification($title, $message, $duration, $group);
    }

    /**
     * Sends notification as info level
     * Notifications are automatically logged
     *
     * @param string     $title Title of the notification
     * @param string     $message Message of notification
     * @param int        $duration use 0 for permanent
     * @param Group|null $group
     */

    public function warning($message, $title = "Warning", $duration = 8500, $group = null)
    {
        $this->logger->warning("[Notification] ".$message, ["title" => $title]);
        $this->sendNotification('$ff0 '.$title, $message, $duration, $group);
    }

    /**
     * Sends notification as info level
     * Notifications are automatically logged
     *
     * @param string     $title Title of the notification
     * @param string     $message Message of notification
     * @param int        $duration use 0 for permanent
     * @param Group|null $group
     */

    public function error($message, $title = "Error", $duration = 10500, $group = null)
    {
        $this->logger->error("[Notification] ".$message, ["title" => $title]);
        $this->sendNotification('$f00 '.$title, $message, $duration, $group);
    }


    protected function sendNotification($title, $message, $duration, $group = null)
    {
        if ($group == null) {
            $group = $this->group;
        }

        $this->notificationsUpdater->setNotification($title, $message, $duration, $group);
    }


}