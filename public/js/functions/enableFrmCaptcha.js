function enableFrmCaptcha(captcha_options) {
  var promise = new Promise(function enable(resolve, reject) {

    let form = document.getElementById(captcha_options.frm_id);

    let inputs = form.elements;
    for (i = 0; i < inputs.length; i++) {
      inputs[i].removeAttribute('disabled');
    }

    let buttons = form.getElementsByTagName('button');
    for (i = 0; i < buttons.length; i++) {
      buttons[i].removeAttribute('disabled');
    }

    let icon_btn = document.getElementById(captcha_options.captcha_btn_id).querySelector('i');
    icon_btn.classList.remove(captcha_options.ondis_btn_icon_spin);
    resolve('CAPTCHA_ENABLED_BUTTONS');
  });
  return promise;
}