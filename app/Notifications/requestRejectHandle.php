<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class requestRejectHandle extends Notification implements ShouldQueue
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
            ->subject(Lang::getFromJson('Thông báo chuyển xử lý yêu cầu.'))
            ->greeting('Xin chào!')
            ->line(Lang::getFromJson('Có một yêu cầu đã được yêu cầu chuyển xử lý.'))
            ->line(Lang::getFromJson('Người gửi: '.$this->data['nguoi_gui'].' - ' . $this->data['phong_ban']))
            ->line(Lang::getFromJson('Tiêu đề: ').$this->data['tieu_de'])
            ->action(Lang::getFromJson('Chi tiết'), url(config('app.url').'/request-update/'.$this->data['ma_yeu_cau']))
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
