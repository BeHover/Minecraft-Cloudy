import React from "react";

export default function TabItem({title, icon}) {
    return(
        <span>
            <span className="profile__menu-nav-item-link-icon"><i className={icon} /></span>
            {title}
        </span>
    );
}