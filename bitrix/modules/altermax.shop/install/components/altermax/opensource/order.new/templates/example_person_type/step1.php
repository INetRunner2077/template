<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
$APPLICATION->SetTitle("Оформление заказа");
?>
<form class="account_active" method="post" action="<?=$APPLICATION->GetCurPage()?>">
<div class="order-section order-section-property">
    <div class="order-section-header">
        <span class="order-section-header-text">АККАУНТ</span>
    </div>
    <div class="properties-1 active">
        <div class="form-row">
            <label>Email</label>
            <input form="fin-order-form" type="email" class="form-control input" value="<?=$arResult['PROP']['EMAIL']?>" required="" readonly="">
        </div>
    </div>
    <div class="button_container">
    <button class="danil_button_non_target" type="button" onclick="location.href='/personal/private/?order=Y'">
        <span>Редактировать аккаунт</span>
    </button>
    <input type="hidden" name="step" value="2">
    <button type="submit" class="danil_button_target">
        <span>Продолжить оформление</span>
    </button>
    </div>
</form>
</div>

<style>

    .button_container {
        margin-top:20px
    }

    .submit-order, .edit-order {
        padding: 9px 13px 8px;
        color: white;
        font-size: 18px;
    }
    .submit-order {
        background: #8A221C;
    }
    .edit-order {
        color:black;
    }
    .button_container button {
        cursor: pointer;
    }

</style>