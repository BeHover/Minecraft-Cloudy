import React, {useEffect} from "react";
import 'react-tabs/style/react-tabs.css';
import "../../assets/styles/main.css";
import Header from "../../layouts/Header";
import {useNavigate} from "react-router-dom";
import {getToken, getUser} from "../../services/UserService";
import HomePageProfileBlock from "../../components/profile/HomePageProfileBlock";

import "../../assets/styles/components/Profile/profile.css";
import ProfileMenu from "../../components/profile/ProfileMenu";

export default function ProfilePage() {
    const token = getToken();
    const userData = getUser();
    const navigate = useNavigate();

    useEffect(() => {
        if (null === token) {
            return navigate("/login", { replace: true });
        }
    }, []);

    return (
        <div id="cloudy-web-content" className="cloudy-background">
            <Header />
            <main>
                <section className="container container--profile">
                    <div className="profile">
                        <ProfileMenu />
                        <HomePageProfileBlock userData={userData} />
                    </div>
                </section>
            </main>
        </div>
    );
}