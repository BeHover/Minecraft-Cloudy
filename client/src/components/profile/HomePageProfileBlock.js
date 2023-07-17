import React from "react";
import HomePageProfileData from "./HomePageProfileData";
import HomePageProfileSkin from "./HomePageProfileSkin";
import HomePageProfilePromocode from "./HomePageProfilePromocode";

export default function HomePageProfileBlock({userData}) {
    return(
        <div className="profile__grid">
            <div className="profile__grid profile__grid--2">
                <HomePageProfileData userData={userData} />
                <HomePageProfileSkin/>
            </div>
            <HomePageProfilePromocode/>
        </div>
    );
}