// Sign in form validation

$('#sign_in').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    if(!validateRequiredFields(form)) {

        var getData = ConvertFormToJSON(form);


        $.post('/index.php',getData,function(result) {
            if(result) {

                if(result.exist) {

                    $('#signInModal .modal-title').html('Problem with the login');
                    $('#signInModal .modal-body p').html(result.exist);
                    $('#signInModal').modal('show').on("hidden.bs.modal", function () {
                        window.location = "/";
                    });
                    setTimeout(function () {
                        $('#signInModal').find('.black_btn').trigger('click');
                    },2000);

                } else if(result.loggedInAdmin) {

                    $('#signInModal .modal-title').html('Successful login');
                    $('#signInModal .modal-body p').html(result.loggedInAdmin);
                    $('#signInModal').modal('show').on("hidden.bs.modal", function () {
                      window.location = "/admin-home.php";
                    });
                    setTimeout(function () {
                        $('#signInModal').find('.black_btn').trigger('click');
                        window.location = "/admin-home.php";
                    },2000);

                } else if(result.loggedInEmployee) {
                  $('#signInModal .modal-title').html('Successful login');
                  $('#signInModal .modal-body p').html(result.loggedInEmployee);
                  $('#signInModal').modal('show').on("hidden.bs.modal", function () {
                      window.location = "/employee-home.php";
                  });
                  setTimeout(function () {
                      $('#signInModal').find('.black_btn').trigger('click');
                      window.location = "/employee-home.php";
                  },2000);
                }
            }

        },'json');

    }
});

//Sign up form validation

$('#register').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);

    if(!validateRequiredFields(form)) {

        var validEmail = validateEmailType(form);
        var letter = validateLettersOnly(form);

        if(!validEmail && !letter) {

            var getData = ConvertFormToJSON(form);
            $.post('/create-user.php',getData,function(result) {

                if(result) {

                    if(result.exist) {

                        $('#signUpModal .modal-title').html('Problem with the registration');
                        $('#signUpModal .modal-body p').html(result.exist);
                        $('#signUpModal').modal('show');

                    }else if(result.created) {

                        $('#signUpModal .modal-title').html('Registration successful');
                        $('#signUpModal .modal-body p').html(result.created);
                        $('#signUpModal').modal('show').on("hidden.bs.modal", function () {
                            window.location = "/admin-home.php";
                        });
                        setTimeout(function () {
                            $('#signUpModal').find('.black_btn').trigger('click');
                            window.location = "/admin-home.php";
                        },2000);

                    }
                }

            },'json');
        }
    }
});

//Submit request validation

$('#submitRequest').validate({ // initialize the plugin
     rules: {
         vacation_start:"required",
         vacation_end:"required",
         reason:"required"
     },
     messages: {
       vacation_start: "This field is required",
       vacation_end: "This field is required",
       reason: "This field is required"
     }
 });

 //Update validation

 $('#prepopulated_form').validate({ // initialize the plugin
      rules: {
          password:"required"
      },
      messages: {
        password: "This field is required"
      }
  });

function validateRequiredFields(form) {
    var error = false;
    form.find('.required').each(function() {
        if($(this).hasClass('roboto')) {
            if($(this).val() != '') {
                error = true;
            }
        } else {
            if($(this).val() == '') {
                $(this).addClass('invalid');
                $(this).parent().find('.error_msg').html('This field is required.').show();
                error = true;
            } else {
                $(this).removeClass('invalid');
                $(this).parent().find('.error_msg').hide();
            }
        }
    });
    return error;
}

function validateLettersOnly(form) {
    var error = false;
    var pattern = /^[A-Za-zα-ωΑ-ΩίϊΐόάέύϋΰήώΆΌΈΎΊΉΏ\s]+$/;
    form.find('.lettersOnly').each(function() {
        if(!pattern.test($(this).val())) {
            $(this).addClass('invalid');
            $(this).parent().find('.error_msg').html('It is only allowed letters.').show();
            error = true;
        } else {
            $(this).removeClass('invalid');
            $(this).parent().find('.error_msg').hide();
        }
   });
    return error;
}

function validateEmailType(form) {
    var error = false;
    var emailField = form.find('input.validateEmail');
    var pattern = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    if(!pattern.test(emailField.val())) {
        emailField.addClass('invalid');
        emailField.parent().find('.error_msg').html('The email is not valid.').show();
        error = true;
    } else {
        emailField.removeClass('invalid');
        emailField.parent().find('.error_msg').hide();
    }
    return error;
}

function ConvertFormToJSON(form) {
    var array = jQuery(form).serializeArray();
    var json = {};

    jQuery.each(array, function() {
        json[this.name] = this.value || '';
    });

    return json;
}
