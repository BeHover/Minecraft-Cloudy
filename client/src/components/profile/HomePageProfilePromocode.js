import React from "react";

export default function HomePageProfilePromocode() {
    return(
        <div className="profile-block">
            <p className="profile-block__title">Промокод</p>
            <p className="profile-block__subtitle">Если у вас есть промокод, активируйте его и получите награду</p>
            <form className="profile-promocode profile-promocode__form">
                <input type="text" className="profile-promocode__input" placeholder="XXX-XXXXXX-XX-XXXX" name="promocode" />
                <button type="button" className="btn btn--primary profile-block__button">Использовать промокод</button>
            </form>
            <button type="button" className="profile-promocode__text">Нажмите, чтобы посмотреть историю активаций<i className="fas fa-history"/></button>
        </div>
    );
}