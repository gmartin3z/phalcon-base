function enableFrm(frm_options) {
  var promise = new Promise(function enable(resolve, reject) {

    let form = document.getElementById(frm_options.frm_id);

    let inputs = form.elements;
    for (i = 0; i < inputs.length; i++) {
      inputs[i].removeAttribute('disabled');
    }

    let buttons = form.getElementsByTagName('button');
    for (i = 0; i < buttons.length; i++) {
      buttons[i].removeAttribute('disabled');
    }

    let icon_btn = document.getElementById(frm_options.submit_btn_id).querySelector('i');

    icon_btn.classList.remove(frm_options.ondis_btn_icon);
    icon_btn.classList.remove(frm_options.ondis_btn_icon_spin);
    icon_btn.classList.add(frm_options.onenb_btn_icon);

    let txt_btn = document.getElementById(frm_options.submit_btn_id).querySelector('span');
    txt_btn.textContent = frm_options.onenb_btn_spantxt;

    resolve('ENABLED_BUTTONS');
  });
  return promise;
}