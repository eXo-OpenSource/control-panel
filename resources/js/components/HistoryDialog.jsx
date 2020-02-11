import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";

export default class HistoryDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            data: null
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleShow = () => {
            this.setState({ show: true });
            if(this.state.data === null) {
                this.loadData();
            }
        };
    }

    async loadData() {
        const response = await axios.get('/api/histories/' + this.props.historyId);

        try {
            this.setState({
                data: response.data
            });
        } catch (error) {
            console.log(error);
        }
    }

    render() {

        let body = <Spinner animation="border" />;

        if(this.state.data) {
            let inviter = this.state.data.InviterId !== 0 ? <dd><a href={this.state.data.InviterUrl}>{this.state.data.Inviter}</a></dd> : <dd>{this.state.data.Inviter}</dd>;
            let uninviter = this.state.data.UninviterId !== 0 ? <dd><a href={this.state.data.UninviterUrl}>{this.state.data.Uninviter}</a></dd> : <dd>{this.state.data.Uninviter}</dd>;
            let internalReason = this.state.data.InternalReason ? <><dt>Interner Grund</dt><dd>{this.state.data.InternalReason}</dd></>: '';

            body =  <div>
                        <dl className="user-stats">
                            <dt>{this.state.data.ElementTypeName}</dt>
                            <dd><a href={this.state.data.ElementUrl}>{this.state.data.Element}</a></dd>
                            <dt>Inviter</dt>
                            {inviter}
                            <dt>Uninviter</dt>
                            {uninviter}
                            <dt>Beitrittsdatum</dt>
                            {this.state.data.JoinDateText}
                            <dt>Uninvitedatum</dt>
                            {this.state.data.LeaveDateText}
                            <dt>Dauer</dt>
                            {this.state.data.Duration}
                            <dt>Höchste Rang</dt>
                            {this.state.data.HighestRank}
                            <dt>Rang beim Uninvite</dt>
                            {this.state.data.UninviteRank}
                            <dt>Grund</dt>
                            {this.state.data.ExternalReason}
                            {internalReason}
                        </dl>
                    </div>;
        }

        return (
            <>
                <Button variant="primary" onClick={this.handleShow}>
                    Details
                </Button>

                <Modal show={this.state.show} onHide={this.handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>Spielerakte</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {body}
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.handleClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>
            </>
        );
    }
}

var history = document.getElementsByTagName('react-history-dialog');

for (var index in history) {
    const component = history[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<HistoryDialog {...props} />, component);
    }
}
