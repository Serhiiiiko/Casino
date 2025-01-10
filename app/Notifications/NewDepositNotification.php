<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewDepositNotification extends Notification
{
    use Queueable;

    /**
     * Имя пользователя, совершившего депозит.
     *
     * @var string
     */
    public $name;

    /**
     * Сумма депозита.
     *
     * @var float
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
     * Создаёт представление уведомления в виде письма (Mail).
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Если у вас есть Blade-шаблон emails.new-deposit с локализацией,
        // можно передать переведённые ключи. Либо оставить как есть,
        // если шаблон уже поддерживает нужную локализацию.
        return (new MailMessage)->view(
            'emails.new-deposit', [
                'usuario' => $this->name,
                'valor'   => \Helper::amountFormatDecimal($this->amout),
            ]
        );
    }

    /**
     * Массив данных для хранения в БД (канал 'database').
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'message' => 'Здравствуйте, Администратор. Уведомляем вас о новом депозите на сумму ' 
                . \Helper::amountFormatDecimal($this->amout) 
                . ', совершённом пользователем ' 
                . $this->name,
        ];
    }
}
