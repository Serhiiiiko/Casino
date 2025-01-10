<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewWithdrawalNotification extends Notification
{
    use Queueable;

    /**
     * Имя пользователя, который запросил вывод.
     */
    public $name;

    /**
     * Сумма вывода.
     */
    public $amout;

    /**
     * Создаёт новый экземпляр уведомления.
     */
    public function __construct($name, $amout)
    {
        $this->name  = $name;
        $this->amout = $amout;
    }

    /**
     * Определяет каналы доставки уведомления.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Представление уведомления в виде письма (Mail).
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->view(
            'emails.new-withdrawal', [
                'usuario' => $this->name,
                'valor'   => \Helper::amountFormatDecimal($this->amout),
            ]
        );
    }

    /**
     * Данные для хранения в БД (канал 'database').
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Здравствуйте, Администратор. Был запрошен вывод средств на сумму '
                . \Helper::amountFormatDecimal($this->amout)
                . ' пользователем '
                . $this->name,
        ];
    }
}
