<?php

namespace App\Livewire\Tools;

use App\Actions\AnalyzeSslOutput;
use Livewire\Component;

class SslTriage extends Component
{
    public string $opensslOutput = '';

    public ?array $analysis = null;

    public function analyze(): void
    {
        $this->validate([
            'opensslOutput' => 'required|min:50',
        ]);

        $this->analysis = app(AnalyzeSslOutput::class)($this->opensslOutput);
    }

    public function reset(): void
    {
        $this->opensslOutput = '';
        $this->analysis = null;
    }

    public function render()
    {
        return view('livewire.tools.ssl-triage');
    }
}
