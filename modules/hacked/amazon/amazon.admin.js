// $Id: amazon.admin.js,v 1.1 2008/02/22 05:04:30 eaton Exp $

jQuery(document).ready(function(){ jQuery("#edit-amazon-associate-setting").bind("change", function() {
  if (this.value == 'custom') {
    $("#amazon-associate-id-wrapper").show('fast');
  }
  else {
    $("#amazon-associate-id-wrapper").hide('fast');
  }
  return false;
})
});

jQuery(document).ready(
  function(){ jQuery("#edit-amazon-cache").bind("change", function() {
  if (this.checked == true) {
    $("#amazon-storage-details").show('fast');
  }
  else {
    $("#amazon-storage-details").hide('fast');
  }
  return false;
  })
});
