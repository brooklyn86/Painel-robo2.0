@component("mail::message")
<h3 align="left">Olá, Fair Consultoria</h3>
<p align="left">{{$dados['messagem']}}</p>
<p align="left">Processo: <b>{{$dados['processo']['precatoria']}}</b></p>
<p align="left">Situação: <b>{{$dados['processo']['situacao']}}</b></p>

@component("mail::button", ['url' => 'https://robo.fairconsultoria.com.br'])
Acessar Plataforma
@endcomponent

@endcomponent
      

