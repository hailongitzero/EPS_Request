<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class requestAssignInform extends Notification implements ShouldQueue
{
    use Queueable;
    public $tries = 3; // Max tries
    public $data;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::getFromJson('Thông báo yêu cầu đã tiếp nhận.'))
            ->greeting('Xin chào!')
            ->line(Lang::getFromJson('Yêu cầu của bạn đã được tiếp nhận và xử lý bởi '.$this->data['nguoi_xu_ly'].'.'))
            ->line(Lang::getFromJson('Tiêu đề: ').$this->data['tieu_de'])
            ->action(Lang::getFromJson('Chi tiết'), url(config('app.url').'/request-detail/'.$this->data['ma_yeu_cau']))
            ->line(Lang::getFromJson('Đây là mail hệ thống. Vui lòng không trả lời email này.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
