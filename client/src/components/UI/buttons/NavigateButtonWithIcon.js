import {useNavigate} from "react-router-dom";

export default function NavigateButtonWithIcon({to, text, icon, classNames, reflect}) {
    const navigate = useNavigate();

    if (reflect === true) {
        return (
            <button type="button" onClick={() => navigate(to, { replace: true })} className={classNames}>
                <span>
                    <i className={icon} style={{marginRight: "8px"}}></i>
                    {text}
                </span>
            </button>
        );
    }

    return (
        <button type="button" onClick={() => navigate(to, { replace: true })} className={classNames}>
            <span>
                {text}
                <i className={icon} style={{marginLeft: "8px"}}></i>
            </span>
        </button>
    );
}