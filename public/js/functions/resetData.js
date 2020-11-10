function resetData(frm_id) {
  elements = document.getElementById(frm_id);
  for (current = 0; current < elements.length; current++) {
    field_type = elements[current].type.toLowerCase();
    switch (field_type) {
      case 'text':
      case 'password':
      case 'textarea':
        elements[current].value = '';
        break;
      case 'radio':
      case 'checkbox':
        if (elements[current].checked) {
          elements[current].checked = false;
        }
        break;
      case 'select-one':
      case 'select-multi':
        elements[current].selectedIndex = -1;
        break;
      default:
        break;
    }
  }
}