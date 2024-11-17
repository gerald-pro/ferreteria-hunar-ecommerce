<?php

namespace App\Livewire\Contacts;

use App\Models\ContactMessage;
use Livewire\Component;

class ContactForm extends Component
{
    public $name;
    public $email;
    public $subject;
    public $message;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'required|string|max:255',
        'message' => 'required|string',
    ];

    public function submit()
    {
        $this->validate();

        ContactMessage::create([
            'name' => $this->name,
            'email' => $this->email,
            'subject' => $this->subject,
            'message' => $this->message,
        ]);

        // Enviar correo de confirmación o notificación
        // Mail::to('support@example.com')->send(new ContactMessageReceived($this->name, $this->email, $this->subject, $this->message));

        $this->dispatch('toast:message', [
            'message' => 'Mensaje enviado correctamente.',
            'status' => 'success',
        ]);

        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->subject = '';
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.contacts.contact-form');
    }
}
