<?php

namespace App\Notifications;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserLikeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $post;
    public $link;
    public $text;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
        $this->text = "پست {$this->post->title} لایک شده است.";
        $this->link = "/post/{$this->post->slug}";
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return !!$notifiable->email_on_like ? ['mail', 'database'] : ['database'];
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
            ->subject("پست شما لایک شد")
            ->line($this->text)
            ->action("لینک پست", url($this->link));
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
            'text' => $this->text,
            'link' => $this->link,
        ];
    }
}