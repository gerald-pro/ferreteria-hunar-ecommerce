<?php

namespace App\Livewire\Contacts;

use App\Models\ContactMessage;
use Livewire\Attributes\On;
use Livewire\Component;

class ContactMessagesList extends Component
{
    public $messages;

    public function mount()
    {
        $this->messages = ContactMessage::all();
    }

    #[On('delete')] 
    public function delete($messageId)
    {
        $message = ContactMessage::findOrFail($messageId);
        $message->delete();

        $this->messages = ContactMessage::all();

        $this->dispatch('toast:message', ['status' => 'success', 'message' => 'Mensaje eliminado con éxito.']);
    }

    public function render()
    {
        return view('livewire.contacts.contact-messages-list');
    }
}
