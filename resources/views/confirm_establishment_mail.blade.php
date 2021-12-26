@component('mail::message')
<h1>{{__('mail.confirmation_mail.title')}}</h1>
<p>{{__('mail.confirmation_mail.paragraph')}}</p>

@component('mail::button', ['url' => $url])
{{__('mail.confirmation_mail.click_here')}}
@endcomponent

@endcomponent