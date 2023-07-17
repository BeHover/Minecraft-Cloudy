import {useNavigate} from "react-router-dom";

export default function NavigateButton({to, text, classNames}) {
    const navigate = useNavigate();

    return (
        <button type="button" onClick={() => navigate(to, { replace: true })} className={classNames}>
            {text}
        </button>
    );
}