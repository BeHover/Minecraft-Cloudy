import React from "react";

export default function ReportItem({report, handleShow}) {
    return(
        <div className="reports__link">
            <div className="reports__header">
                <div className="reports__data">
                    <p className="reports__type">{report.report.type.name}</p>
                    {report.report.closed
                        ? <p className="reports__status">Обращение решено</p>
                        : <p className="reports__status">В процессе решения</p>
                    }
                </div>
                <button type="button" className="btn btn--black reports__button" onClick={handleShow}>
                    <i className="fas fa-expand-arrows-alt" />
                </button>
            </div>
            <div className="reports__body">
                <p className="reports__description">{report.report.text}</p>
            </div>
            <div className="reports__footer">
                <p className="reports__created">Обращение от:
                    <span>{report.report.created.datetime}</span>
                </p>
            </div>
        </div>
    );
}