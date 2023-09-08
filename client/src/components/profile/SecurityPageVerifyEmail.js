import React from "react";

export default function SecurityPageVerifyEmail() {
    return(
        <div className="profile-block">
            <p className="profile-block__title">Подтверждение почты<i className="fas fa-badge-check profile__verify"></i></p>
            <p className="profile-block__subtitle">С помощью электронной почты можно восстановить доступ к аккаунту</p>
            {/*<p className="profile-block__text profile-block__text--center">Электронная почта подтверждена. Ваш аккаунт в безопасности.</p>*/}
            <form className="profile-promocode profile-promocode__form">
                <input type="text" className="profile-promocode__input" placeholder="XXX-XXX-XXX" name="otp" />
                <button type="button" className="btn btn--primary profile-block__button">Подтвердить почту</button>
            </form>
            <button type="button" className="profile-promocode__text">Нажмите, чтобы выслать письмо на почту с кодом активации<i className="fas fa-reply-all"/></button>
        </div>
    );
}