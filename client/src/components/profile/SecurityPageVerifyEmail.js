import React from "react";

export default function SecurityPageVerifyEmail() {
    return(
        <div className="profile__menu">
            <p className="profile__menu-title">Подтверждение почты<i className="fas fa-badge-check profile__verify"></i></p>
            <p className="profile__menu-subtitle">С помощью электронной почты можно восстановить доступ к аккаунту</p>
            <p className="profile__menu-text" style={{textAlign: "center"}}>Электронная почта подтверждена. Ваш аккаунт в безопасности.</p>
            {/*<form className="promocode promocode__form">*/}
            {/*    <input type="text" className="promocode__input" placeholder="XXX-XXXXXX" name="code" />*/}
            {/*    <button type="button" className="btn btn--primary promocode__btn">Подтвердить почту</button>*/}
            {/*</form>*/}
            {/*<button type="button" className="promocode__text">Нажмите, чтобы выслать письмо на почту с кодом активации<i className="fas fa-reply-all"/></button>*/}
        </div>
    );
}