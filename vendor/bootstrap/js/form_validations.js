//Stn code Validation
function stncode_val(txt) {
  $("#stncode_empty").css("display", "none");
  if (/^[A-Za-z]+$/.test(txt.value) == false) {
    $("#stncode_val").css("display", "block");
  } else {
    $("#stncode_val").css("display", "none");
  }
}
//Stn name Validation
function stnname_val(txt) {
  $("#stnname_empty").css("display", "none");
  if (/^[0-9a-zA-Z\s]+$/.test(txt.value) == false) {
    $("#stnname_val").css("display", "block");
  } else {
    $("#stnname_val").css("display", "none");
  }
}

//branch code validation
function brcode_val(txt) {
  $("#br_code_empty").css("display", "none");
  if (/^[A-Za-z]+$/.test(txt.value) == false) {
    $("#br_code_val").css("display", "block");
  } else {
    $("#br_code_val").css("display", "none");
  }
}

//branch name validation
function brname_val(txt) {
  $("#br_name_empty").css("display", "none");
  if (/^[A-Za-z\s]+$/.test(txt.value) == false) {
    $("#br_name_val").css("display", "block");
  } else {
    $("#br_name_val").css("display", "none");
  }
}
//Mobile
function mob_val(txt) {
  if (/^\d{10}$/.test(txt.value) == false) {
    $("#mob_val").css("display", "block");
  } else {
    $("#mob_val").css("display", "none");
  }
  $("#mob_empty").css("display", "none");
}
//Phone
function phno_val(txt) {
  if (/^\d{10}$/.test(txt.value) == false) {
    $("#phno_val").css("display", "block");
  } else {
    $("#phno_val").css("display", "none");
  }
  $("#phno_empty").css("display", "none");
}
//Fax
function fax_val(txt) {
  if (/^\d+$/.test(txt.value) == false) {
    $("#fax_val").css("display", "block");
  } else {
    $("#fax_val").css("display", "none");
  }
}
//Email
function email_val(txt) {
  if (
    /^([a-z0-9_\-\.])+\@([a-z0-9_\-\.])+\.([a-z]{2,4})$/.test(txt.value) ==
    false
  ) {
    $("#email_val").css("display", "block");
  } else {
    $("#email_val").css("display", "none");
  }
  $("#email_empty").css("display", "none");
}
//GST
function gst_val(txt) {
  if (
    /^([0-2][0-9]|[3][0-7])[A-Z]{3}[ABCFGHLJPTK][A-Z]\d{4}[A-Z][A-Z0-9][Z][A-Z0-9]$/.test(
      txt.value
    ) == false
  ) {
    $("#gst_val").css("display", "block");
  } else {
    $("#gst_val").css("display", "none");
  }
}
//Hub
function hub_val(txt) {
  if (/^[A-Za-z]+$/.test(txt.value) == false) {
    $("#hub_val").css("display", "block");
  } else {
    $("#hub_val").css("display", "none");
  }
}
//Current Invoice
function currinv_val(txt) {
  if (/^\d+$/.test(txt.value) == false) {
    $("#currinv_val").css("display", "block");
  } else {
    $("#currinv_val").css("display", "none");
  }
}
//Bank Account Name
function bankaccname_val(txt) {
  if (/^[A-Za-z\s]+$/.test(txt.value) == false) {
    $("#bankaccname_val").css("display", "block");
  } else {
    $("#bankaccname_val").css("display", "none");
  }
}
//Bank Account Number
function bankaccnum_val(txt) {
  if (/^\d+$/.test(txt.value) == false) {
    $("#bankaccnum_val").css("display", "block");
  } else {
    $("#bankaccnum_val").css("display", "none");
  }
}
