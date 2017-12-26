<!DOCTYPE html>
<html>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0">
<head>
	<title>@lang('Ошибка на сайте')</title>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.4.1/css/bulma.min.css">
</head>
<body>
<section class="hero is-danger is-fullheight">
  <div class="hero-body">
		<div class="container has-text-centered">
		  <h1 class="title">
		    @lang('Шмяк')
		  </h1>
		  <h2 class="subtitle">
		    @lang('На сайте найдена ошибка') <button class="button modal-button" data-target="details">@lang('Подробно')</button>
		  </h2>
		</div>
  </div>
</section>
<div class="modal" id="details">
  <div class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">@lang('Описание ошибки')</p>
      <button class="delete"></button>
    </header>
    <section class="modal-card-body">
      {{$exception}}
    </section>
    <footer class="modal-card-foot">
      <a class="button">@lang('Закрыть')</a>
    </footer>
  </div>
</div>
</body>
</html>