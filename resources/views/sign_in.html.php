<?php $this->extend('base_bootstrap') ?>

<?php $this->start('body') ?>
    <div class="container">
         <form class="form-signin" id="loginForm">
            <h2 class="form-signin-heading">Пожалуйста, авторизуйтесь</h2>
            <div class="alert alert-danger hidden" role="alert" id="submitAnswer">
                <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                <span class="sr-only">Ошибка:</span>
                <span id='answerText'></span>
            </div>
            <label for="inputName" class="sr-only">Имя пользователя</label>
            <input type="name" id="inputName" class="form-control" placeholder="Имя пользователя" required autofocus>
            <label for="inputPassword" class="sr-only">Пароль</label>
            <input type="password" id="inputPassword" class="form-control" placeholder="Пароль" required>
           <button class="btn btn-lg btn-primary btn-block" type="submit">Войти</button>
         </form>

    </div> <!-- /container -->
<?php $this->stop('body') ?>

<?php $this->start('script') ?>
    <script>
        $("#loginForm").submit(function(event){
            // cancels the form submission
            event.preventDefault();
            submitForm();
        });

        function submitForm(){
            // Initiate Variables With Form Content
            var name = $("#inputName").val();
            var password = $("#inputPassword").val();

            $.ajax({
                url: "/sign_in/check/",
                type: "POST",
                dataType: "json",
                data: {
                    name: name,
                    password: password
                },
                error: function(data) {
                    alert('AJAX response for "' + this.url + '" error:\n' + data.responseText);
                },
                success : function(answer) {
                    switch (answer.type) {
                        case 'error':
                            $('#answerText').text('Неверное имя пользователя или пароль');
                            $('#submitAnswer').removeClass("alert-success");
                            $('#submitAnswer').addClass("alert-danger");
                            $('#submitAnswer').removeClass("hidden");
                            break;
                        case 'success':
                            $('#answerText').text('Login success');
                            $('#submitAnswer').removeClass("alert-danger");
                            $('#submitAnswer').addClass("alert-success");
                            $('#submitAnswer').removeClass("hidden");
                            window.location.replace("/");
                            break;
                    }
                }
            });
        }
    </script>
<?php $this->stop('script') ?>
