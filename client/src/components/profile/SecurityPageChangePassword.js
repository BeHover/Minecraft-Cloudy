import React, {useRef, useState} from "react";
import axios from "axios";

export default function SecurityPageChangePassword() {
    const textRef = useRef(null);
    const [formData, setFormData] = useState({
        nowPassword: '',
        newPassword: '',
        repeatPassword: ''
    });

    const handleChange = event => {
        setFormData({...formData, [event.target.name]: event.target.value});
    };

    const handleSubmit = async event => {
        event.preventDefault();
        try {
            // setIsLoading(true);
            textRef.current.style.display = "flex";
            textRef.current.textContent = "Проверяем указанные данные";
            textRef.current.style.color = "#3c4043";
            const response = await axios.post("https://127.0.0.1:8000/api/user/change-password", formData);

            // setIsLoading(false);
            // navigate("/profile", { replace: true });
            console.log(response.data);
            textRef.current.textContent = "Пароль успешно зарегистрирован";
        } catch (error) {
            if (error.response.status === 405) {
                console.log("crash");
            }
            // setIsLoading(false);
            textRef.current.textContent = error.response.data.message;
            textRef.current.style.color = "#ef2929";
        }
    };

    return(
        <div className="profile-block" style={{alignSelf: "start"}}>
            <p className="profile-block__title">Пароль от аккаунта</p>
            <p className="profile-block__subtitle">Рекомендуем периодически обновлять пароль, чтобы повысить безопасность аккаунта</p>
            <form onSubmit={handleSubmit}>
                <p className="profile-security__text profile-security__text--error" ref={textRef}></p>
                <label htmlFor="nowPassword">
                    <span className="authentication__label">Текущий пароль</span>
                    <input
                        type="password"
                        id="nowPassword"
                        name="nowPassword"
                        value={formData.nowPassword}
                        onChange={handleChange}
                        className="authentication__input"
                        required
                    />
                </label>

                <label htmlFor="newPassword">
                    <span className="authentication__label">Новый пароль</span>
                    <input
                        type="text"
                        id="newPassword"
                        name="newPassword"
                        value={formData.newPassword}
                        onChange={handleChange}
                        className="authentication__input"
                        required
                    />
                </label>

                <div className="profile-security__buttons">
                    <button type="submit" className="btn btn--primary profile-block__button">Изменить пароль</button>
                </div>
            </form>
        </div>
    );
}