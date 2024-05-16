<?php

namespace App\Services;

use Symfony\Component\Mailer\MailerInterface;
Use Symfony\Component\Mime\Email;

class MailerService
{
    public function __construct (private MailerInterface $mailer) {

    }

    public function sendEmail(
        $to = 'steven_gomes@hotmail.fr',
        $subject = 'This is the Mail subject !',
        $content = '',
        $text = ''
    ): void
    {
        $email = (new Email())
            ->from('noreply@mysite.com')
            ->to($to)
            ->subject($subject)
            ->text($text)
            ->html($content);
        $this->mailer->send($email);
    }

    public function sendAccountCreationEmail(string $to): void
    {
        $email = (new Email())
            ->from('noreply@zooarcadia.com')
            ->to($to)
            ->subject('Création de votre compte')
            ->text('Votre compte a été créé avec succès. Veuillez vous rapprocher de l\'administrateur pour obtenir votre mot de passe.');

        $this->mailer->send($email);
    }
}