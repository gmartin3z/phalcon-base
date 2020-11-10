function submitData(route, method, data, toastr_options, frm_options) {
  toastr.remove();
  let promise = new Promise(function submit(resolve, reject) {
    let params = typeof(data) == 'string' ? data : Object.keys(data).map(
      function serializeData(key) {
        return encodeURIComponent(key) + '=' + encodeURIComponent(data[key])
      }
    ).join('&');

    let request = new XMLHttpRequest();
    request.open(method, route, true);
    request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    request.onreadystatechange = function changeState() {
      let status_code = request.status;
      let response_content = request.responseText;

      let message_type;
      let messages_bag = [];

      if (request.readyState == XMLHttpRequest.DONE) {
        switch (status_code) {
          case 200:
              message_type = 'success';
              messages_bag = [toastr_options.msg_status_200];
              resetData(frm_options.frm_id);
            break;
          case 422:
              json_response = JSON.parse(response_content);
              errors = json_response.details;
              message_type = 'error';
              err = errors;
              messages_bag = [toastr_options.msg_status_422 + '\n'];
              for (current = 0; current < err.length; current++) {
                messages_bag.push('<li>' + err[current] + '</li>')
              }
            break;
          case 404:
              message_type = 'error';
              if (typeof response === 'undefined') {
                messages_bag = [
                  toastr_options.msg_status_404
                ];
              } else {
                messages_bag = [
                  toastr_options.msg_status_404 + '\n' + response_content
                ];
              }
            break;
          case 0:
              message_type = 'error';
              messages_bag = [
                toastr_options.msg_status_0
              ];
            break;
          default:
              message_type = 'error';
              messages_bag = [
                toastr_options.msg_status_unknown + '\n' + '(' + status_code + ')'
              ];
            break;
        }

        // resolve or reject
        if (message_type == 'success') {
          toastr[message_type](messages_bag, toastr_options.success_title, {
            timeOut: 5000 
          });
          resolve('SUCCESSFUL_REQUEST');
        } else {
          toastr[message_type](messages_bag, toastr_options.error_title, {
            newestOnTop: true,
            preventDuplicates: true,
            timeOut: 10000,
          }).css('width', '440px');
          reject('FAILED_REQUEST');
        }
      }
    }
    request.send(params);
  });
  return promise;
}