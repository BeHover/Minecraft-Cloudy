import React from "react";

export default function HomePageProfilePromocode() {
    return(
        <div className="profile__menu">
            <p className="profile__menu-title">Промокод</p>
            <p className="profile__menu-subtitle">Если у вас есть промокод, активируйте его и получите награду</p>
            <form className="promocode promocode__form">
                <input type="text" className="promocode__input" placeholder="XXX-XXXXXX-XX-XXXX" name="promocode" />
                <button type="button" className="btn btn--primary promocode__btn">Использовать</button>
            </form>
            <button type="button" className="promocode__text">Нажмите, чтобы посмотреть историю активаций<i className="fas fa-history"/></button>
        </div>
    );
}