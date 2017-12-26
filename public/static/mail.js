var field = [];
field["kk"] = "Бұл форма";
field["en"] = "This field";
field["ru"] = "Это поле";

var invalid = [];
invalid["kk"] = "дұрыс толтырылмаған";
invalid["en"] = "has invalid value";
invalid["ru"] = "заполнено неверно";

var incomplete = [];
incomplete["kk"] = "толық толтырылмаған";
incomplete["en"] = "has incomplete value";
incomplete["ru"] = "заполнено неполностью";

var empty = [];
empty["kk"] = "бос болмауы тиіс";
empty["en"] = "can't be empty";
empty["ru"] = "не может быть пустым";

var captcha = [];
captcha["kk"] = "Сіз recaptcha тексеруінен өтпедіңіз";
captcha["en"] = "You did not completed recaptcha check";
captcha["ru"] = "Вы не прошли проверку recaptcha";

var alreadySend = false;

function validate(name, phone, email, msg) {
  var errors = {}
  var values = {}
  var invalid = false
  values.name = name.trim()
  values.phone = phone.trim()
  values.email = email.trim()
  values.msg = msg.trim()
  if (!values.name) {
    errors.name = field[activeLocale]+" "+empty[activeLocale];
    invalid = true
  } else if (values.name.split(' ').length == 1) {
    errors.name = field[activeLocale]+" "+incomplete[activeLocale];
    invalid = true
  }
  if (!values.email) {
    errors.email = field[activeLocale]+" "+empty[activeLocale];
    invalid = true
  } else if (!/^(([^<>()\[\]\.,;:\s@\"]+(\.[^<>()\[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(values.email)) {
    errors.email = field[activeLocale]+" "+invalid[activeLocale];
    invalid = true
  }
  if (!values.msg) {
    errors.msg = field[activeLocale]+" "+empty[activeLocale];
    invalid = true
  } else if (msg.length < 8) {
    errors.msg = field[activeLocale]+" "+invalid[activeLocale];
    invalid = true
  }
  return {
    errors: errors,
    values: values,
    invalid: invalid
  }
}

function showFormErrors(errors) {
  if (errors.name) {
    $('#name_field').addClass('error')
    jQuery('#name_field .error_text').text(errors.name)
  }
  if (errors.email) {
    $('#email_field').addClass('error')
    jQuery('#email_field .error_text').text(errors.email)
  }
  if (errors.msg) {
    $('#msg_field').addClass('error')
    jQuery('#msg_field .error_text').text(errors.msg)
  }
}

function submitFn() {
  var btnInitialText;
  var name = $('#name_input').val();
  var phone = $('#phone_input').val();
  var email = $('#email_input').val();
  var msg = $('#msg_input').val();
  var gRecaptchaResponse =$('.g-recaptcha-response').val();
  if (gRecaptchaResponse == ""){
      alert(captcha[activeLocale]);
  }
  var validator = validate(name, phone, email, msg)
  if (validator.invalid) {
    showFormErrors(validator.errors);
    return false;
  } else {
    btnInitialText = $("#submit_contact_form").html();
    $("#submit_contact_form").html("<img src='/loader/white.svg'>");
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    if(alreadySend===false){
      alreadySend = true;
      request = $.ajax({
        url: "/send-mail",
        type: "post",
        data: {
          name: name,
          phone: phone,
          email: email,
          msg: msg,
          'g-recaptcha-response': gRecaptchaResponse
        },
      });
      request.done(function (response, textStatus, jqXHR){
        console.log(response+", "+textStatus);
        $('#form-container').addClass('hidden');
        $('#done-container').addClass('visible');
      });
      request.fail(function (jqXHR, textStatus, errorThrown){
        console.error(
          "The following error occurred: "+
          textStatus, errorThrown
        );
        alert("Ошибка на сервере");
      });
      request.always(function () {
        $("#submit_contact_form").html(btnInitialText);
        return true;
      });
    }
  }
}