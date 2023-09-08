import React, {useEffect, useState} from "react";
import { Tab, Tabs, TabList, TabPanel } from 'react-tabs';
import 'react-tabs/style/react-tabs.css';
import "../assets/styles/main.css";
import Header from "../layouts/Header";
import NavigateButtonWithIcon from "../components/UI/buttons/NavigateButtonWithIcon";
import {useNavigate} from "react-router-dom";
import DataLoader from "../components/loaders/DataLoader";
import LoadingBanner from "../components/banners/LoadingBanner";
import HomePageProfileBlock from "../components/profile/HomePageProfileBlock";
import SecurityPageBlock from "../components/profile/SecurityPageBlock";
import BanPageBlock from "../components/profile/BanPageBlock";
import SupportPageBlock from "../components/profile/SupportPageBlock";
import UserLandsList from "../components/profile/UserLands/UserLandsList";
import AdminPageBlock from "../components/profile/AdminPageBlock";
import TabItem from "../components/tabs/TabItem";
import {getToken, getUser} from "../services/UserService";

export default function ProfilePage() {
    const [isAdmin, setIsAdmin] = useState(false);
    const navigate = useNavigate();

    const token = getToken();
    const userData = getUser();

    useEffect(() => {
        if (null === token) {
            return navigate("/login", { replace: true });
        }

        if (userData.roles.includes("ROLE_ADMIN")) {
            setIsAdmin(true);
        }
    }, []);

    return (
        <div id="react-dom" style={{overflow: "hidden", background: "#f7f7f7"}}>
            <Header/>
            {isLoading
                ? <DataLoader title="Происходит загрузка данных пользователя" subtitle="Пожалуйста, дождитесь окончания загрузки данных вашего профиля." />
                : <main>
                    {error
                        ? <LoadingBanner
                            title={error}
                            firstText="Сообщите нам об ошибке, с которой вы столкнулись, если причина её появления вам не ясна или вы не знаете как её устранить."
                            button={<NavigateButtonWithIcon to="/report" text="Сообщить об ошибке" icon="fas fa-flag-alt" classNames="btn btn--red error__href" />}
                            image="https://mc-cloudy.com/public/images/mobile-nav.webp"
                        />
                        : <Tabs>
                            <section className="container container--profile">
                                <div className="profile">
                                    <div className="profile__menu" style={{alignSelf: "start"}}>
                                        <p className="profile__menu-title profile__menu-title--border">Меню управления</p>
                                        <TabList>
                                            <Tab>
                                                <TabItem title="Главная страница" icon="fas fa-home" />
                                            </Tab>
                                            <Tab>
                                                <TabItem title="Безопасность" icon="fas fa-shield" />
                                            </Tab>
                                            <Tab>
                                                <TabItem title="Блокировки" icon="fas fa-ban" />
                                            </Tab>
                                            <Tab>
                                                <TabItem title="Служба поддержки" icon="fas fa-headphones" />
                                            </Tab>
                                            <Tab>
                                                <TabItem title="Управление городом" icon="fas fa-landmark-alt" />
                                            </Tab>
                                            {isAdmin
                                                ? <Tab>
                                                    <TabItem title="Панель администратора" icon="fas fa-tools" />
                                                </Tab>
                                                : ""
                                            }

                                        </TabList>
                                    </div>

                                    <TabPanel>
                                        <HomePageProfileBlock userData={userData} />
                                    </TabPanel>
                                    <TabPanel>
                                        <SecurityPageBlock/>
                                    </TabPanel>
                                    <TabPanel>
                                        <BanPageBlock/>
                                    </TabPanel>
                                    <TabPanel>
                                        <SupportPageBlock/>
                                    </TabPanel>
                                    <TabPanel>
                                        <UserLandsList/>
                                    </TabPanel>
                                    {isAdmin
                                        ? <TabPanel>
                                            <AdminPageBlock/>
                                        </TabPanel>
                                        : ""
                                    }

                                </div>
                            </section>
                        </Tabs>
                    }
                </main>
            }
        </div>
    );
}