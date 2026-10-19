<div style="border: 2px solid {{ $type === 'error' ? 'red' : 'green' }}; background-color: #ffffff; padding: 15px; margin-bottom: 15px;">
    <strong>{{ ucfirst($type) }}</strong> — {{ $message }}
</div>
