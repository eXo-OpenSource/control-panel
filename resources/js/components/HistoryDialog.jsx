import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';

export default class HistoryDialog extends Component {
    constructor() {
        super();
    }

    render() {
        return (
            <button type="button" className="btn btn-primary" data-toggle="modal">
                Launch demo modal
            </button>
        );
    }
}

var charts = document.getElementsByTagName('react-history-dialog');

for (var index in charts) {
    const component = charts[index];
    const props = Object.assign({}, component.dataset);
    ReactDOM.render(<HistoryDialog {...props} />, component);
}

/*
                <button type="button" className="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">
                    Launch demo modal
                </button>

                <div className="modal fade" id="exampleModalLong" tabIndex="-1" role="dialog"
                     aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                    <div className="modal-dialog" role="document">
                        <div className="modal-content">
                            <div className="modal-header">
                                <h5 className="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                <button type="button" className="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div className="modal-body">
                                <dl className="user-stats">
                                    <dt>Waffenlevel</dt>
                                </dl>
                            </div>
                            <div className="modal-footer">
                                <button type="button" className="btn btn-secondary" data-dismiss="modal">Schließen
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
 */
