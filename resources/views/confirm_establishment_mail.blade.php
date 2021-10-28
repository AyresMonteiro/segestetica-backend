@component('mail::message')
<h1>Confirme sua senha.</h1>
<p>Clique no botão abaixo para confirmar sua senha.</p>

@component('mail::button', ['url' => $url])
  CLIQUE AQUI
@endcomponent

@endcomponent