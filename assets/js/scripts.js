/*!
    * Start Bootstrap - SB Admin v7.0.4 (https://startbootstrap.com/template/sb-admin)
    * Copyright 2013-2022 Start Bootstrap
    * Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-sb-admin/blob/master/LICENSE)
*/  

/*
   If the current url is not login page, check localStorage for email and token. Display admin email on the dashboard, otherwise redirect to login page if token and email doesn't exist. 
   The path variable should be replaced to match your directory path in your server's root directory retaining the prefix forward slash(/). Otherwise they would be an infinite refresh. Example of my server's root directory:
   `C:\laragon\www` actually `/Wp/login.html` is located within the `www` directory. In case you need to place `/WP/login.html` in a directory that come before `C:\laragon\www` then replace the path variable to match it.  
*/
let path = '/michael-Ikechukwu_PHP/login.html';
if (window.location.pathname != path) {
    if (localStorage['token'] && localStorage['email']) {
        window.addEventListener('DOMContentLoaded', event => {
            $('.loggedIn').text(localStorage['email']);
        });
    }else{
        location.href = 'login.html';
    }
}

/*
  Excuted javascript after the dom content is loaded
*/
window.addEventListener('DOMContentLoaded', event => {
   //Login Authentication
   $('#login_form').on('submit',function(event){
        // Prevent form submission
        event.preventDefault();
        // Get user's email
        let $userEmail = $('#inputEmail').val();
        // Get user's password
        let $userPassword = $('#inputPassword').val();
        // Get the attribute error
        let $error = $('#error');

        if (!$userEmail.match(/^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/) || $userEmail == "") {
            $('.E-error').css('display', 'block');
            $('#inputEmail').css('border', '1px solid #f00');
            return false;
        }else{
            $('.E-error').css('display', 'none');
            $('#inputEmail').css('border', '1px solid #ced4da');
        }

        if ($userPassword == "") {
            $('.P-error').css('display', 'block');
            $('#inputPassword').css('border', '1px solid #f00');
            return false;
        }else{
            $('.P-error').css('display', 'none');
            $('#inputPassword').css('border', '1px solid #ced4da');
        }        
        
        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
        }).then(function(res){
            let data = $.parseJSON(res);
            console.log(data);
            if (data.error) {
                $error.removeClass('d-none').html(data.error);
                return;
            }

            localStorage.setItem('token', data.token);
            localStorage.setItem('email', data.email);
            location.href = 'index.html';
        }).fail(function(res) {
            $error.removeClass('d-none').text('Error attempting to sign in');
        });
    });

   // Logout admin out of the dashboard
   $('.logOut').on('click', function(event){
        event.preventDefault();
        let action = "logOut";
        $.ajax({
            type: 'GET',
            url: 'src/WP_Crawler.php',
            data: {
                action:action,
                token: localStorage.getItem('token'),
                email: localStorage.getItem('email')
            }
        }).then(function(res){
            console.log(res);
            let data = $.parseJSON(res);
            if (data.success == true) {
                localStorage.clear();
                location.href = 'login.html';
            }
        });
   });

   //Verify token and email before proceeding
    if (localStorage['token'] && localStorage['email']) {   
         /*Perform crawler*/
         $('#url_form').on('submit',function(event){
            // Prevent form submission
            event.preventDefault();
            let url_address = $('#host_address').val();
            // console.log(url_address.split('/').length);
                if(/^(http|https):\/\/[a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/i.test(url_address) || url_address == ""){
                    if (url_address.split('/').length == 3) {
                        $('#error').addClass('d-none');
                        /*Display Spinner*/
                        $("#shapes").css("display", "grid");
                        $.ajax({
                            type: 'POST',
                            url: $(this).attr('action'),
                            data: $(this).serialize(),
                        }).then(function(res){
                            let data = $.parseJSON(res);
                            /*Hide Spinner*/
                            $(".shapes").css("display", "none");
                            if (data.success == false) {
                                 $('#error').addClass('alert-danger').removeClass('d-none').html('No internet, please check your internet connection');
                            }else{
                                $('#error').addClass('alert-primary').removeClass('d-none').html('Crawler Completed');
                            }
                           
                        });
                    } else {
                        $('#error').addClass('alert-danger').removeClass('d-none').html('Please enter valid url');
                    }
                } else {
                    $('#error').addClass('alert-danger').removeClass('d-none').html('Please enter valid url');
                }
        });
    }
    

   // Toggle the side navigation
   const sidebarToggle = document.body.querySelector('#sidebarToggle');
   if (sidebarToggle) {
        // Uncomment Below to persist sidebar toggle between refreshes
        if (localStorage.getItem('sb|sidebar-toggle') === 'true') {
            document.body.classList.toggle('sb-sidenav-toggled');
        }
        sidebarToggle.addEventListener('click', event => {
            event.preventDefault();
            document.body.classList.toggle('sb-sidenav-toggled');
            localStorage.setItem('sb|sidebar-toggle', document.body.classList.contains('sb-sidenav-toggled'));
        });
    }

});