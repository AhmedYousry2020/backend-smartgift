<?php

namespace App\Notifications;

use App\Http\Controllers\Dashboard\NewFirebaseController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PushNotification extends Notification
{
    use Queueable;

    protected $title;
    protected $content;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($title, $content, $user)
    {
        $this->user = $user;

        $this->content = $content;

        $this->title = $title;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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

    public function toFirebase($notifiable)
    {
        $firebase = new NewFirebaseController();

        switch($this->user->device_type){
            case 'android':
                $firebase->sendAndroidNotification($this->user->device_token, $firebase->fillAndroidJson($this->title, $this->content,1,0));
            case 'ios':
                $firebase->sendIOSNotification($this->user->device_token, $firebase->fillIOSJson($this->title, $this->content));
        }
    }

     public function toDatabase($notifiable)
    {
        return [
            'title'=>$this->title,
            'content'=>$this->content
            ];
    }
}
