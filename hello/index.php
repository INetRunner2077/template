<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>

<div class="hello_form">
<h2> Спасибо за регистрацию </h2>

<p> На указанный Вами адрес электронной почты отправлено соответствующее уведомление. </p>

    <div class="button_container">
    <a href="/catalog" class="danil_button_target hello"  tabindex="10"><span>Перейти к подбору товаров</span></a>
    <a href="/personal/private/" class="danil_button_non_target hello" rel="nofollow">
        <span>Перейти в личный кабинет</span>
    </a>
    </div>
</div>
<style>
    .hello_form h2 {
        font-size: 25px;
    }

    .hello_form p {
        font-size: 20px;
    }

    .hello_form {
        display: flex;
        flex-direction: column;
        padding: 10px 20px 10px 20px;
    }

.hello {
    text-align:center;
}

    @media (max-width:975px) {

        .hello_form h2 {
            text-align:center;
            font-size: 24px;
        }
        .hello_form p {
            text-align:center;
            font-size: 18px;
        }

        .hello_form  .button_container {
            display: flex;
            flex-direction: column;
            margin-top: 10px;
        }
        .danil_button_non_target {
            margin-top:15px;
        }

    }

</style>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>