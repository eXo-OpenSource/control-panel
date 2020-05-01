import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";
import ButtonGroup from "react-bootstrap/ButtonGroup";

export default class TeamSpeakBanDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            showMessage: false,
            duration: '',
            reason: '',
            message: '',
            messageType: '',
            multiplicator: 60 * 60
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleMessageClose = () => {
            this.setState({ showMessage: false });
        };

        this.handleShow = () => {
            this.setState({ show: true });
            if(this.state.data === null) {
                this.loadData();
            }
        };

    }

    async ban() {
        try {
            const response = await axios.put('/api/admin/users/' + this.props.id + '/teamspeak', {
                type: 'ban',
                duration: this.state.duration * this.state.multiplicator,
                reason: this.state.reason
            });

            let type = 'danger';

            if(response.data.status === 'Success') {
                type = 'success';
            }

            this.setState({ show: false });
            this.setState({ showMessage: true, message: response.data.message, messageType: type });
        } catch (error) {
            this.setState({ show: false });
            this.setState({ showMessage: true, message: 'Zugriff verweigert', messageType: 'danger' });
        }
    }

    setTimeMultiplicator(multiplicator) {
        this.setState({multiplicator: multiplicator})
    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    render() {
        return (
            <>
                <span className="dropdown-item" style={{'cursor': 'pointer'}} onClick={this.handleShow}>Sperren</span>

                <Modal show={this.state.show} onHide={this.handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>{this.props.name} sperren</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <Form>
                            <Form.Group>
                                <Form.Label>Dauer</Form.Label>
                                <InputGroup>
                                    <Form.Control name="duration" type="text" placeholder="Dauer" onChange={this.onChange.bind(this)} />
                                    <InputGroup.Append>
                                        <div className="btn-group btn-group-toggle" data-toggle="buttons">
                                            <label className="btn btn-secondary active" onClick={this.setTimeMultiplicator.bind(this, 60 * 60)}>
                                                <input type="radio" name="options" id="option1" checked onClick={this.setTimeMultiplicator.bind(this, 60 * 60)}/> Stunden
                                            </label>
                                            <label className="btn btn-secondary" onClick={this.setTimeMultiplicator.bind(this, 60 * 60 * 24)}>
                                                <input type="radio" name="options" id="option2" onClick={this.setTimeMultiplicator.bind(this, 60 * 60 * 24)}/> Tage
                                            </label>
                                            <label className="btn btn-secondary" onClick={this.setTimeMultiplicator.bind(this, 60 * 60 * 24 * 30)}>
                                                <input type="radio" name="options" id="option3" onClick={this.setTimeMultiplicator.bind(this, 60 * 60 * 24 * 30)}/> Monate
                                            </label>
                                        </div>
                                    </InputGroup.Append>
                                </InputGroup>
                                <Form.Text className="text-muted">
                                    Um einen Spieler permanent zu sperren die Dauer "0" verwenden.
                                </Form.Text>
                            </Form.Group>

                            <Form.Group>
                                <Form.Label>Grund</Form.Label>
                                <InputGroup>
                                    <Form.Control name="reason" type="text" placeholder="Grund" onChange={this.onChange.bind(this)} />
                                </InputGroup>
                            </Form.Group>
                        </Form>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="danger" onClick={this.ban.bind(this)}>
                            Sperren
                        </Button>
                        <Button variant="secondary" onClick={this.handleClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>

                <Modal show={this.state.showMessage} onHide={this.handleMessageClose} dialogClassName={'modal-' + this.state.messageType}>
                    <Modal.Header closeButton>
                        <Modal.Title>Status</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <span>{this.state.message}</span>
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.handleMessageClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>
            </>
        );
    }
}

var banDialogs = document.getElementsByTagName('react-team-speak-ban-dialog');

for (var index in banDialogs) {
    const component = banDialogs[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<TeamSpeakBanDialog {...props} />, component);
    }
}

