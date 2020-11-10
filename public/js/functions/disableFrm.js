function disableFrm(frm_options) {
  var promise = new Promise(function disable(resolve, reject) {

    let form = document.getElementById(frm_options.frm_id);

    let inputs = form.elements;
    for (i = 0; i < inputs.length; i++) {
      inputs[i].setAttribute('disabled', true);
    }

    let buttons = form.getElementsByTagName('button');
    for (i = 0; i < buttons.length; i++) {
      buttons[i].setAttribute('disabled', true);
    }

    let icon_btn = document.getElementById(frm_options.submit_btn_id).querySelector('i');

    icon_btn.classList.remove(frm_options.onenb_btn_icon);
    icon_btn.classList.add(frm_options.ondis_btn_icon);
    icon_btn.classList.add(frm_options.ondis_btn_icon_spin);

    let txt_btn = document.getElementById(frm_options.submit_btn_id).querySelector('span');
    txt_btn.textContent = frm_options.ondis_btn_spantxt;

    resolve('DISABLED_BUTTONS');
  });
  return promise;
}

function disablePreviewFrm(frm_options, btn_id) {
  var promise = new Promise(function disable(resolve, reject) {
    let form = document.getElementById(frm_options.frm_id);

    let inputs = form.elements;
    for (i = 0; i < inputs.length; i++) {
      inputs[i].setAttribute('disabled', true);
    }

    let buttons = form.getElementsByTagName('button');
    for (i = 0; i < buttons.length; i++) {
      buttons[i].setAttribute('disabled', true);
    }

    let spinned_button_id;

    if (btn_id === 'submit') {
      spinned_button_id = frm_options.submit_btn_id;
    } else {
      spinned_button_id = frm_options.back_btn_id;
    }

    let icon_btn = document.getElementById(spinned_button_id).querySelector('i');

    icon_btn.classList.remove(frm_options.onenb_btn_icon);
    icon_btn.classList.add(frm_options.ondis_btn_icon);
    icon_btn.classList.add(frm_options.ondis_btn_icon_spin);

    let txt_btn = document.getElementById(spinned_button_id).querySelector('span');
    txt_btn.textContent = frm_options.ondis_btn_spantxt;

    resolve('DISABLED_BUTTONS');
  });
  return promise;
}