function updateCaptcha(route, captcha_img_id) {
  let promise = new Promise(function submit(resolve, reject) {
    let request = new XMLHttpRequest();
    request.open('get', route, true);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    request.onreadystatechange = function changeState() {
      if (request.readyState == XMLHttpRequest.HEADERS_RECEIVED) {
        if (request.status == 200) {
          request.responseType = 'blob';
        } else {
          request.responseType = 'json';
        }
      }

      let message_type;
      let messages_bag = [];

      if (request.readyState == XMLHttpRequest.DONE) {
        let status_code = request.status;
        let status_text = request.statusText;
        let response_content = request.response;

        switch (status_code) {
          case 200:
              message_type = 'success';
              messages_bag = ['Listo'];
              url = window.URL.createObjectURL(response_content);
              img = document.getElementById(captcha_img_id);
              img.src = url;
            break;
          case 0:
              message_type = 'error';
              messages_bag = [
                'Sin conexión a internet o sitio fuera de línea. ' +
                'Verifique la conexión, recarge la página ' +
                'y reintente nuevamente o consulte ' +
                'al servicio de soporte.'
              ];
            break;
          case 404:
              message_type = 'error';
              if (typeof response === 'undefined') {
                messages_bag = [
                  'Error ' + status_code + ': ' + status_text + '. ' +
                  'Recarge la página y reintente nuevamente ' +
                  'o consulte al servicio de soporte.'
                ];
              } else {
                messages_bag = [
                  'Error ' + status_code + ': ' + response_content + '. ' +
                  'Recarge la página y reintente nuevamente ' +
                  'o consulte al servicio de soporte.'
                ];
              }
            break;
          default:
              message_type = 'error';
              messages_bag = [
                'Error ' + status_code + ': ' + status_text + '. ' +
                'Recarge la página y reintente nuevamente ' +
                'o consulte al servicio de soporte.'
              ];
            break;
        }

        // resolve or reject
        if (message_type == 'success') {
          resolve('SUCCESSFUL_REQUEST');
        } else {
          toastr[message_type](messages_bag, 'Error', {
            newestOnTop: true,
            preventDuplicates: true,
            timeOut: 10000,
          }).css('width', '440px');
          reject('FAILED_REQUEST');
        }
      }
    }
    request.send();
  });
  return promise;
}
