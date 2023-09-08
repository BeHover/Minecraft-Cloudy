import React from "react";

export default function BanPageBlock() {
    return(
        <div className="profile-block profile-block--align-start">
            <p className="profile-block__title">Блокировки аккаунта</p>
            <p className="profile-block__subtitle">Здесь отображается вся история ваших блокировок</p>
            <p className="profile-block__text profile-block__text--center">К счастью, у вас пока не было блокировок аккаунта.</p>
        </div>
    );
}