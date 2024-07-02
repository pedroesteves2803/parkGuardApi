@component('mail::message')
# Token de recuperção de senha

- **ID:** {{ $passwordResetToken->token()->value() }}


Obrigado por utilizar nossa aplicação!

Atenciosamente,<br>
{{ config('app.name') }}
@endcomponent
