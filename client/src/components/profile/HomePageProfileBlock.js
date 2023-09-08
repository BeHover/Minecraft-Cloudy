import React from "react";
import HomePageProfileData from "./HomePageProfileData";
import HomePageProfileSkin from "./HomePageProfileSkin";
import HomePageProfilePromocode from "./HomePageProfilePromocode";

export default function HomePageProfileBlock({userData}) {
    return(
        <div className="profile-block__grid">
            <div className="profile-block__grid profile-block__grid--x2">
                <HomePageProfileData userData={userData} />
                <HomePageProfileSkin/>
            </div>
            <HomePageProfilePromocode/>
        </div>
    );
}