import React from "react";

export default function DownloadFileButton({to, text, classNames}) {
    return (
        <a href={to} download={true} target="_blank" className={classNames}>
            {text}
        </a>
    );
}