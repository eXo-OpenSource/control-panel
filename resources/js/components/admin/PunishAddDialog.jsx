import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, Table} from 'react-bootstrap';
import axios from "axios";

export default class PunishAddDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            showMessage: false,
            reason: '',
            internal: '',
            duration: 0,
            type: ''
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleShow = () => {
            this.setState({
                show: true,
                reason: '',
                internal: '',
                type: '',
                duration: 0
            });
        };

        this.handleMessageClose = () => {
            this.setState({ show: false });
            this.setState({ showMessage: false });
        };
    }

    async store() {
        if(this.state.type === '') {
            return;
        }

        try {
            const response = await axios.post('/api/admin/users/' + this.props.id + '/punish', {
                type: this.state.type,
                reason: this.state.reason,
                internal: this.state.internal,
                duration: this.state.duration,
            });

            let type = 'danger';

            if(response.data.status === 'Success') {
                type = 'success';
            } else if(response.data.status === 'Warning') {
                type = 'warning';
            }

            this.setState({ show: false });
            this.setState({ showMessage: true, message: response.data.message, messageType: type });
        } catch (error) {
            this.setState({ show: false });
            this.setState({ showMessage: true, message: 'Zugriff verweigert', messageType: 'danger' });
        }
    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    render() {
        return (
            <>
                <Button variant="primary" onClick={this.handleShow}>
                    Hinzufügen
                </Button>

                <Modal show={this.state.show} onHide={this.handleClose} size="lg">
                    <Modal.Header closeButton>
                        <Modal.Title>Strafe</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <Form>
                            <Form.Group controlId="exampleForm.ControlSelect1">
                                <Form.Label>Typ</Form.Label>
                                <Form.Control name="type" as="select" onChange={this.onChange.bind(this)} >
                                    <option value="">[Bitte wählen]</option>
                                    <option value="notice">notice</option>
                                    <option value="teamspeak">teamspeak</option>
                                </Form.Control>
                                <Form.Text className="text-muted">
                                    Der Typ "notice" ist nur für Teammitglieder sichtbar.
                                </Form.Text>
                            </Form.Group>

                            <Form.Group>
                                <Form.Label>Dauer</Form.Label>
                                <InputGroup>
                                    <Form.Control name="duration" type="text" placeholder="Dauer" value={this.state.duration} onChange={this.onChange.bind(this)} />
                                    <InputGroup.Append>
                                        <InputGroup.Text>Stunden</InputGroup.Text>
                                    </InputGroup.Append>
                                </InputGroup>
                                <Form.Text className="text-muted">
                                    Falls permanent oder kein Wert einfach den Wert auf 0 lassen.
                                </Form.Text>
                            </Form.Group>

                            <Form.Group>
                                <Form.Label>Grund</Form.Label>
                                <InputGroup>
                                    <Form.Control name="reason" type="text" onChange={this.onChange.bind(this)} />
                                </InputGroup>
                            </Form.Group>

                            <Form.Group>
                                <Form.Label>Intern</Form.Label>
                                <InputGroup>
                                    <Form.Control name="internal" as="textarea" onChange={this.onChange.bind(this)} />
                                </InputGroup>
                            </Form.Group>
                        </Form>
                    </Modal.Body>
                    <Modal.Footer>

                        <Button variant="primary" onClick={this.store.bind(this)}>
                            Speichern
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

var punish = document.getElementsByTagName('react-punish-add-dialog');

for (var index in punish) {
    const component = punish[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<PunishAddDialog {...props} />, component);
    }
}
