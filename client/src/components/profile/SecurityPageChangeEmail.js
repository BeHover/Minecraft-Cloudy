import React from "react";

export default function SecurityPageChangeEmail() {
    return(
        <div className="profile__menu" style={{alignSelf: "start"}}>
            <p className="profile__menu-title">Электронная почта</p>
            <p className="profile__menu-subtitle">При фиксации передачи аккаунта сторонним лицам он будет навсегда заблокирован</p>
            <form>
                <label htmlFor="username">
                    <span className="authentication__label">Текущая почта</span>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        className="authentication__input"
                        required
                    />
                </label>

                <label htmlFor="username">
                    <span className="authentication__label">Новая почта</span>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        className="authentication__input"
                        required
                    />
                </label>

                <label htmlFor="username">
                    <span className="authentication__label">Повторите новую почту</span>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        className="authentication__input"
                        required
                    />
                </label>

                <div style={{display: "flex", justifyContent: "flex-end", marginTop: "20px"}}>
                    <button type="submit" className="btn btn--primary promocode__btn">Изменить почту</button>
                </div>
            </form>
        </div>
    );
}