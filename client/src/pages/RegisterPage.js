import React, {useEffect, useRef, useState} from 'react';
import axios from 'axios';
import "../assets/styles/main.css";
import NavigateButtonWithIcon from "../components/UI/buttons/NavigateButtonWithIcon";
import {useNavigate} from "react-router-dom";

export default function RegisterPage() {
    const [formData, setFormData] = useState({
        username: '',
        password: '',
        email: ''
    });

    const handleChange = event => {
        setFormData({...formData, [event.target.name]: event.target.value});
    };

    const [loading, setLoading] = useState(false);
    const navigate = useNavigate();
    const textRef = useRef(null);

    useEffect(() => {
        if (localStorage.getItem("token")) {
            return navigate("/profile", { replace: true });
        }
    }, []);

    const handleSubmit = async event => {
        event.preventDefault();
        try {
            setLoading(true);
            textRef.current.textContent = "Обрабатываем данные для создания нового аккаунта";
            textRef.current.style.color = "#3c4043";
            const response = await axios.post("https://127.0.0.1:8000/api/register", formData);
            localStorage.setItem("token", response.data.token);

            setLoading(false);
            navigate("/profile", { replace: true });
            textRef.current.textContent = "Аккаунт успешно зарегистрирован";
        } catch (error) {
            setLoading(false);
            textRef.current.textContent = error.response.data.message;
            textRef.current.style.color = "#ef2929";
        }
    };

    return (
        <div id="react-dom" className="authentication" style={{overflow: "hidden"}}>
            <section className="container section information authentication__content">
                <form onSubmit={handleSubmit}>
                    <p className="information__label information__label--center">Вселенная развлечений</p>
                    <h2 className="information__title information__title--center" style={{textTransform: "none"}}>Регистрация аккаунта</h2>
                    <p className="information__text information__text--center" ref={textRef}>Заполните все поля для регистрации нового аккаунта</p>

                    {loading
                        ? <div className="loader">Loading</div>
                        : <div className="authentication__box">
                            <div className="authentication__inputs">
                                <label htmlFor="username">
                                    <span className="authentication__label">Игровой никнейм</span>
                                    <input
                                        type="text"
                                        id="username"
                                        name="username"
                                        value={formData.username}
                                        onChange={handleChange}
                                        className="authentication__input"
                                        required
                                    />
                                </label>

                                <label htmlFor="password" className="authentication__label">
                                    <span className="authentication__label">Пароль от аккаунта</span>
                                    <input
                                        type="password"
                                        id="password"
                                        name="password"
                                        value={formData.password}
                                        onChange={handleChange}
                                        className="authentication__input"
                                        required
                                    />
                                </label>

                                <label htmlFor="email" className="authentication__label">
                                    <span className="authentication__label">Электронная почта</span>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value={formData.email}
                                        onChange={handleChange}
                                        className="authentication__input"
                                        required
                                    />
                                </label>
                            </div>

                            <button className="btn btn--primary" type="submit">
                                Создать аккаунт
                            </button>

                            <ul className="authentication__list">
                                <li className="authentication__item">
                                    <NavigateButtonWithIcon to="/login" text="Войти в существующий аккаунт" icon="fas fa-user-circle" classNames="authentication__info authentication__info--left" reflect={true}></NavigateButtonWithIcon>
                                </li>
                                <li className="authentication__item">
                                    <NavigateButtonWithIcon to="/restore-password" text="Восстановить доступ к аккаунту" icon="fas fa-question-circle" classNames="authentication__info authentication__info--left" reflect={true}></NavigateButtonWithIcon>
                                </li>
                                <li className="authentication__item">
                                    <NavigateButtonWithIcon to="/" text="Вернуться на главную" icon="fas fa-arrow-circle-left" classNames="authentication__info authentication__info--left" reflect={true}></NavigateButtonWithIcon>
                                </li>
                            </ul>
                        </div>
                    }
                </form>
            </section>
        </div>
    );
}