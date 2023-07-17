import React, {useEffect, useRef, useState} from 'react';
import axios from 'axios';
import "../assets/styles/main.css";
import NavigateButtonWithIcon from "../components/UI/buttons/NavigateButtonWithIcon";
import {useNavigate} from "react-router-dom";

export default function LoginPage() {
    const [formData, setFormData] = useState({
        username: '',
        password: ''
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
            textRef.current.textContent = "Проверка наличия аккаунта с такими данными";
            textRef.current.style.color = "#3c4043";
            const response = await axios.post("https://127.0.0.1:8000/api/login", formData);
            localStorage.setItem("token", response.data.token);

            setLoading(false);
            navigate("/profile", { replace: true });
            textRef.current.textContent = "Добро пожаловать в личный кабинет";
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
                    <h2 className="information__title information__title--center" style={{textTransform: "none"}}>Вход в личный кабинет</h2>
                    <p className="information__text information__text--center" ref={textRef}>Авторизуйтесь, чтобы перейти в личный кабинет</p>

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
                            </div>

                            <button className="btn btn--primary" type="submit">
                                Войти в аккаунт
                            </button>

                            <ul className="authentication__list">
                                <li className="authentication__item">
                                    <NavigateButtonWithIcon to="/restore-password" text="Восстановить пароль от аккаунта" icon="fas fa-question-circle" classNames="authentication__info authentication__info--left" reflect={true}></NavigateButtonWithIcon>
                                </li>
                                <li className="authentication__item">
                                    <NavigateButtonWithIcon to="/register" text="Создать новый аккаунт" icon="fas fa-plus-circle" classNames="authentication__info authentication__info--left" reflect={true}></NavigateButtonWithIcon>
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