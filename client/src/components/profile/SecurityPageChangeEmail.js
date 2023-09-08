import React from "react";

export default function SecurityPageChangeEmail() {
    return(
        <div className="profile-block">
            <p className="profile-block__title">Электронная почта</p>
            <p className="profile-block__subtitle">При фиксации передачи аккаунта сторонним лицам он будет навсегда заблокирован</p>
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

                <div className="profile-security__buttons">
                    <button type="submit" className="btn btn--primary profile-block__button">Изменить почту</button>
                </div>
            </form>
        </div>
    );
}